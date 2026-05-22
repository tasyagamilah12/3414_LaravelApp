<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Partner;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua kategori
        $categories = Category::all();

        // Ambil semua partner
        $partners = Partner::all();

        // Query event
        $query = Event::with('category')
            ->where('date', '>=', now())
            ->orderBy('date', 'asc');

        // Filter kategori
        if ($request->has('category') && $request->category != '') {

            $query->whereHas('category', function ($q) use ($request) {

                $q->where('slug', $request->category);

            });

        }

        // Ambil data event
        $events = $query->get();

        // Kirim ke view
        return view('welcome', compact(
            'events',
            'categories',
            'partners'
        ));
    }
}