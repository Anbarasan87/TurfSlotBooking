<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;  
use App\Models\User;
use App\Models\Turf;
use App\Models\Booking;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $query = Turf::query();

        if ($request->has('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->has('sport_type')) {
            $query->where('sport_type', $request->sport_type);
        }

        if ($request->has('date')) {
            $query->where('availability', 'Available');
        }

        $turfs = $query->get();

        return response()->json(['turfs' => $turfs]);
    }

    public function bookings()
    {
        $bookings = auth()->user()->bookings()->with('turf')->get();
        return view('users.bookings', compact('bookings'));
    }
    public function bookTurf(Request $request)
    {
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'turf_id' => $request->input('turf_id'), 
            'booking_date' => $request->input('date'),
            'time_slot' => $request->input('time_slot'),
            'status' => 'pending',
            'total_price' => $request->input('total_price'),
        ]);

        return response()->json(['total_price' => $booking->total_price]);
    }

    public function confirmPayment(Request $request)
    {
        $booking = Booking::find($request->input('booking_id'));

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $booking->status = 'confirmed';
        $booking->save();

        return response()->json([
            'booking_date' => $booking->booking_date,
            'time_slot' => $booking->time_slot,
        ]);
    }
    public function subscribe(Request $request)
{
    $request->validate(['email' => 'required|email']);
    return back()->with('success', 'Subscription successful!');
}
public function cancel($id)
{
    $booking = Booking::findOrFail($id);

    if ($booking->status !== 'Cancelled') {
        $booking->status = 'Cancelled'; 
        $booking->save();
    }

    return redirect()->route('dashboard.user', ['view' => 'bookings'])->with('success', 'Booking has been cancelled.');
}
}
