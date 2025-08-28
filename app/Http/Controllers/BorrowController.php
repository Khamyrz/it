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
        // Eager load the latestBorrow relation for each item
        $items = RoomItem::with('latestBorrow')->get();

        // Only show usable items that are not currently borrowed
        $availableItems = RoomItem::where('status', 'Usable')
            ->whereDoesntHave('latestBorrow', function ($query) {
                $query->where('status', 'Borrowed');
            })->get();

        // Monthly borrow tracker
        $activities = Borrow::with('roomItem')
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

        // Create borrow record
        Borrow::create([
            'room_item_id' => $request->room_item_id,
            'borrower_name' => $request->borrower_name,
            'borrow_date' => $request->borrow_date,
            'status' => 'Borrowed',
        ]);

        // Update item status to "Borrowed"
        $item = RoomItem::findOrFail($request->room_item_id);
        $item->status = 'Borrowed';
        $item->save();

        return redirect('/borrow')->with('success', 'Item successfully borrowed!');
    }

    public function returnItem($id)
    {
        $borrow = Borrow::findOrFail($id);
        $borrow->status = 'Returned';
        $borrow->return_date = now();
        $borrow->save();

        // Restore item status to "Usable"
        $item = RoomItem::findOrFail($borrow->room_item_id);
        $item->status = 'Usable';
        $item->save();

        return redirect('/borrow')->with('success', 'Item successfully returned!');
    }
}
