<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Telebirr Manual Payment Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file contains settings for the Telebirr manual
    | payment integration where users upload receipts for verification.
    |
    */

    // Telebirr Pay Bill Information
    'pay_bill_number' => env('TELEBIRR_PAYBILL', '600600'),
    'account_name' => env('TELEBIRR_ACCOUNT_NAME', 'Your Organization Name'),

    // Payment Instructions
    'ussd_code' => '*127#',
    'instructions' => [
        'Dial *127# on your mobile phone',
        'Select "Pay Bill"',
        'Enter Pay Bill Number: 600600',
        'Enter Account Name: Your Organization Name',
        'Enter Amount: [Plan Amount]',
        'Enter your PIN to confirm',
        'Save the transaction receipt/screenshot',
    ],

    // Upload Settings
    'max_receipt_size' => 2048, // KB (2MB)
    'allowed_formats' => ['jpg', 'jpeg', 'png', 'pdf'],
    'upload_path' => 'receipts/telebirr/',
    'storage_disk' => 'public',

    // Verification Settings
    'verification_required_roles' => ['organAdmin', 'member'],
    'auto_approve_threshold' => null, // Set amount in ETB for auto-approval if needed

    // Notification Settings
    'notify_admin_on_submission' => true,
    'notify_user_on_approval' => true,
    'notify_user_on_rejection' => true,

    // Admin email for notifications
    'admin_email' => env('TELEBIRR_ADMIN_EMAIL', env('MAIL_FROM_ADDRESS', 'admin@example.com')),
];
