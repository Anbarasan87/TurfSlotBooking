<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Turf;
use App\Models\Booking;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $role = Auth::user()->role;

            if ($role === 'admin') {
                return $this->admin();
            } elseif ($role === 'user') {
                return $this->user();
            } elseif ($role === 'owner') {
                return $this->owner();
            }
        } else {
            return redirect()->route('login')->with('error', 'Please login to access the dashboard.');
        }

        abort(403, 'Unauthorized');
    }

    public function user(Request $request)
{
    $allTurfs = Turf::where('availability', 'Available')->get();
    $bookings = auth()->user()->bookings()->with('turf')->get();

    // Check if turf_id is passed in the request
    if ($request->has('turf_id')) {
        $turf = Turf::findOrFail($request->query('turf_id')); // Get the turf by ID

        // Correctly use the $turf->id to fetch bookings
        $turfBookings = Booking::where('turf_id', $turf->id)->get(['booking_date', 'time_slot']); 
        
        return view('users.bookingform', compact('turf', 'turfBookings'));
    }

    // Render different views based on the 'view' query parameter
    if ($request->query('view') === 'bookings') {
        return view('users.booking', compact('bookings'));
    }

    if ($request->query('view') === 'profile') {
        return view('users.profile', ['user' => auth()->user()]);
    }

    // Default: render the dashboard
    return view('dashboard.user', compact('bookings', 'allTurfs'));
}

public function storeBooking(Request $request)
{
 
    $request->validate([
            'turf_id' => 'required|exists:turfs,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|string',
            'price_per_hour' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
        ]);

        [$start_time, $end_time] = explode('-', $request->time_slot);

        if (strtotime($end_time) <= strtotime($start_time)) {
            return back()->withErrors(['time_slot' => 'End time must be later than start time.'])->withInput();
        }

        $booking = new Booking();
        $booking->user_id = auth()->id(); 
        $booking->turf_id = $request->turf_id;
        $booking->booking_date = $request->booking_date;
        $booking->time_slot = $request->time_slot;
        $booking->status = 'Pending'; 
        $booking->total_price = $request->total_price;
        $booking->save();

        return redirect()->route('user.paymentgateway', ['booking_id' => $booking->id])->with('success', 'Booking successfully created. Proceed to payment.'); 
    }

public function showPaymentGateway($booking_id)
    {
        $booking = Booking::findOrFail($booking_id);
        return view('users.paymentgateway', compact('booking'));
    }

    public function processPayment(Request $request, $booking_id)
    {
        $booking = Booking::findOrFail($booking_id);

        if ($request->payment_method == 'stripe') {
        } elseif ($request->payment_method == 'razorpay') {
        }
        $booking->status = 'Confirmed';
        $booking->save();
        session()->flash('payment_success', 'Payment Successful!');
        return redirect()->route('dashboard.user')->with('success', 'Payment successful and booking confirmed!');
    }
   
    
    public function showTurfs()
        {
        $turfs = Turf::where('owner_id', auth()->id())->get();
        return view('owner.turfs', compact('turfs'));
    }
    public function cancelBooking(Request $request, $id)
{
    $booking = Booking::findOrFail($id);

    $currentTime = Carbon::now();
    $bookingStartTime = Carbon::parse($booking->booking_date . ' ' . $booking->time_slot);

    if ($currentTime->gte($bookingStartTime)) {
        return redirect()->back()->with('error', 'You cannot cancel a booking after the start time.');
    }

    $booking->update(['status' => 'Cancelled']);

    return redirect()->back()->with('success', 'Booking successfully cancelled.');
}


    public function showBookings()
    {
        $ownerId = auth()->user()->id;
        $bookings = Booking::whereHas('turf', function ($query) use ($ownerId) {
            $query->where('owner_id', $ownerId);
        })->with(['user', 'turf'])->get();

        $users = User::all();
        $turfs = Turf::all();

        return view('owner.bookings', compact('bookings', 'users', 'turfs'));
    }

    public function admin(Request $request = null)
{
    if ($request && $request->has('download')) {
        return $this->handleDownloadRequest($request);
    }

    $userCount = User::count();
    $turfCount = Turf::count();
    $bookingCount = Booking::count();

    \Log::info("User Count: $userCount, Turf Count: $turfCount, Booking Count: $bookingCount");

    return view('admin.dashboard', compact('userCount', 'turfCount', 'bookingCount'));
}
public function updateBooking(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'turf_id' => 'required|exists:turfs,id',
            'booking_date' => 'required|date',
            'time_slot' => 'required|string',
            'total_price' => 'required|numeric',
        ]);

        $booking = Booking::findOrFail($id);

        $booking->user_id = $request->user_id;
        $booking->turf_id = $request->turf_id;
        $booking->booking_date = $request->booking_date;
        $booking->time_slot = $request->time_slot;
        $booking->total_price = $request->total_price;

        $booking->save();

        return redirect()->route('owner.bookings')->with('success', 'Booking updated successfully.');
    }
    public function updateTurf(Request $request, $id)
    {
        $validatedData = $request->validate([
            'owner_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'sport_type' => 'required|string|max:50',
            'price_per_hour' => 'required|numeric|min:0',
            'availability' => 'required|string|in:Available,Unavailable',
        ]);

        $turf = Turf::findOrFail($id);

        $turf->update($validatedData);

        return redirect()->route('owner.turfs')->with('success', 'Turf updated successfully!');
    }

    protected function handleDownloadRequest(Request $request)
    {
        $type = $request->query('type', 'all');
    
        switch ($type) {
            case 'turf':
                $turfs = Turf::all();
                return $this->generateATurfDownload($turfs);
    
            case 'booking':
                $bookings = Booking::all();
                return $this->generateABookingDownload($bookings);
    
            case 'user':
                $users = User::all();
                return $this->generateAUserDownload($users);
    
            case 'all':
            default:
                $turfs = Turf::all();
                $bookings = Booking::all();
                $users = User::all();
                return $this->generateAAllDownload($users, $turfs, $bookings);
        }
    }

    public function generateATurfDownload($turfs)
    {
        $csvData = "ID, Name, Location, Sport Type, Price Per Hour, Availability\n";
        foreach ($turfs as $turf) {
            $csvData .= "{$turf->id}, {$turf->name}, {$turf->location}, {$turf->sport_type}, {$turf->price_per_hour}, {$turf->availability}\n";
        }
    
        return response($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="turfs.csv"',
        ]);
    }
    
    public function generateABookingDownload($bookings)
    {
        $csvData = "ID, Turf ID, Booking Date, Time Slot, Status, Total Price\n";
        foreach ($bookings as $booking) {
            $csvData .= "{$booking->id}, {$booking->turf_id}, {$booking->booking_date}, {$booking->time_slot}, {$booking->status}, {$booking->total_price}\n";
        }
    
        return response($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="bookings.csv"',
        ]);
    }
    
    public function generateAUserDownload($users)
    {
        $csvData = "ID, Name, Email\n";
        foreach ($users as $user) {
            $csvData .= "{$user->id}, {$user->name}, {$user->email}\n";
        }
    
        return response($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users.csv"',
        ]);
    }
    
    public function generateAAllDownload($users, $turfs, $bookings)
    {
        $csvData = "User ID, User Name, User Email, Turf ID, Turf Name, Turf Location, Sport Type, Price Per Hour, Availability, Booking ID, Booking Date, Time Slot, Status, Total Price\n";
    
        foreach ($users as $user) {
            foreach ($turfs as $turf) {
                foreach ($bookings as $booking) {
                    $csvData .= "{$user->id}, {$user->name}, {$user->email}, {$turf->id}, {$turf->name}, {$turf->location}, {$turf->sport_type}, {$turf->price_per_hour}, {$turf->availability}, {$booking->id}, {$booking->booking_date}, {$booking->time_slot}, {$booking->status}, {$booking->total_price}\n";
                }
            }
        }
    
        return response($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="all_data.csv"',
        ]);
    }
    

    public function manageUsers()
    {
        $users = User::all();
        return view('admin.users', compact('users'))->render();
    }

    public function manageTurfs()
    {
        $turfs = Turf::all();
        return view('admin.turfs', compact('turfs'))->render();
    }

    
  
    public function owner()
    {
        $ownerId = Auth::id();
        $turfCount = Turf::where('owner_id', $ownerId)->count();
        $bookingCount = Booking::whereHas('turf', function ($query) use ($ownerId) {
            $query->where('owner_id', $ownerId);
        })->count();

        return view('dashboard.owner', compact('turfCount', 'bookingCount'));
    }

    public function downloadData(Request $request)
    {
        $ownerId = Auth::id();

        $type = $request->query('type', 'all');

        switch ($type) {
            case 'turf':
                $turfs = Turf::where('owner_id', $ownerId)->get();
                return $this->generateTurfDownload($turfs);

            case 'booking':
                $bookings = Booking::whereHas('turf', function ($query) use ($ownerId) {
                    $query->where('owner_id', $ownerId);
                })->get();
                return $this->generateBookingDownload($bookings);

            case 'all':
            default:

                $turfs = Turf::where('owner_id', $ownerId)->get();
                $bookings = Booking::whereHas('turf', function ($query) use ($ownerId) {
                    $query->where('owner_id', $ownerId);
                })->get();
                return $this->generateAllDownload($turfs, $bookings);
        }
    }

    protected function generateTurfDownload($turfs)
    {
        $csvData = "ID,Name,Location,Sport Type,Price Per Hour,Availability\n";

        foreach ($turfs as $turf) {
            $csvData .= "{$turf->id},{$turf->name},{$turf->location},{$turf->sport_type},{$turf->price_per_hour},{$turf->availability}\n";
        }

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="turfs.csv"',
        ]);
    }

    protected function generateBookingDownload($bookings)
    {
        $csvData = "ID,User ID,Turf ID,Booking Date,Time Slot,Status,Total Price\n";

        foreach ($bookings as $booking) {
            $csvData .= "{$booking->id},{$booking->user_id},{$booking->turf_id},{$booking->booking_date},{$booking->time_slot},{$booking->status},{$booking->total_price}\n";
        }

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="bookings.csv"',
        ]);
    }
    protected function generateAllDownload($turfs, $bookings)
    {
        $csvData = "Turf Data:\nID,Name,Location,Sport Type,Price Per Hour,Availability\n";

        foreach ($turfs as $turf) {
            $csvData .= "{$turf->id},{$turf->name},{$turf->location},{$turf->sport_type},{$turf->price_per_hour},{$turf->availability}\n";
        }

        $csvData .= "\nBooking Data:\nID,User ID,Turf ID,Booking Date,Time Slot,Status,Total Price\n";

        foreach ($bookings as $booking) {
            $csvData .= "{$booking->id},{$booking->user_id},{$booking->turf_id},{$booking->booking_date},{$booking->time_slot},{$booking->status},{$booking->total_price}\n";
        }

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="turfs_and_bookings.csv"',
        ]);
    }
       
}
    
