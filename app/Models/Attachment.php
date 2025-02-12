<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'url',
    ];

    public function claims()
    {
        return $this->belongsToMany(Claim::class, 'claim_attachment');
    }
}