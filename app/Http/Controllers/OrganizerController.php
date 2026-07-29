<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class OrganizerController extends Controller
{
    /**
     * Menampilkan Halaman Profil Publik Penyelenggara
     */
    public function show($id)
    {
        // Cari user dengan role organizer atau admin
        $organizer = User::whereIn('role', ['organizer', 'admin'])
            ->findOrFail($id);

        // Ambil daftar event yang dibuat oleh organizer ini
        $events = $organizer->events()
            ->latest()
            ->paginate(6);

        // Ambil ulasan publik beserta detail pembeli & event
        $reviews = $organizer->organizerReviews()
            ->with(['user', 'event'])
            ->latest()
            ->paginate(5);

        // Hitung statistik rating
        $totalReviews = $organizer->organizerReviews()->count();
        $averageRating = $organizer->averageRating();

        return view('organizers.show', compact(
            'organizer',
            'events',
            'reviews',
            'totalReviews',
            'averageRating'
        ));
    }
}