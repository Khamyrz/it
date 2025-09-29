<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomItem;
use App\Models\MaintenanceNote;


class MaintenanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Check if this is a new user
        $isNewUser = $user->is_new_user;
        
        if ($isNewUser) {
            // New users only see their own items
            $items = RoomItem::where('user_id', $user->id)
                             ->whereNotNull('full_set_id')
                             ->orderBy('full_set_id')
                             ->orderBy('device_category')
                             ->orderBy('created_at', 'desc')
                             ->get();
        } else {
            // Old users see all items (backward compatibility)
            $items = RoomItem::whereNotNull('full_set_id')
                             ->orderBy('full_set_id')
                             ->orderBy('device_category')
                             ->orderBy('created_at', 'desc')
                             ->get();
        }
        
        // Group items by full_set_id using arrays
        $fullsets = [];
        
        foreach ($items as $item) {
            $fullsetId = $item->full_set_id;
            
            if ($fullsetId) {
                if (!isset($fullsets[$fullsetId])) {
                    $fullsets[$fullsetId] = [
                        'id' => $fullsetId,
                        'room' => $item->room_title,
                        'items' => collect(),
                        'usable_count' => 0,
                        'unusable_count' => 0,
                        'total_count' => 0,
                        'categories' => [],
                        'display_name' => $item->barcode // Use barcode as display name
                    ];
                } else {
                    // Update display name if current item has a barcode that should be prioritized
                    // You can customize this logic based on your needs (e.g., use first barcode, or specific device category)
                    if (!empty($item->barcode) && (empty($fullsets[$fullsetId]['display_name']) || $item->device_category === 'PC')) {
                        $fullsets[$fullsetId]['display_name'] = $item->barcode;
                    }
                }
                
                $fullsets[$fullsetId]['items']->push($item);
                $fullsets[$fullsetId]['total_count']++;
                
                // Track device categories
                if (!in_array($item->device_category, $fullsets[$fullsetId]['categories'])) {
                    $fullsets[$fullsetId]['categories'][] = $item->device_category;
                }
                
                if ($item->status === 'Usable') {
                    $fullsets[$fullsetId]['usable_count']++;
                } elseif ($item->status === 'Unusable') {
                    $fullsets[$fullsetId]['unusable_count']++;
                }
            }
        }

        // Sort categories within each fullset
        foreach ($fullsets as &$fullset) {
            sort($fullset['categories']);
        }

        // Convert to collection for consistency with blade templates
        $fullsets = collect($fullsets);

        // Get maintenance notes for each fullset
        $notes = MaintenanceNote::whereIn('fullset_id', array_keys($fullsets->toArray()))->get()->keyBy('fullset_id');

        return view('maintenance', compact('fullsets', 'notes'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Usable,Unusable'
        ]);

        $item = RoomItem::findOrFail($id);
        $oldStatus = $item->status;
        $item->status = $request->status;
        $item->save();

        $message = "Item '{$item->device_category}' in {$item->barcode} status updated from '{$oldStatus}' to '{$request->status}'.";

        return redirect()->back()->with('success', $message);
    }

    public function updateNote(Request $request, $fullsetId)
    {
        $request->validate([
            'note' => 'nullable|string|max:1000'
        ]);

        $user = auth()->user();
        
        // Verify the fullset belongs to the user (for new users) or exists (for old users)
        $itemQuery = RoomItem::where('full_set_id', $fullsetId);
        if ($user->is_new_user) {
            $itemQuery->where('user_id', $user->id);
        }
        $item = $itemQuery->firstOrFail();
        
        $displayName = $item->barcode;

        MaintenanceNote::updateOrCreate(
            ['fullset_id' => $fullsetId],
            ['note' => $request->note, 'updated_at' => now()]
        );

        return redirect()->back()->with('success', 'Note updated successfully for ' . $displayName);
    }

    public function updateBulkStatus(Request $request, $fullsetId)
    {
        $request->validate([
            'category' => 'required|string',
            'status' => 'required|in:Usable,Unusable'
        ]);

        $user = auth()->user();
        
        // Build query with user isolation
        $itemsQuery = RoomItem::where('full_set_id', $fullsetId)
                             ->where('device_category', $request->category);
        if ($user->is_new_user) {
            $itemsQuery->where('user_id', $user->id);
        }
        $items = $itemsQuery->get();

        if ($items->isEmpty()) {
            return redirect()->back()->with('error', 'No items found for the specified category.');
        }

        // Get display name from first item
        $displayName = $items->first()->barcode;

        $updatedCount = 0;
        foreach ($items as $item) {
            if ($item->status !== $request->status) {
                $item->status = $request->status;
                $item->save();
                $updatedCount++;
            }
        }

        $message = "Updated {$updatedCount} items in category '{$request->category}' of {$displayName} to '{$request->status}'.";

        return redirect()->back()->with('success', $message);
    }
}