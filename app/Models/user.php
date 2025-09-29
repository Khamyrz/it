<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user extends Model
{
    protected $fillable = ['name', 'email', 'password', 'photo', 'mobile', 'is_approved', 'is_new_user'];

    public function roomItems()
    {
        return $this->hasMany(RoomItem::class);
    }
}
