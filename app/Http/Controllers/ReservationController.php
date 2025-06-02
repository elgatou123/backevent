<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Invite;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index() {
        return Reservation::with('utilisateur', 'event')->get();
    }

    public function store(Request $request) {
    $user = Auth::user(); // Correction: utiliser user() au lieu de utilisateur()
    
    $validated = $request->validate([
        'event_id' => 'required|exists:events,id',
        'preferred_date' => 'required|date',
        'preferred_time' => 'required',
        'special_note' => 'nullable|string'
    ]);

    $reservation = Reservation::create([
        'user_id' => $user->id,
        'event_id' => $validated['event_id'],
        'preferred_date' => $validated['preferred_date'],
        'preferred_time' => $validated['preferred_time'],
        'special_note' => $validated['special_note'] ?? null
    ]);

    // Création de l'invitation
    $invite = Invite::create([
        'reservation_id' => $reservation->id,
        'token' => Str::uuid(),
    ]);

    return response()->json([
        'reservation' => $reservation->load('utilisateur', 'event'),
        'invite_link' => url('/invite/' . $invite->token),
    ], 201);
}

public function myReservations() {
    $user = Auth::user(); // Correction: utiliser user() au lieu de utilisateur()
    return Reservation::with('event', 'invite')
        ->where('user_id', $user->id)
        ->get();
}

public function getEventReservations($eventId) {
        return Reservation::with(['utilisateur', 'invite'])
            ->where('event_id', $eventId)
            ->get();
    }
}