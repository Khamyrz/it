<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrow;
use App\Models\RoomItem;
use Carbon\Carbon;

class PrintReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Check if this is a new user for data isolation
        $isNewUser = $user->is_new_user;
        
        // Get filter from query string; default to 'daily'
        $filter = $request->input('filter', 'daily');

        // Fetch borrowed items with related room items
        $borrowQuery = Borrow::with('roomItem');
        
        // Apply user-based filtering for new users
        if ($isNewUser) {
            $borrowQuery->whereHas('roomItem', function($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        // Apply date-based filtering
        switch ($filter) {
            case 'weekly':
                $borrowQuery->whereBetween('borrow_date', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek(),
                ]);
                break;
            case 'monthly':
                $borrowQuery->whereMonth('borrow_date', Carbon::now()->month)
                            ->whereYear('borrow_date', Carbon::now()->year);
                break;
            case 'daily':
            default:
                $borrowQuery->whereDate('borrow_date', Carbon::today());
                break;
        }

        $borrowedItems = $borrowQuery->orderBy('borrow_date', 'desc')->get();

        // Fetch room items based on user type
        $roomItemQuery = $isNewUser ? 
            RoomItem::where('user_id', $user->id) : 
            RoomItem::query();
        $roomItems = $roomItemQuery->orderBy('created_at', 'desc')->get();

        // Return both datasets to the view
        return view('print-report', compact('borrowedItems', 'roomItems', 'filter'));
    }
}
