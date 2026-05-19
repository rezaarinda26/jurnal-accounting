<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pic;

class Journal extends Model
{
    protected $fillable = [
        'journal_number',
        'date',
        'pic_id',
        'pic_name',
        'description',
        'bundle_id',
    ];

    public function pic()
    {
        return $this->belongsTo(Pic::class);
    }

    public function entries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('dashboard_stats');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('dashboard_stats');
        });
    }
}
