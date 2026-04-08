<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    use HasFactory;

    protected $fillable = ['bundle_number', 'status', 'type', 'closed_at'];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    public function journals()
    {
        return $this->hasMany(Journal::class);
    }
}
