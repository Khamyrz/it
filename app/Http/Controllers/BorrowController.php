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
        
        // Always filter by authenticated user for data isolation
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
        
        // Always verify the item belongs to the user
        $item = RoomItem::where('id', $request->room_item_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

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
        
        // Always find borrow record and verify user has access to the item
        $borrow = Borrow::with('roomItem')
            ->where('id', $id)
            ->whereHas('roomItem', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->firstOrFail();
        
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
