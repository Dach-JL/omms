# Telebirr Integration - Browser Testing Guide

## 🌐 Server Status

**Server:** Running at http://127.0.0.1:8000  
**Branch:** dach (pushed to GitHub)

---

## ✅ What's Ready to Test

### 1. Configuration Verification

-   ✅ Configuration file created: `config/telebirr_manual.php`
-   ✅ Environment variables added to `.env`
-   ✅ All routes registered successfully
-   ✅ Database migration for Telebirr fields completed

### 2. Available Routes

#### User Routes (requires login):

```
GET  /payment/telebirr/{plan_id}          - Show payment form
POST /payment/telebirr/submit             - Submit payment with receipt
GET  /payment/telebirr/success            - Success confirmation
GET  /my-telebirr-payments                - User payment history
GET  /telebirr-receipt/{paymentId}/download - Download receipt
```

#### Admin Routes (requires SuperAdmin role):

```
GET  /admin/telebirr-verifications        - Pending payments dashboard
GET  /admin/telebirr-verify/{id}          - Payment detail view
POST /admin/telebirr-approve/{id}         - Approve payment
POST /admin/telebirr-reject/{id}          - Reject payment
GET  /admin/telebirr-history              - Approved payments history
GET  /admin/telebirr-rejected             - Rejected payments
GET  /admin/telebirr-search               - Search payments
```

---

## 🧪 How to Test

### Step 1: Access Test Page

**URL:** http://localhost:8000/test-telebirr

This page shows:

-   ✅ Configuration status
-   ✅ All available routes
-   ✅ Database migration status
-   ✅ Quick links based on your role
-   ⚠️ Pending migrations warning

### Step 2: Check Migration Status

The test page will show pending migrations. You have two options:

#### Option A: Run Remaining Migrations (Recommended for MySQL/MariaDB)

```bash
php artisan migrate --force
```

#### Option B: Skip Problematic Migrations (For SQLite)

Some migrations use ENUM which SQLite doesn't support. You can:

1. Comment out the problematic migration in the sequence
2. Or switch to MySQL for testing
3. Or manually create only the essential tables

**Essential migrations for Telebirr:**

-   ✅ Already done: `2026_03_25_233711_add_telebirr_manual_fields_to_payments_table.php`
-   ⚠️ Pending: Plans table migration (needed for actual payment testing)

### Step 3: Login or Register

You need to be logged in to test payment features:

**Option 1: Use existing credentials**

-   Go to http://localhost:8000/login
-   Login with your account

**Option 2: Create a test user**

```bash
php artisan tinker
```

```php
// Create SuperAdmin
App\Models\User::create([
    'name' => 'Test Admin',
    'email' => 'admin@test.com',
    'role' => 'SuperAdmin',
    'password' => bcrypt('password123'),
]);

// Create OrganAdmin
App\Models\User::create([
    'name' => 'Test Org',
    'email' => 'org@test.com',
    'role' => 'organAdmin',
    'organization_name' => 'Test Organization',
    'password' => bcrypt('password123'),
]);
```

### Step 4: Test Payment Flow

#### As a Regular User:

1. **Access Payment Form** (requires plan ID):
    ```
    http://localhost:8000/payment/telebirr/1
    ```
2. **Fill Payment Form**:

    - Enter phone number (e.g., 912345678)
    - Enter transaction reference (any test value like "TRX123456")
    - Upload a test image (can be any JPG/PNG file < 2MB)
    - Add optional notes
    - Click "Submit Payment"

3. **View Success Page**:

    - Should show payment confirmation
    - Display payment reference number

4. **View Payment History**:
    ```
    http://localhost:8000/my-telebirr-payments
    ```

#### As SuperAdmin:

1. **Access Verification Dashboard**:
    ```
    http://localhost:8000/admin/telebirr-verifications
    ```
2. **View Pending Payments**:

    - See all submitted payments
    - View uploaded receipts
    - Check transaction details

3. **Approve/Reject Payment**:

    - Click "View Details" on any payment
    - Review receipt image
    - Click "Approve Payment" OR "Reject Payment"
    - If rejecting, provide a reason (min 10 characters)

4. **Check Results**:
    - Approved payments move to history
    - User's plan should be activated automatically
    - Rejected payments shown in rejected list

---

## 🔍 What to Verify

### Backend Functionality:

-   [ ] File upload working (receipt images saved to `/storage/receipts/telebirr/`)
-   [ ] Database transactions (payment records created correctly)
-   [ ] Validation working (try submitting empty form)
-   [ ] Authorization checks (users can only see their own payments)
-   [ ] Search functionality (search by transaction ref, phone, name)

### Frontend (when views are created):

-   [ ] Payment form displays correctly
-   [ ] Instructions are clear
-   [ ] File upload has proper validation messages
-   [ ] Success page shows confirmation
-   [ ] Admin dashboard shows pending payments
-   [ ] Receipt images display clearly
-   [ ] Approve/Reject buttons work

### Database:

-   [ ] All Telebirr fields exist in payments table
-   [ ] Data is saved correctly
-   [ ] Status changes work (pending → approved/rejected)
-   [ ] Timestamps are recorded properly

---

## ⚠️ Current Limitations

### Not Yet Implemented:

1. ❌ **Frontend Views** - No Blade templates for payment forms yet (Phase 3)
2. ❌ **Plan Integration** - Can't test actual plan upgrade without plans table
3. ❌ **Email Notifications** - Not configured yet
4. ❌ **CSS Styling** - Basic HTML only, no Tailwind/Bootstrap integration

### Workarounds:

-   Test via direct URL access
-   Use Postman/curl for POST requests
-   Check database directly to verify data
-   Use Laravel logs to see processing

---

## 🛠️ Testing Without Full Migration

If you can't run migrations due to SQLite ENUM issues:

### Manual Test - Direct Database Insert:

```bash
php artisan tinker
```

```php
// Create a test payment record
App\Models\Payment::create([
    'user_id' => 1, // Your user ID
    'plan_id' => null, // No plan needed for testing
    'name' => 'Test User',
    'organ_name' => 'Test Org',
    'amount' => 25.00,
    'billing' => 'monthly',
    'payment_method' => 'telebirr_manual',
    'status' => 'pending_verification',
    'receipt_image' => 'test.jpg',
    'telebirr_transaction_ref' => 'TRX123456',
    'payer_phone_number' => '912345678',
    'payment_notes' => 'Test payment',
    'verification_status' => 'pending',
    'verification_requested_at' => now(),
]);
```

Then access admin dashboard to see the test payment.

---

## 📊 Expected Results

### After Successful Test:

1. **Configuration**: ✅ Verified working
2. **Routes**: ✅ All 12 routes accessible
3. **Database**: ✅ Telebirr fields present in payments table
4. **File Upload**: ✅ Receipts saved to correct directory
5. **Controllers**: ✅ Methods execute without errors
6. **Authorization**: ✅ Users can only access their data
7. **Logging**: ✅ Payment attempts logged in storage/logs

---

## 🐛 Known Issues & Solutions

### Issue 1: "Plans table not found"

**Solution:** Run `php artisan migrate --force` or skip plan-dependent tests

### Issue 2: "SQLite ENUM error"

**Solution:** Use MySQL instead, or comment out ENUM migrations

### Issue 3: "404 Not Found"

**Solution:** Clear route cache: `php artisan route:clear`

### Issue 4: "Unauthorized" error

**Solution:** Make sure you're logged in first

### Issue 5: "Storage link not working"

**Solution:** Run `php artisan storage:link`

---

## 📝 Test Checklist

Use this checklist to verify everything works:

```
[ ] Server running (http://localhost:8000)
[ ] Logged in as test user
[ ] Test page accessible (/test-telebirr)
[ ] Configuration values displayed correctly
[ ] Routes list visible
[ ] Database migration status shown
[ ] Can access payment form (if plans exist)
[ ] Can submit payment with receipt
[ ] Success page displays
[ ] Payment appears in user history
[ ] Admin can see pending payments (if SuperAdmin)
[ ] Admin can approve/reject (if SuperAdmin)
[ ] Receipt downloads work
[ ] Search functionality works
[ ] Logs show payment activity
```

---

## 🎯 Next Steps After Testing

Once Phase 3 (Views) is complete, you'll have:

-   Beautiful payment forms with instructions
-   Professional admin dashboard
-   Responsive design with Tailwind CSS
-   Better user experience
-   Email notifications
-   Complete payment workflow

---

**Testing Branch:** dach  
**Last Updated:** March 25, 2026  
**Status:** Ready for browser testing ✅
