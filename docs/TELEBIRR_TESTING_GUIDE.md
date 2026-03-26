# 🧪 Telebirr Payment Testing Guide

## ✅ Prerequisites Complete

- [x] Sample plans created in database
- [x] All migrations up to date
- [x] Routes configured and working
- [x] Controllers implemented
- [x] Views created (8 Blade templates)
- [x] OrganAdmin account ready (d@gmail.com / 12345678)

---

## 📋 Test Plan Overview

### Test Data Available:
```
Plan ID 1: Basic     - ETB 0 (free)
Plan ID 2: Pro       - ETB 25 (monthly)  ← Use this for testing
Plan ID 3: Enterprise - ETB 50 (yearly)
```

---

## 🎯 Testing Scenarios

### **Scenario 1: User Payment Flow** ⭐

#### Step 1: Access Payment Landing Page
```
URL: http://127.0.0.1:8000/payment
Expected: See payment method selection page with Telebirr option
```

**What to Check:**
- ✅ Page loads without errors
- ✅ Telebirr card is highlighted in blue
- ✅ "View Available Plans" button visible (since no plan selected yet)
- ✅ Instructions section displays correctly

---

#### Step 2: Select a Plan
```
URL: http://127.0.0.1:8000/payment/2
Expected: See payment page with Pro plan details
```

**What to Check:**
- ✅ Blue info box shows plan details (Pro, ETB 25.00, monthly)
- ✅ "Pay with Telebirr" button is now active
- ✅ Plan summary displays correctly

---

#### Step 3: Fill Telebirr Payment Form
```
URL: Click "Pay with Telebirr" button
Expected: Navigate to payment form at /payment/telebirr/2
```

**Form Fields to Test:**

| Field | Test Input | Expected Validation |
|-------|-----------|---------------------|
| Phone Number | `912345678` | ✅ Valid (9 digits) |
| Phone Number | `123` | ❌ Error: Must be valid format |
| Transaction Ref | `TRX123456789` | ✅ Valid |
| Transaction Ref | *(empty)* | ❌ Error: Required field |
| Receipt Upload | *Upload test image* | ✅ Valid (JPG/PNG < 2MB) |
| Receipt Upload | *PDF file* | ❌ Error: Images only |
| Payment Notes | `Test payment` | ✅ Optional field |

**What to Check:**
- ✅ Plan summary displays at top
- ✅ USSD instructions are clear (6 steps)
- ✅ Pay Bill Number shows: 600600
- ✅ Form validation works correctly
- ✅ File upload has drag & drop UI
- ✅ Error messages display properly

---

#### Step 4: Submit Payment
```
Action: Fill form with valid data and click "Submit Payment"
Expected: Redirect to success page
```

**Test Data to Use:**
```
Phone Number: 912345678
Transaction Ref: TRX987654321
Receipt: Upload any test image (can create fake screenshot)
Payment Notes: Testing payment submission
```

**What to Check:**
- ✅ Success page loads with green checkmark
- ✅ Payment details are correct:
  - Payment ID: (auto-generated)
  - Transaction Ref: TRX987654321
  - Amount: ETB 25.00
  - Status: "Pending Verification" badge (yellow)
- ✅ "Next Steps" section shows 4 bullet points
- ✅ Important note about transaction reference
- ✅ Two action buttons work:
  - "View My Payments" → Goes to history
  - "Go to Dashboard" → Goes to /home

---

#### Step 5: View Payment History
```
URL: http://127.0.0.1:8000/my-telebirr-payments
Expected: See table with submitted payment
```

**What to Check:**
- ✅ Table shows your payment submission
- ✅ Status badge is yellow "Pending"
- ✅ Transaction ref displays correctly
- ✅ Amount shows: ETB 25.00
- ✅ "Receipt" link is clickable
- ✅ Empty state shows if no payments exist

---

### **Scenario 2: Admin Verification Flow** ⭐

#### Step 1: Login as SuperAdmin
```
Credentials:
Email: d@gmail.com
Password: 12345678
```

**Important:** Make sure you're logged in as SuperAdmin (not OrganAdmin) to see admin pages.

---

#### Step 2: Access Verification Dashboard
```
URL: http://127.0.0.1:8000/admin/telebirr-verifications
Expected: See pending payments dashboard
```

**What to Check:**
- ✅ Purple gradient header displays
- ✅ Pending count badge shows number (should be 1+)
- ✅ Table lists all pending payments
- ✅ Your test payment appears in the table
- ✅ Columns show: ID, User, Phone, Transaction Ref, Amount, Submitted
- ✅ "Review" button with eye icon

**If No Payments Show:**
- Check if you submitted payment correctly
- Verify you're logged in as admin
- Check database payments table

---

#### Step 3: Review Payment Detail
```
Action: Click "Review" button on a pending payment
URL: http://127.0.0.1:8000/admin/telebirr-verify/{ID}
Expected: Open detailed review page
```

**What to Check:**

**User Information Card (Left):**
- ✅ Name displays
- ✅ Email shows
- ✅ Organization name (if provided)
- ✅ Phone number matches submission

**Payment Details Card (Right):**
- ✅ Transaction Reference: TRX987654321
- ✅ Amount: ETB 25.00 (bold green)
- ✅ Payment Method: "Telebirr Manual"
- ✅ Submitted timestamp
- ✅ Status: Yellow "Pending Verification" badge

**Receipt Image Section:**
- ✅ Receipt image displays large and clear
- ✅ Image is readable (not pixelated)
- ✅ Download button present
- ✅ Clicking download saves image

**Action Buttons:**
- ✅ Green "Approve Payment" button
- ✅ Red "Reject Payment" button (toggles form)
- ✅ Rejection form is hidden initially
- ✅ Clicking "Reject Payment" shows textarea
- ✅ Cancel button hides rejection form again

**Verification Guidelines:**
- ✅ Blue help box at bottom
- ✅ 5 guidelines listed

---

#### Step 4: Approve Payment
```
Action: Click "Approve Payment" button
Expected: Payment approved, user's plan activated
```

**What Should Happen:**
1. ✅ Database transaction completes
2. ✅ Payment status changes to "approved"
3. ✅ User's plan_id updates to selected plan
4. ✅ User's plan_expiry set (1 month from now)
5. ✅ Redirect to verification dashboard
6. ✅ Success message displays: "Payment approved successfully..."

**Verify in Database:**
```bash
php artisan tinker --execute="
\$payment = App\Models\Payment::find({PAYMENT_ID});
echo 'Status: ' . \$payment->verification_status . PHP_EOL;
echo 'Verified At: ' . \$payment->verified_at . PHP_EOL;
\$user = \$payment->user;
echo 'User Plan ID: ' . \$user->plan_id . PHP_EOL;
echo 'Plan Expiry: ' . \$user->plan_expiry . PHP_EOL;
"
```

---

#### Step 5: View Approved History
```
URL: http://127.0.0.1:8000/admin/telebirr-history
Expected: See approved payment in history
```

**What to Check:**
- ✅ Green gradient header
- ✅ Revenue summary banner at top
- ✅ Total revenue = sum of all approved payments
- ✅ Your test payment appears in table
- ✅ Shows "Verified By" admin name
- ✅ Approval date/time displays
- ✅ "View" button links to detail page

**Revenue Stats:**
- ✅ Total Revenue Generated: ETB 25.00 (or more)
- ✅ Last 30 Days: Shows recent revenue

---

#### Step 6: Test Rejection Flow
```
Prerequisite: Create another test payment (repeat Scenario 1)
```

**Steps:**
1. Go to `/admin/telebirr-verifications`
2. Click "Review" on a different payment
3. Click "Reject Payment" button (red)
4. Rejection form slides down
5. Enter rejection reason:
   ```
   The transaction reference does not match our records. 
   Please verify with Telebirr and resubmit.
   ```
6. Click "Confirm Rejection"

**What to Check:**
- ✅ Payment status changes to "rejected"
- ✅ Rejection reason saved in database
- ✅ Verified timestamp recorded
- ✅ Redirect to dashboard
- ✅ Success message: "Payment rejected..."

**View Rejected Payments:**
```
URL: http://127.0.0.1:8000/admin/telebirr-rejected
```

**What to Check:**
- ✅ Red gradient header
- ✅ Rejected payment appears in table
- ✅ Rejection reason truncated (hover for full text)
- ✅ Email icon button present
- ✅ Clicking email icon opens mail client with:
  - To: User's email
  - Subject: Regarding Your Payment #{ID}
  - Body: Pre-filled message with payment details

---

### **Scenario 3: Edge Cases & Error Handling**

#### Test 1: Invalid Plan ID
```
URL: http://127.0.0.1:8000/payment/999
Expected: Redirect to payment index with error message
```
**Check:** Error message: "Plan not found. Please select a valid plan."

---

#### Test 2: Missing Receipt Upload
```
Action: Submit payment form without uploading receipt
Expected: Validation error
```
**Check:** Error message: "The receipt image field is required."

---

#### Test 3: Large File Upload
```
Action: Try to upload file > 2MB
Expected: Validation error
```
**Check:** Error message: "The receipt image may not be greater than 2048 kilobytes."

---

#### Test 4: Invalid File Type
```
Action: Upload PDF or non-image file
Expected: Validation error
```
**Check:** Error message: "The receipt image must be an image."

---

#### Test 5: Invalid Phone Format
```
Action: Enter phone number with letters or < 9 digits
Expected: Validation error
```
**Check:** Error message about invalid format

---

#### Test 6: Empty State Views
```
For Users: Delete all payments from database
For Admins: Approve/reject all pending payments
```

**Check:**
- ✅ User sees: "No payments yet" with upgrade CTA
- ✅ Admin sees: "No pending payments" message
- ✅ Empty state icons display correctly
- ✅ Helpful text suggestions appear

---

## 🐛 Common Issues & Solutions

### Issue 1: "404 Not Found" on /payment
**Solution:** Run `php artisan route:clear` and restart server

### Issue 2: "Class not found" error
**Solution:** Run `composer dump-autoload`

### Issue 3: Views not rendering
**Solution:** Clear view cache: `php artisan view:clear`

### Issue 4: Receipt image not displaying
**Solution:** 
1. Check file exists in `storage/app/public/receipts/telebirr/`
2. Ensure storage link created: `php artisan storage:link`
3. Check file permissions

### Issue 5: "Cannot approve payment" error
**Solution:** 
1. Check user has plan associated
2. Verify payment status is "pending"
3. Check database foreign key constraints

---

## ✅ Test Completion Checklist

### User Flow Tests
- [ ] Payment landing page loads
- [ ] Plan selection works
- [ ] Telebirr form renders
- [ ] Form validation works
- [ ] Receipt upload successful
- [ ] Payment submission succeeds
- [ ] Success confirmation displays
- [ ] Payment history shows submission

### Admin Flow Tests
- [ ] Verification dashboard loads
- [ ] Pending payments visible
- [ ] Payment detail view works
- [ ] Receipt image displays clearly
- [ ] Approve function works
- [ ] User plan activates after approval
- [ ] Approved history shows revenue
- [ ] Reject function works
- [ ] Rejection reason saves
- [ ] Email integration works

### Technical Tests
- [ ] All routes accessible
- [ ] No 500 errors
- [ ] No JavaScript errors in console
- [ ] Responsive on mobile
- [ ] Images load properly
- [ ] Pagination works (if multiple payments)
- [ ] Database transactions atomic
- [ ] Logs show no errors

---

## 📊 Expected Results Summary

After completing all tests:

**Database Changes:**
- ✅ Payments table has test records
- ✅ Payment statuses: pending/approved/rejected
- ✅ Users table updated with plan_id and plan_expiry
- ✅ Verified_by and verified_at fields populated

**User Experience:**
- ✅ Smooth navigation between pages
- ✅ Clear visual feedback at each step
- ✅ Professional UI with proper styling
- ✅ Mobile-responsive layouts

**Admin Capabilities:**
- ✅ Can view all pending payments
- ✅ Can approve/reject with reasons
- ✅ Can track revenue statistics
- ✅ Can contact users about rejections

---

## 🚀 Next Steps After Testing

Once all tests pass:

1. ✅ Mark Phase 3 as COMPLETE
2. ✅ Proceed to Phase 4 (Integration)
3. ⏭️ Add email notifications
4. ⏭️ Deploy to production server
5. ⏭️ Train admin staff on verification workflow

---

## 📞 Support

If you encounter issues during testing:

1. **Check Laravel logs:** `storage/logs/laravel.log`
2. **Enable debug mode:** Set `APP_DEBUG=true` in `.env`
3. **Check browser console:** F12 → Console tab
4. **Verify database:** Use tinker to inspect records
5. **Clear caches:** `php artisan optimize:clear`

---

**Testing Status:** Ready to Begin  
**Test Environment:** Local Development (SQLite)  
**Server:** http://127.0.0.1:8000  
**Branch:** dach  

---

*Created: March 25, 2026*  
*Purpose: Comprehensive testing guide for Telebirr payment integration*
