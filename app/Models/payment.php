<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class payment extends Model
{

     use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'name',
        'organ_name',
        'amount',
        'payment_method',
        //'transaction_id',
        'status',
        'billing',
        // Telebirr manual payment fields
        'receipt_image',
        'telebirr_transaction_ref',
        'payer_phone_number',
        'payment_notes',
        'verification_requested_at',
        'verified_at',
        'verified_by',
        'verification_status',
        'rejection_reason',
    ];






    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'verification_requested_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the admin who verified this payment
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
