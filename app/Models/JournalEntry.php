<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $fillable = ['journal_id', 'account_id', 'description', 'amount', 'is_debit'];

    protected $casts = [
        'is_debit' => 'boolean',
    ];

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
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
