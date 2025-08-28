<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomItem;
use App\Models\Category;
use App\Models\Room;

class CategoryController extends Controller
{
    /**
     * Display the categories, rooms, and grouped room items.
     */
    public function index()
    {
        // Fetch all room items
        $roomItems = RoomItem::all();

        // Group them by normalized room_title
        $groupedItems = $roomItems->groupBy(function ($item) {
            return trim(strtolower($item->room_title));
        });

        // Return view with all needed data
        return view('categories_rooms', [
            'categories'   => Category::all(),
            'rooms'        => Room::all(),
            'groupedItems' => $groupedItems,
        ]);
    }

    /**
     * Store a new category.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        Category::create([
            'name' => $request->input('category_name'),
        ]);

        return redirect()->route('categories.index')->with('success', 'Category added successfully!');
    }

    /**
     * Show the form for editing a category.
     */
    public function edit(Category $category)
    {
        return view('edit_category', compact('category'));
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        $category->update([
            'name' => $request->input('category_name'),
        ]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }
}

