<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'subject', // Sujet
        'detailed_description', // Description détaillée
        'category', // Catégorie (e.g., Refund, Contract Issue, Billing Error, etc.)
        'status', // Statut (e.g., Open, In Progress, Resolved, Closed)
        'user_id', // ID of the user who created the claim
        'broker_id', // ID of the broker assigned to the claim (if applicable)
    ];

    /**
     * Get the user who created the claim.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the broker assigned to the claim.
     */
    public function broker()
    {
        return $this->belongsTo(User::class, 'broker_id');
    }
}