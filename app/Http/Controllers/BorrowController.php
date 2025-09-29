<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomItem;
use App\Models\Borrow;
use Carbon\Carbon;

class BorrowController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Check if this is a new user
        $isNewUser = $user->is_new_user;
        
        if ($isNewUser) {
            // New users only see their own items
            $items = RoomItem::where('user_id', $user->id)->with('latestBorrow')->get();
            $availableItems = RoomItem::where('user_id', $user->id)
                ->where('status', 'Usable')
                ->whereDoesntHave('latestBorrow', function ($query) {
                    $query->where('status', 'Borrowed');
                })->get();
            $activities = Borrow::with('roomItem')
                ->whereHas('roomItem', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->whereMonth('borrow_date', now()->month)
                ->whereYear('borrow_date', now()->year)
                ->orderByDesc('borrow_date')
                ->get();
        } else {
            // Old users see all items (backward compatibility)
            $items = RoomItem::with('latestBorrow')->get();
            $availableItems = RoomItem::where('status', 'Usable')
                ->whereDoesntHave('latestBorrow', function ($query) {
                    $query->where('status', 'Borrowed');
                })->get();
            $activities = Borrow::with('roomItem')
                ->whereMonth('borrow_date', now()->month)
                ->whereYear('borrow_date', now()->year)
                ->orderByDesc('borrow_date')
                ->get();
        }

        return view('borrow', [
            'items' => $items,
            'availableItems' => $availableItems,
            'activities' => $activities,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_item_id' => 'required|exists:room_items,id',
            'borrower_name' => 'required|string|max:255',
            'borrow_date' => 'required|date',
        ]);

        $user = auth()->user();
        
        // Verify the item belongs to the user (for new users) or exists (for old users)
        $itemQuery = RoomItem::where('id', $request->room_item_id);
        if ($user->is_new_user) {
            $itemQuery->where('user_id', $user->id);
        }
        $item = $itemQuery->firstOrFail();

        // Create borrow record
        Borrow::create([
            'room_item_id' => $request->room_item_id,
            'borrower_name' => $request->borrower_name,
            'borrow_date' => $request->borrow_date,
            'status' => 'Borrowed',
        ]);

        // Update item status to "Borrowed"
        $item->status = 'Borrowed';
        $item->save();

        return redirect('/borrow')->with('success', 'Item successfully borrowed!');
    }

    public function returnItem($id)
    {
        $user = auth()->user();
        
        // Find borrow record and verify user has access to the item
        $borrowQuery = Borrow::with('roomItem')->where('id', $id);
        if ($user->is_new_user) {
            $borrowQuery->whereHas('roomItem', function($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }
        $borrow = $borrowQuery->firstOrFail();
        
        $borrow->status = 'Returned';
        $borrow->return_date = now();
        $borrow->save();

        // Restore item status to "Usable"
        $item = $borrow->roomItem;
        $item->status = 'Usable';
        $item->save();

        return redirect('/borrow')->with('success', 'Item successfully returned!');
    }
}
