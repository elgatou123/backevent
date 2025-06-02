<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'preferred_date',
        'preferred_time',
        'special_note',
        'user_id',
        'event_id'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function invite()
    {
        return $this->hasOne(Invite::class);
    }
}