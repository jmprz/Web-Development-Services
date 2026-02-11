<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $fillable = ['title', 'detail', 'deadline', 'status'];

    public function getDaysRemainingAttribute()
    {
        // This calculates the difference between today and the deadline
        return now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($this->deadline)->startOfDay(), false);
    }
}
