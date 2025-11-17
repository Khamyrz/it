<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RoomItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'photo',
        'room_title',
        'device_category',
        'device_type',
        'brand',
        'model',
        'serial_number',
        'description',
        'barcode',
        'status',
        'quantity',
        'is_full_item',
        'is_full_set_item', // Database column name (legacy)
        'full_set_id',
    ];

    protected $casts = [
        'is_full_item' => 'boolean',
        'is_full_set_item' => 'boolean', // Database column name (legacy)
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ─── Relationships ──────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class, 'room_item_id');
    }

    public function latestBorrow()
    {
        return $this->hasOne(Borrow::class, 'room_item_id')->latestOfMany();
    }

    public function fullSetSiblings()
    {
        // Check both column names for compatibility
        $isFullSet = $this->is_full_set_item ?? $this->is_full_item ?? false;
        if (!$isFullSet || !$this->full_set_id) {
            return collect();
        }

        return self::where('full_set_id', $this->full_set_id)
            ->where('id', '!=', $this->id)
            ->get();
    }

    // ─── Scopes ─────────────────────────────────────

    public function scopeUsable($query)
    {
        return $query->where('status', 'Usable');
    }

    public function scopeUnusable($query)
    {
        return $query->where('status', 'Unusable');
    }

    public function scopeByRoom($query, $room)
    {
        return $query->where('room_title', $room);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('device_category', $category);
    }

    public function scopeByDeviceType($query, $deviceType)
    {
        return $query->where('device_type', $deviceType);
    }

    public function scopeFullSetItems($query)
    {
        // Use the actual database column name
        return $query->where('is_full_set_item', true);
    }

    public function scopeSingleItems($query)
    {
        // Use the actual database column name
        return $query->where('is_full_set_item', false);
    }

    public function scopeByFullSetId($query, $fullSetId)
    {
        return $query->where('full_set_id', $fullSetId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForNewUsers($query)
    {
        return $query->whereNotNull('user_id');
    }

    public function scopeForOldUsers($query)
    {
        return $query->whereNull('user_id');
    }

    // ─── Accessors ──────────────────────────────────

    public function getPhotoUrlAttribute()
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : asset('images/no-image.png');
    }

    public function getIsUsableAttribute()
    {
        return $this->status === 'Usable';
    }

    public function getIsUnusableAttribute()
    {
        return $this->status === 'Unusable';
    }

    public function getFormattedBarcodeAttribute()
    {
        return $this->barcode;
    }

    public function getBarcodeNumberAttribute()
    {
        preg_match('/(\d+)$/', $this->barcode, $matches);
        return $matches[1] ?? null;
    }

    // ─── Helper Methods ─────────────────────────────

    public function getStatusBadgeClass()
    {
        return $this->status === 'Usable' ? 'badge-success' : 'badge-danger';
    }

    public function getDeviceTypeIcon()
    {
        $icons = [
            'Computer Unit' => 'fas fa-desktop',
            'Peripheral Device' => 'fas fa-mouse',
            'Uncategorized' => 'fas fa-question-circle',
        ];

        return $icons[$this->device_type] ?? 'fas fa-cube';
    }

    public function getCategoryIcon()
    {
        $icons = [
            'System Unit'    => 'fas fa-desktop',
            'Monitor'        => 'fas fa-tv',
            'Keyboard'       => 'fas fa-keyboard',
            'Mouse'          => 'fas fa-mouse',
            'Printer'        => 'fas fa-print',
            'Scanner'        => 'fas fa-scan',
            'Projector'      => 'fas fa-video',
            'Router'         => 'fas fa-wifi',
            'Switch'         => 'fas fa-network-wired',
            'UPS'            => 'fas fa-battery-half',
            'Speakers'       => 'fas fa-volume-up',
            'Webcam'         => 'fas fa-video',
            'Microphone'     => 'fas fa-microphone',
            'Headphones'     => 'fas fa-headphones',
            'Flash Drive'    => 'fas fa-usb',
            'Hard Drive'     => 'fas fa-hdd',
            'SSD'            => 'fas fa-save',
            'RAM'            => 'fas fa-memory',
            'GPU'            => 'fas fa-microchip',
            'Motherboard'    => 'fas fa-microchip',
            'PSU'            => 'fas fa-plug',
        ];

        return $icons[$this->device_category] ?? 'fas fa-cube';
    }

    // ─── Model Events ───────────────────────────────

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($item) {
            if ($item->photo) {
                Storage::disk('public')->delete($item->photo);
            }
        });
    }
}
