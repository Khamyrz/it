<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\RoomItem;
use App\Models\Borrow;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Check if this is a new user for data isolation
        $isNewUser = $user->is_new_user;

        // Count items grouped by device category - create fresh query
        $itemCountsQuery = $isNewUser ? 
            RoomItem::where('user_id', $user->id) : 
            RoomItem::query();
        $itemCounts = $itemCountsQuery->select('device_category')
            ->selectRaw('count(*) as total')
            ->groupBy('device_category')
            ->get();

        // Usable and Unusable device count - create fresh queries
        $usableCountQuery = $isNewUser ? 
            RoomItem::where('user_id', $user->id)->where('status', 'Usable') : 
            RoomItem::where('status', 'Usable');
        $usableCount = $usableCountQuery->count();
        
        $unusableCountQuery = $isNewUser ? 
            RoomItem::where('user_id', $user->id)->where('status', 'Unusable') : 
            RoomItem::where('status', 'Unusable');
        $unusableCount = $unusableCountQuery->count();

        // All borrowed items - filter by user's items if new user
        $borrowQuery = Borrow::where('status', 'Borrowed');
        if ($isNewUser) {
            $borrowQuery->whereHas('roomItem', function($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }
        $borrowedCount = $borrowQuery->count();

        // Get all room items and classify them by device type - create fresh query
        $allItemsQuery = $isNewUser ? 
            RoomItem::where('user_id', $user->id) : 
            RoomItem::query();
        $allRoomItems = $allItemsQuery->get();
        
        // Count peripherals and computer units
        $peripheralCount = 0;
        $computerUnitCount = 0;
        
        foreach ($allRoomItems as $item) {
            $deviceType = $this->getDeviceType($item->device_category);
            if ($deviceType === 'Peripherals') {
                $peripheralCount++;
            } elseif ($deviceType === 'Computer Units') {
                $computerUnitCount++;
            }
        }

        // Load active borrowed items with related roomItem
        $activeBorrowedQuery = Borrow::where('status', 'Borrowed')->with('roomItem');
        if ($isNewUser) {
            $activeBorrowedQuery->whereHas('roomItem', function($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }
        $activeBorrowedRoomItems = $activeBorrowedQuery->get();

        // Classify borrowed devices using the same logic
        $borrowedPeripheralCount = 0;
        $borrowedComputerCount = 0;
        
        foreach ($activeBorrowedRoomItems as $borrow) {
            if ($borrow->roomItem) {
                $deviceType = $this->getDeviceType($borrow->roomItem->device_category);
                if ($deviceType === 'Peripherals') {
                    $borrowedPeripheralCount++;
                } elseif ($deviceType === 'Computer Units') {
                    $borrowedComputerCount++;
                }
            }
        }

        // Count usable peripherals and computer units
        $usablePeripheralCount = 0;
        $usableComputerUnitCount = 0;
        $unusablePeripheralCount = 0;
        $unusableComputerUnitCount = 0;

        // Create separate queries to avoid query object reuse issues
        $usableItemsQuery = $isNewUser ? 
            RoomItem::where('user_id', $user->id)->where('status', 'Usable') : 
            RoomItem::where('status', 'Usable');
        $usableItems = $usableItemsQuery->get();
        
        $unusableItemsQuery = $isNewUser ? 
            RoomItem::where('user_id', $user->id)->where('status', 'Unusable') : 
            RoomItem::where('status', 'Unusable');
        $unusableItems = $unusableItemsQuery->get();

        foreach ($usableItems as $item) {
            $deviceType = $this->getDeviceType($item->device_category);
            if ($deviceType === 'Peripherals') {
                $usablePeripheralCount++;
            } elseif ($deviceType === 'Computer Units') {
                $usableComputerUnitCount++;
            }
        }

        foreach ($unusableItems as $item) {
            $deviceType = $this->getDeviceType($item->device_category);
            if ($deviceType === 'Peripherals') {
                $unusablePeripheralCount++;
            } elseif ($deviceType === 'Computer Units') {
                $unusableComputerUnitCount++;
            }
        }

        // Pending user approvals
        $pendingUsers = User::where('is_approved', false)->get();

        // Latest 5 borrowed items
        $recentBorrowedQuery = Borrow::with('roomItem')->orderByDesc('borrow_date');
        if ($isNewUser) {
            $recentBorrowedQuery->whereHas('roomItem', function($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }
        $recentBorrowedItems = $recentBorrowedQuery->take(5)->get();

        // Group room items by room title - create fresh query
        $roomItemCountsQuery = $isNewUser ? 
            RoomItem::where('user_id', $user->id) : 
            RoomItem::query();
        $roomItemCounts = $roomItemCountsQuery->select('room_title')
            ->selectRaw('count(*) as total')
            ->groupBy('room_title')
            ->get();

        // Group room items by device type
        $deviceTypeCounts = [
            'Peripherals' => $peripheralCount,
            'Computer Units' => $computerUnitCount
        ];

        return view('dashboard', compact(
            'user',
            'itemCounts',
            'usableCount',
            'unusableCount',
            'borrowedCount',
            'borrowedPeripheralCount',
            'borrowedComputerCount',
            'pendingUsers',
            'recentBorrowedItems',
            'roomItemCounts',
            'peripheralCount',
            'computerUnitCount',
            'deviceTypeCounts',
            'usablePeripheralCount',
            'usableComputerUnitCount',
            'unusablePeripheralCount',
            'unusableComputerUnitCount'
        ));
    }

    /**
     * Auto-assign device type based on device category
     * Same logic as in RoomManagementController
     */
    private function getDeviceType($deviceCategory)
    {
        $deviceCategory = strtolower($deviceCategory);
        
        // Define peripherals
        $peripherals = [
            'keyboard', 'mouse', 'monitor', 'printer', 'scanner', 'webcam',
            'microphone', 'external hard drive', 'usb flash drive', 'headphones',
            'modem', 'wi-fi adapter', 'speakers', 'flash drive', 'usb hub', 'nic',
            'headset', 'projector', 'router', 'switch'
        ];
        
        // Define computer units
        $computerUnits = [
            'system unit', 'central processing unit', 'cpu', 'graphics processing unit',
            'gpu', 'graphic card', 'video card', 'random access memory', 'ram',
            'storage devices', 'hard disk drives', 'hdds', 'usb flash drives',
            'external ssds', 'ssd', 'motherboard', 'power supply unit', 'psu'
        ];
        
        // Check if device category matches any peripheral
        if (in_array($deviceCategory, $peripherals)) {
            return 'Peripherals';
        }
        
        // Check if device category matches any computer unit
        if (in_array($deviceCategory, $computerUnits)) {
            return 'Computer Units';
        }
        
        // If no match found, return 'Other'
        return 'Other';
    }
}