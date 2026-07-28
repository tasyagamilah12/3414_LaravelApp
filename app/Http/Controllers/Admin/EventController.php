<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Authorization Multi Tenant
     * Admin dapat mengakses semua event.
     * Organizer hanya dapat mengakses event miliknya sendiri.
     */
    private function authorizeEvent(Event $event)
    {
        if (Auth::user()->role == 'admin') {
            return;
        }

        abort_if($event->user_id != Auth::id(), 403);
    }

    /**
     * Menampilkan daftar event.
     */
    public function index()
    {
        if (Auth::user()->role == 'admin') {

            $events = Event::with(['category', 'user'])
                ->latest()
                ->paginate(10);

        } else {

            $events = Event::with(['category', 'user'])
                ->where('user_id', Auth::id())
                ->latest()
                ->paginate(10);

        }

        return view('admin.events.index', compact('events'));
    }

    /**
     * Form tambah event.
     */
    public function create()
    {
        $categories = Category::all();

        return view('admin.events.create', compact('categories'));
    }

    /**
     * Simpan event baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|numeric|min:1',
            'poster'      => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        // Simpan pemilik event
        $data['user_id'] = Auth::id();

        Event::create($data);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Data Event berhasil ditambahkan.');
    }

    /**
     * Form edit event.
     */
    public function edit(Event $event)
    {
        $this->authorizeEvent($event);

        $categories = Category::all();

        return view('admin.events.edit', compact('event', 'categories'));
    }

    /**
     * Update event.
     */
    public function update(Request $request, Event $event)
    {
        $this->authorizeEvent($event);

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|numeric|min:1',
            'poster'      => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('poster')) {

            if ($event->poster_path) {
                Storage::disk('public')->delete($event->poster_path);
            }

            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $event->update($data);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * Hapus event.
     */
    public function destroy(Event $event)
    {
        $this->authorizeEvent($event);

        if ($event->poster_path) {
            Storage::disk('public')->delete($event->poster_path);
        }

        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Data event berhasil dihapus.');
    }

    /**
     * Redirect jika route show dipanggil.
     */
    public function show(Event $event)
    {
        $this->authorizeEvent($event);

        return redirect()->route('admin.events.index');
    }
}