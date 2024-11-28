<?php

namespace App\Http\Controllers\Auth;
use App\Models\User;
use App\Models\Turf;
use App\Models\Booking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TurfController extends Controller
{
    public function index()
    {
        $turfs = Turf::where('owner_id', auth()->id())->get();
        return view('dashboard.owner', compact('turfs'));
    }

    public function create()
    {
        if (Turf::where('owner_id', auth()->id())->exists()) {
            return redirect()->route('dashboard.owner')->with('error', 'You can only add one turf. Please edit the existing one.');
        }
        
        return view('turfs.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'sport_type' => 'required|string',
            'price_per_hour' => 'required|numeric',
            'availability' => 'required|array',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $turf = new Turf();
        $turf->name = $request->name;
        $turf->location = $request->location;
        $turf->sport_type = $request->sport_type;
        $turf->price_per_hour = $request->price_per_hour;
        $turf->availability = json_encode($request->availability);

        if ($request->hasFile('image')) {
            $turf->image = $request->file('image')->store('images', 'public');
        }

        $turf->owner_id = auth()->id();
        $turf->save();

        return redirect()->route('dashboard.owner')->with('success', 'Turf created successfully.');
    }

    public function edit(Turf $turf)
    {
        return view('turfs.edit', compact('turfs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'sport_type' => 'required|string',
            'price_per_hour' => 'required|numeric',
            'availability' => 'required|json',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $turf = Turf::findOrFail($id);
        $turf->name = $request->name;
        $turf->location = $request->location;
        $turf->sport_type = $request->sport_type;
        $turf->price_per_hour = $request->price_per_hour;
        $turf->availability = $request->availability;

        if ($request->hasFile('image')) {
            $turf->image = $request->file('image')->store('images', 'public');
        }

        $turf->save();

        return redirect()->route('dashboard.owner')->with('success', 'Turf updated successfully.');
    }
}
