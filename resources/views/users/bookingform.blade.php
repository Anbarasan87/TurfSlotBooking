<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turf Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .booking-form {
            background-color: #0e1a35;
            padding: 30px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
        }
        .booking-form h3 {
            color: #ffffff;
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
        }
        .booking-form label {
            color: #ffffff;
            font-size: 16px;
            margin-bottom: 10px;
            display: block;
        }
        .booking-form input[type="date"],
        .booking-form select,
        .booking-form button {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease-in-out;
        }
        .booking-form input[type="date"],
        .booking-form select {
            background-color: #f9f9f9;
        }
        .booking-form input[type="date"]:focus,
        .booking-form select:focus {
            border: 2px solid #ffffff;
            background-color: #f1f1f1;
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
        }
        .booking-form button {
            background-color: #ffffff;
            color: #0e1a35;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        .booking-form button:hover {
            background-color: #f1f1f1;
        }
        .total-price {
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border-radius: 10px;
            width: 30%;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .modal-content p {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .modal-content button {
            padding: 10px 20px;
            background-color: #0e1a35;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .modal-content button:hover {
            background-color: #0c1530;
        }
    </style>
</head>
<body>
    <form action="{{ route('dashboard.user.book', ['turf_id' => $turf->id]) }}" method="POST" class="booking-form" id="bookingForm">
        @csrf
        <input type="hidden" name="turf_id" value="{{ $turf->id }}">

        <h3>Booking for {{ $turf->name }}</h3>

        <label>Date:
            <input type="date" name="booking_date" id="booking_date" required>
        </label>

        <label>Time Slot:
            <select name="time_slot" id="time_slot" required>
                <option value=""selected disabled hidden>Select  a time slot</option>
                <option value="08:00 - 09:00">08:00 - 09:00</option>
                <option value="09:00 - 10:00">09:00 - 10:00</option>
                <option value="10:00 - 11:00">10:00 - 11:00</option>
                <option value="11:00 - 12:00">11:00 - 12:00</option>
                <option value="12:00 - 13:00">12:00 - 13:00</option>
                <option value="13:00 - 14:00">13:00 - 14:00</option>
                <option value="14:00 - 15:00">14:00 - 15:00</option>
                <option value="15:00 - 16:00">15:00 - 16:00</option>
                <option value="16:00 - 17:00">16:00 - 17:00</option>
            </select>
        </label>

        <div class="total-price">
            <p>Total Price: ₹<span id="total_price">0.00</span></p>
            <input type="hidden" name="price_per_hour" value="{{ $turf->price_per_hour }}">
            <input type="hidden" name="total_price" id="hidden_total_price">
        </div>

        <button type="submit" id="submitBtn">Confirm and Proceed to Payment</button>
    </form>

    <div id="alertModal" class="modal">
        <div class="modal-content">
            <p id="modalMessage"></p>
            <button onclick="closeModal()">OK</button>
        </div>
    </div>

    <script>
        const pricePerHour = {{ $turf->price_per_hour }};
        const totalPriceElement = document.getElementById('total_price');
        const hiddenTotalPriceInput = document.getElementById('hidden_total_price');
        const modal = document.getElementById('alertModal');
        const modalMessage = document.getElementById('modalMessage');

        const bookings = @json($turfBookings);

        function showModal(message) {
            modalMessage.textContent = message;
            modal.style.display = 'block';
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        function calculateTotalPrice() {
            const timeSlot = document.getElementById('time_slot').value;

            if (timeSlot) {
                const totalPrice = pricePerHour; 
                totalPriceElement.textContent = totalPrice.toFixed(2);
                hiddenTotalPriceInput.value = totalPrice.toFixed(2);
            } else {
                totalPriceElement.textContent = '0.00';
                hiddenTotalPriceInput.value = '';
            }
        }

        function isTimeSlotAvailable() {
            const bookingDate = document.getElementById('booking_date').value;
            const timeSlot = document.getElementById('time_slot').value;

            if (!bookingDate || !timeSlot) {
                showModal('Please select a date and time slot.');
                return false;
            }

            const isBooked = bookings.some(booking => {
                return booking.booking_date === bookingDate && booking.time_slot === timeSlot;
            });

            if (isBooked) {
                showModal('The selected time slot is already booked. Please choose another time.');
                return false;
            }

            return true;
        }

        document.getElementById('bookingForm').onsubmit = function(event) {
            if (!isTimeSlotAvailable()) {
                event.preventDefault();
            }
        };

        document.getElementById('time_slot').addEventListener('change', calculateTotalPrice);
    </script>
</body>
</html>
