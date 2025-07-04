<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_service');
    }
}
