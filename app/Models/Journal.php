<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $fillable = ['journal_number', 'date', 'pic_name', 'description', 'bundle_id'];

    public function entries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }
}
