<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomItem;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display categories with related room items and unique room titles.
     */
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        $items = RoomItem::all();
        $rooms = RoomItem::select('room_title')->distinct()->orderBy('room_title')->get();

        // RoomItem-based unique categories
        $roomItemCategories = RoomItem::select('device_category')
            ->whereNotNull('device_category')
            ->distinct()
            ->pluck('device_category');

        $itemCounts = RoomItem::selectRaw('device_category, COUNT(*) as total')
            ->groupBy('device_category')
            ->pluck('total', 'device_category');

        return view('categories', compact(
            'categories',
            'items',
            'rooms',
            'roomItemCategories',
            'itemCounts'
        ));
    }

    /**
     * Get items by identifier (room or category) with totals for rooms.
     */
    public function getItemsByIdentifier($identifier)
    {
        $type = request()->get('type', 'category'); // Default to category if not specified
        
        if ($type === 'room') {
            // Get items by room
            $items = RoomItem::where('room_title', $identifier)
                ->orderBy('device_category')
                ->orderBy('serial_number')
                ->get();
            
            // Calculate category totals for the room
            $categoryTotals = $items->groupBy('device_category')
                ->map(function ($group) {
                    return $group->count();
                })
                ->toArray();
            
            return response()->json([
                'items' => $items,
                'categoryTotals' => $categoryTotals,
                'type' => 'room'
            ]);
        } else {
            // Get items by category (existing functionality)
            $items = RoomItem::where('device_category', $identifier)
                ->orderBy('room_title')
                ->orderBy('serial_number')
                ->get();
            
            return response()->json([
                'items' => $items,
                'type' => 'category'
            ]);
        }
    }

    /**
     * Store a new category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create($validated);

        return redirect()->back()->with('success', 'Category added successfully!');
    }

    /**
     * Update an existing category.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update($validated);

        return redirect()->back()->with('success', 'Category updated successfully!');
    }

    /**
     * Delete a category and optionally handle its associations.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Optional logic: If needed to detach from RoomItems
        // RoomItem::where('device_category', $category->name)->update(['device_category' => null]);

        $category->delete();

        return redirect()->back()->with('success', 'Category deleted successfully!');
    }

    /**
     * Export database as SQL file
     */
    public function exportSql()
    {
        try {
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            
            // Get all tables
            $tables = \DB::select('SHOW TABLES');
            $databaseName = 'Tables_in_' . $database;
            
            $sql = "-- Database Export\n";
            $sql .= "-- Generated: " . now()->toDateTimeString() . "\n";
            $sql .= "-- Database: {$database}\n\n";
            $sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
            $sql .= "SET time_zone = \"+00:00\";\n\n";
            
            foreach ($tables as $table) {
                $tableName = $table->$databaseName;
                
                // Skip migrations table if you want
                // if ($tableName === 'migrations') continue;
                
                // Get table structure
                $createTable = \DB::select("SHOW CREATE TABLE `{$tableName}`");
                $sql .= "\n-- --------------------------------------------------------\n";
                $sql .= "-- Table structure for table `{$tableName}`\n";
                $sql .= "-- --------------------------------------------------------\n\n";
                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sql .= $createTable[0]->{'Create Table'} . ";\n\n";
                
                // Get table data
                $rows = \DB::table($tableName)->get();
                if ($rows->count() > 0) {
                    $sql .= "-- Dumping data for table `{$tableName}`\n\n";
                    
                    foreach ($rows as $row) {
                        $values = [];
                        foreach ((array)$row as $value) {
                            if ($value === null) {
                                $values[] = 'NULL';
                            } else {
                                $values[] = "'" . addslashes($value) . "'";
                            }
                        }
                        $sql .= "INSERT INTO `{$tableName}` VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $sql .= "\n";
                }
            }
            
            // Create response with SQL content
            $filename = 'database_export_' . date('Y-m-d_His') . '.sql';
            
            return response($sql)
                ->header('Content-Type', 'application/sql')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
                
        } catch (\Exception $e) {
            return response()->json(['error' => 'Export failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Toggle automatic exports
     */
    public function toggleAutoExport(Request $request)
    {
        $enabled = $request->input('enabled', false);
        
        // Store setting in database or config file
        // For simplicity, we'll use a config file
        $configPath = config_path('auto_export.php');
        $config = ['enabled' => $enabled];
        
        if (!file_exists(config_path())) {
            mkdir(config_path(), 0755, true);
        }
        
        file_put_contents($configPath, '<?php return ' . var_export($config, true) . ';');
        
        return response()->json([
            'success' => true,
            'enabled' => $enabled
        ]);
    }

    /**
     * Get auto-export status
     */
    public function getAutoExportStatus()
    {
        $configPath = config_path('auto_export.php');
        $enabled = false;
        
        if (file_exists($configPath)) {
            $config = require $configPath;
            $enabled = $config['enabled'] ?? false;
        }
        
        return response()->json(['enabled' => $enabled]);
    }
}