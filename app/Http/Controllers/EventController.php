<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show(\App\Models\Event $event)
    {
        $categories = \App\Models\Category::all();

        return view('event-detail', compact('categories', 'event'));
    }
}