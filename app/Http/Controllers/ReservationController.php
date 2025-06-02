<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Invite;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Display a listing of all reservations with related user and event.
     */
    public function index()
    {
        return Reservation::with('utilisateur', 'event')->get();
    }

    /**
     * Store a new reservation for the authenticated user.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'preferred_date' => 'required|date',
            'preferred_time' => 'required',
            'special_note' => 'nullable|string',
        ]);

        $reservation = Reservation::create([
            'user_id' => $user->id,
            'event_id' => $validated['event_id'],
            'preferred_date' => $validated['preferred_date'],
            'preferred_time' => $validated['preferred_time'],
            'special_note' => $validated['special_note'] ?? null,
        ]);

        // Create an invite linked to the reservation
        $invite = Invite::create([
            'reservation_id' => $reservation->id,
            'token' => Str::uuid(),
        ]);

        return response()->json([
            'reservation' => $reservation->load('utilisateur', 'event'),
            'invite_link' => url('/invite/' . $invite->token),
        ], 201);
    }

    /**
     * Get reservations for the currently authenticated user.
     */
public function myReservations()
{
    $user = Auth::user();
    return Reservation::with(['event', 'invite'])
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();
}


public function show($id)
{
    return Reservation::with(['utilisateur', 'event', 'invite', 'guests'])
        ->findOrFail($id);
}


    /**
     * Get reservations for a specific user by user ID.
     * Optional: Useful for admins or for user profile views.
     */
    public function userReservations($utilisateurId)
    {
        return Reservation::with('event', 'invite')
            ->where('user_id', $utilisateurId)
            ->get();
    }

    /**
     * Get reservations for a specific event.
     */
    public function getEventReservations($eventId)
    {
        return Reservation::with(['utilisateur', 'invite'])
            ->where('event_id', $eventId)
            ->get();
    }
    
}
