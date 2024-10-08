<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventImage;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $events = Event::with('images')->paginate($perPage);

        return response()->json($events, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $event = Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '-' . $image->getClientOriginalName();
                $path = $image->storeAs('images', $imageName, 'public');

                EventImage::create([
                    'event_id' => $event->id,
                    'image_path' => $path,
                ]);
            }
        }

        return response()->json([
            'message' => 'Evento criado com sucesso!',
            'event' => $event->load('images'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'image' => 'nullable|string',
        ]);

        $event = Event::findOrFail($id);

        $event->name = $request->name;
        $event->description = $request->description;
        $event->date = $request->date;

        if ($request->has('image')) {
            $event->image_path = $request->image;
        }

        $event->save();

        return response()->json([
            'message' => 'Evento atualizado com sucesso',
            'data' => $event,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        $event->delete();

        return response()->json([
            'message' => 'Evento deletado com sucesso',
        ]);
    }
}
