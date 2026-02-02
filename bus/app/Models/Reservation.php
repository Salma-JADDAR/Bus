<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_reservation',
        'statut',
        'seat_number',
        'user_id',
        'segment_id'
    ];

    protected $casts = [
        'date_reservation' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function segment()
    {
        return $this->belongsTo(Segment::class);
    }

    public function programme()
    {
        return $this->hasOneThrough(Programme::class, Segment::class);
    }
}