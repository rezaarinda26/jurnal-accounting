<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pic;

class Payable extends Model
{
    protected $fillable = [
        'invoice_number',
        'pic_id',
        'description',
        'amount',
        'due_date',
        'status',
        'journal_id',
    ];

    public function pic()
    {
        return $this->belongsTo(Pic::class);
    }

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }
}
