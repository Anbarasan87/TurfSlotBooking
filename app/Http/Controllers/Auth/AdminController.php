<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Turf;
use App\Models\Booking;

class AdminController extends Controller
{
    public function manageBookings()
    {
        $data = Booking::all();
        $users = User::all();
        $turfs = Turf::all();
        return view('admin.bookings', compact('data','users','turfs'))->render();
    }
    public function manageTurfs()
{
    
    $data = Turf::all();
        return view('admin.turfs', compact('data'));
}

    public function manage(Request $request)
    {
        $resourceType = $request->query('type');

        switch ($resourceType) {
            case 'users':
                $userCount = User::where('role', 'user')->count(); 
                $ownerCount = User::where('role', 'owner')->count(); 
                $data = User::all();  
                return view('admin.users', compact('data', 'userCount', 'ownerCount'));
                
                $data = User::all();
                return view('admin.users', compact('data'));

            case 'turfs':
                $data = Turf::all();
                return view('admin.turfs', compact('data'));

            case 'bookings':
                $data = Booking::with(['user', 'turf'])->get();
                $users = User::all();
                $turfs = Turf::all();
                return view('admin.bookings', compact('data', 'users', 'turfs'));

            default:
                return redirect()->route('admin.dashboard')->with('error', 'Invalid resource type specified.');
        }
    }

    public function create($type)
{
    if ($type == 'turfs') {
        $owners = User::where('role', 'owner')->get(); 
    } else {
        $owners = [];
    }

    return view('admin.create', compact('type', 'owners'));
}


    public function store(Request $request, $type)
{
    $validationRules = [
        'users' => [
            'validation' => [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ],
            'create' => function($data) {
                return User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
                ]);
            }
        ],
        'turfs' => [
            'validation' => [
                'owner_name' => 'required|string|max:255',
                'owner_id' => 'required|exists:users,id',
                'name' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
                'sport_type' => 'required|string|max:50',
                'price_per_hour' => 'required|numeric',
                'availability' => 'required|in:Available,Unavailable',
            ],
            'create' => function($data) {
                $imagePath = null;
                if (request()->hasFile('image')) {
                    $imagePath = request()->file('image')->store('images/turfs', 'public');
                }

                return Turf::create([
                    'owner_name' => $data['owner_name'],
                    'owner_id' => $data['owner_id'],
                    'name' => $data['name'],
                    'location' => $data['location'],
                    'image' => $imagePath,
                    'sport_type' => $data['sport_type'],
                    'price_per_hour' => $data['price_per_hour'],
                    'availability' => $data['availability'],
                ]);
            }
        ],
        'bookings' => [
            'validation' => [
                'user_id' => 'required|exists:users,id',
                'turf_id' => 'required|exists:turfs,id',
                'booking_date' => 'required|date',
                'time_slot' => 'required|string',
                'status' => 'required|in:Pending,Confirmed,Cancelled',
                'total_price' => 'required|numeric',
            ],
            'create' => function($data) {
                return Booking::create([
                    'user_id' => $data['user_id'],
                    'turf_id' => $data['turf_id'],
                    'booking_date' => $data['booking_date'],
                    'time_slot' => $data['time_slot'],
                    'status' => $data['status'],
                    'total_price' => $data['total_price'],
                ]);
            }
        ]
    ];

    if (!array_key_exists($type, $validationRules)) {
        return redirect()->route('admin.dashboard')->with('error', 'Invalid resource type specified.');
    }

    $data = $request->validate($validationRules[$type]['validation']);
    $validationRules[$type]['create']($data);

    return redirect()->route('admin.manage', ['type' => $type])->with('success', ucfirst($type) . ' created successfully!');
}


    public function show($type, $id)
    {
        switch ($type) {
            case 'users':
                $data = User::findOrFail($id);
                break;

            case 'turfs':
                $data = Turf::findOrFail($id);
                break;

            case 'bookings':
                $data = Booking::findOrFail($id);
                break;

            default:
                return redirect()->route('admin.dashboard')->with('error', 'Invalid resource type specified.');
        }

        return view('admin.show', ['data' => $data, 'type' => $type]);
    }

    public function edit($id, Request $request)
    {
        $resourceType = $request->get('type');
        if (!$resourceType) {
            return redirect()->route('admin.dashboard')->with('error', 'Type parameter is missing!');
        }

        switch ($resourceType) {
            case 'users':
                $data = User::find($id);
                if (!$data) {
                    return redirect()->route('admin.manage', ['type' => 'users'])->with('error', 'User not found.');
                }
                return view('admin.user', compact('data'));

            case 'turfs':
                $data = Turf::find($id);
                if (!$data) {
                    return redirect()->route('admin.manage', ['type' => 'turfs'])->with('error', 'Turf not found.');
                }
                return view('admin.turf', compact('data'));

            case 'bookings':
                $data = Booking::find($id);
                if (!$data) {
                    return redirect()->route('admin.manage', ['type' => 'bookings'])->with('error', 'Booking not found.');
                }
                return view('admin.booking', compact('data'));

            default:
                return redirect()->route('admin.dashboard')->with('error', 'Invalid resource type specified.');
        }
    }

    public function update(Request $request, $type, $id)
    {
        switch ($type) {
            case 'users':
                $data = User::findOrFail($id);
                $data->name = $request->input('name');
                $data->email = $request->input('email');
                break;

            case 'turfs':
                $data = Turf::findOrFail($id);
                $data->name = $request->input('name');
                $data->location = $request->input('location');
                break;

            case 'bookings':
                $data = Booking::findOrFail($id);
                $data->user_id = $request->input('user_id');
                $data->turf_id = $request->input('turf_id');
                $data->booking_date = $request->input('booking_date');
                $data->time_slot = $request->input('time_slot');
                $data->total_price = $request->input('total_price');
                break;

            default:
                return redirect()->route('admin.dashboard')->with('error', 'Invalid resource type specified.');
        }

        $data->save();
        return redirect()->route('admin.manage', ['type' => $type])->with('success', ucfirst($type) . ' updated successfully.');
    }

    public function destroy($type, $id)
    {
        switch ($type) {
            case 'users':
                $user = User::findOrFail($id);
                $user->delete();
                return redirect()->route('admin.manage', ['type' => 'users'])->with('success', 'User deleted successfully.');

            case 'turfs':
                $turf = Turf::findOrFail($id);
                $turf->delete();
                return redirect()->route('admin.manage', ['type' => 'turfs'])->with('success', 'Turf deleted successfully.');

            case 'bookings':
                $booking = Booking::findOrFail($id);
                $booking->delete();
                return redirect()->route('admin.manage', ['type' => 'bookings'])->with('success', 'Booking deleted successfully.');

            default:
                return redirect()->route('admin.dashboard')->with('error', 'Invalid resource type specified.');
        }
    }

    public function filterUsers(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->filled('location')) {
            $query->where('location', 'LIKE', '%' . $request->input('location') . '%');
        }

        $data = $query->get();
        return view('admin.users', compact('data'));
    }

    public function searchUsers(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('email', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('role', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $data = $query->get();
        return view('admin.users', compact('data'));
    }    
}
