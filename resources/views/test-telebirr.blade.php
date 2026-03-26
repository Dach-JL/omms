<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telebirr Payment Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            border-bottom: 3px solid #00A8E8;
            padding-bottom: 10px;
        }

        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #00A8E8;
            padding: 15px;
            margin: 20px 0;
        }

        .success {
            background: #d4edda;
            border-left: 4px solid #28a745;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #00A8E8;
            color: white;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #00A8E8;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 5px;
        }

        .btn:hover {
            background-color: #0089c4;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-pending {
            background: #ffc107;
            color: #000;
        }

        .status-approved {
            background: #28a745;
            color: #fff;
        }

        .status-rejected {
            background: #dc3545;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🏧 Telebirr Payment Integration - Test Page</h1>

        <div class="info-box">
            <strong>✅ Configuration Status:</strong><br>
            Telebirr Manual Payment system is configured and ready to test!
        </div>

        <h2>Configuration Details:</h2>
        <table>
            <tr>
                <th>Setting</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Pay Bill Number</td>
                <td>{{ config('telebirr_manual.pay_bill_number') }}</td>
            </tr>
            <tr>
                <td>Account Name</td>
                <td>{{ config('telebirr_manual.account_name') }}</td>
            </tr>
            <tr>
                <td>USSD Code</td>
                <td>{{ config('telebirr_manual.ussd_code') }}</td>
            </tr>
            <tr>
                <td>Max Upload Size</td>
                <td>{{ config('telebirr_manual.max_receipt_size') }} KB</td>
            </tr>
            <tr>
                <td>Allowed Formats</td>
                <td>{{ implode(', ', config('telebirr_manual.allowed_formats')) }}</td>
            </tr>
        </table>

        <h2>Available Routes:</h2>
        <table>
            <tr>
                <th>Type</th>
                <th>Route</th>
                <th>Description</th>
            </tr>
            <tr>
                <td>GET</td>
                <td>/payment/telebirr/{plan_id}</td>
                <td>Show payment form for a plan</td>
            </tr>
            <tr>
                <td>POST</td>
                <td>/payment/telebirr/submit</td>
                <td>Submit payment with receipt</td>
            </tr>
            <tr>
                <td>GET</td>
                <td>/payment/telebirr/success</td>
                <td>Payment success confirmation</td>
            </tr>
            <tr>
                <td>GET</td>
                <td>/my-telebirr-payments</td>
                <td>User payment history</td>
            </tr>
            <tr>
                <td>GET</td>
                <td>/admin/telebirr-verifications</td>
                <td>Admin verification dashboard</td>
            </tr>
        </table>

        <h2>Test Steps:</h2>
        <ol>
            <li><strong>Login Required:</strong> You must be logged in to test the payment features</li>
            <li>
                <strong>Check Database Status:</strong><br>
                Some migrations are still pending. Run: <code>php artisan migrate --force</code><br>
                <em>Note: If you get SQLite errors, you may need to skip certain migrations or use MySQL instead.</em>
            </li>
            <li>
                <strong>Test Payment Form (after fixing migrations):</strong><br>
                Visit: <code>http://localhost:8000/payment/telebirr/1</code><br>
                (Replace "1" with an actual plan ID from your database)
            </li>
            <li>
                <strong>Admin Verification:</strong><br>
                Visit: <code>http://localhost:8000/admin/telebirr-verifications</code><br>
                (Requires SuperAdmin role)
            </li>
        </ol>

        <div class="info-box {{ count($pendingMigrations) === 0 ? 'success' : '' }}">
            <strong>{{ count($pendingMigrations) === 0 ? '✅' : '⚠️' }} Database Status:</strong><br>
            @if(count($pendingMigrations) === 0)
            All migrations completed successfully. The payments table has all required Telebirr fields.
            @else
            <strong style="color: #dc3545;">{{ count($pendingMigrations) }} pending migration(s):</strong><br>
            <ul style="margin: 10px 0;">
                @foreach($pendingMigrations as $migration)
                <li>{{ $migration }}</li>
                @endforeach
            </ul>
            <p style="margin-top: 10px;"><strong>Action Required:</strong> Run <code>php artisan migrate --force</code> to complete setup.</p>
            <p><em>Note: If you encounter SQLite ENUM errors, you may need to comment out problematic migrations or switch to MySQL.</em></p>
            @endif
        </div>

        <h2>Quick Links:</h2>
        @auth
        <p><strong>✓ Logged in as:</strong> {{ auth()->user()->name }} ({{ auth()->user()->email }})</p>
        <p><strong>Role:</strong> {{ auth()->user()->role }}</p>

        @if(auth()->user()->role === 'SuperAdmin' || auth()->user()->role === 'organAdmin')
        <a href="/payment/telebirr/1" class="btn">Test Payment (Plan ID: 1)</a>
        @if(auth()->user()->role === 'SuperAdmin')
        <a href="/admin/telebirr-verifications" class="btn">Admin Dashboard</a>
        @endif
        @endif

        <a href="/home" class="btn">Go to Dashboard</a>
        @else
        <p style="color: #dc3545;"><strong>⚠️ Not Logged In</strong></p>
        <p>Please login first to test payment features:</p>
        <a href="/login" class="btn">Login</a>
        <a href="/register" class="btn">Register</a>
        @endauth

        <h2>Sample Payment Instructions:</h2>
        <div style="background: #f8f9fa; padding: 15px; border-radius: 4px; margin-top: 20px;">
            <h3>How to Make Telebirr Payment:</h3>
            <ol>
                <li>Dial <strong>*127#</strong> on your mobile phone</li>
                <li>Select "Pay Bill"</li>
                <li>Enter Pay Bill Number: <strong>600600</strong></li>
                <li>Enter Account Name: <strong>Your Organization</strong></li>
                <li>Enter Amount: [Plan Amount]</li>
                <li>Enter your PIN to confirm</li>
                <li>Save the transaction receipt/screenshot</li>
                <li>Upload the receipt on the payment form</li>
            </ol>
        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666;">
            <p><strong>Note:</strong> This is a test page for Telebirr manual payment integration. In production, update the Pay Bill Number and Account Name with your actual Telebirr merchant credentials.</p>
        </div>
    </div>
</body>

</html>