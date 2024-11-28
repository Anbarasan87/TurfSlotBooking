<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment for Booking</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 15px;
        }
        .card {
            padding: 30px;
            background-color: #0e1a35;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            border: 1px solid #ddd;
        }
        .card-title {
            color: #ffffff;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .divider {
            background-color: #ffffff;
            height: 2px;
            margin: 20px 0;
        }
        .details p {
            color: #ffffff;
            font-size: 16px;
            margin: 8px 0;
        }
        .input-field label {
            color: #ffffff;
            font-size: 16px;
            font-weight: bold;
        }
        select {
            font-size: 16px;
            padding: 10px;
            color: #0e1a35;
            background-color: #ffffff;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #ffffff;
            color: #0e1a35;
            width: 100%;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            border: 2px solid #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease-in-out;
        }
        button:hover {
            background-color: #e0e0e0;
            color: #0e1a35;
        }
        .footer-note {
            margin-top: 20px;
            text-align: center;
            color: #ffffff;
            font-size: 14px;
        }
        @media screen and (max-width: 768px) {
            .card {
                padding: 20px;
            }
            .card-title {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Payment for Booking</span>

                <div class="divider"></div>

                <div class="details">
                    <p><strong>Turf Name:</strong> {{ $booking->turf->name }}</p>
                    <p><strong>Booking Date:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('d-m-Y') }}</p>
                    <p><strong>Time Slot:</strong> {{ $booking->time_slot }}</p>
                    <p><strong>Total Price:</strong> ₹{{ $booking->total_price }}</p>
                </div>

                <div class="divider"></div>

                <form action="{{ route('payment.process', ['booking_id' => $booking->id]) }}" method="POST">
                    @csrf
                    <div class="input-field">
                        <select id="payment_method" name="payment_method" required>
                            <option value="" disabled selected>Choose Payment Method</option>
                            <option value="stripe">Stripe</option>
                            <option value="razorpay">Razorpay</option>
                        </select>
                    </div>

                    <button type="submit">Proceed to Payment</button>
                </form>

                <div class="footer-note">
                    <p>Your payment is secure and encrypted.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const elems = document.querySelectorAll('select');
            M.FormSelect.init(elems, {
                dropdownOptions: {
                    constrainWidth: false 
                }
            });
        });
    </script>
</body>
</html>
