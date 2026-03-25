# Feature Implementation Requirements

Based on our analysis of the OMMS project architecture, here are the detailed requirements and technical approaches for implementing each of the requested features into the Laravel application.

## 1. Fayda ID Card Integration
**Goal:** Verify member identity directly against the Ethiopian National ID (Fayda) database.

### Prerequisites (External)
- Official documentation and testing/production credentials (API keys, certificates) from the Fayda API portal.
- Approval from authorities to handle citizens' national ID data, ensuring compliance with local data privacy laws.

### Technical Requirements
- **Database:** Add a `fayda_id` (string, unique, nullable) and `fayda_verified_at` (timestamp, nullable) column to the `members` (or `users`) table.
- **Service Layer:** Create `app/Services/FaydaService.php` to handle secure API requests to the Fayda system (Authentication, Data Fetching, Error Handling).
- **UI/UX:** Add a "Fayda ID" input field to the registration and profile edit forms.
- **Security:** Ensure Fayda IDs are either encrypted at rest or hashed if direct storage is prohibited by data governance rules.
- **Validation:** Add custom Laravel validation rules to ensure the entered ID format conforms to Fayda standards before sending an API request.

---

## 2. Custom Member Attributes
**Goal:** Allow administrators to define dynamic fields (e.g., Blood Type, Emergency Contact) without altering the database schema every time.

### Technical Requirements
- **Database:** 
  - Option A (Simple): Add a `custom_attributes` column (type [json](file:///c:/Users/ZACK%20ZULUPP/OneDrive/Desktop/projects/OMMS/omms/package.json)) to the `members` table.
  - Option B (Entity-Attribute-Value): Create two new tables: `member_attributes` (`id`, `name`, `type`, `is_required`) and `member_attribute_values` (`member_id`, `attribute_id`, `value`). Option A is recommended for modern MySQL/PostgreSQL as it's faster to query.
- **Admin Interface:** A settings page where admins can define new custom fields (specifying field name, input type like text/date/dropdown).
- **User Interface:** Update member registration and view pages to dynamically render these custom fields based on what the admin has configured.
- **Validation:** Dynamic validation rules during form submission to ensure required custom fields are provided.

---

## 3. Telebirr Payment Integration
**Goal:** Automate subscription/donation collections using Telebirr.

### Prerequisites (External)
- Telebirr Merchant/App ID, App Key, Short Code, and public key from Ethio Telecom.
- Access to the Telebirr SuperApp/MiniApp API documentation.

### Technical Requirements
- **Database:** Create a `transactions` table with columns: `user_id`, `amount`, `currency` (ETB), `status` (pending, completed, failed), `payment_method` (telebirr), `transaction_ref` (unique internal ID), and `external_ref` (Telebirr's transaction ID).
- **Service Layer:** Build a `TelebirrService.php` to generate signed payloads for the payment request and verify callbacks.
- **Endpoints:**
  1. Payment Initiation Route: Generates a Telebirr checkout URL or QR code for the user to scan.
  2. Webhook Callback Route (`/api/webhooks/telebirr`): An unauthenticated (but signature-verified) endpoint to receive asynchronous success/failure pushes from Telebirr.
- **UI/UX:** A "Pay with Telebirr" button in the billing section that redirects the user or opens a modal.

---

## 4. Manual Payment & Invoice Processing
**Goal:** Support offline payments (Cash, Bank Transfer, Check) with official invoice generation.

### Technical Requirements
- **Database:** 
  - An `invoices` table (`id`, `user_id`, `amount`, `due_date`, `status` [unpaid, paid, partial]).
  - A `payments` table mapping to invoices (`id`, `invoice_id`, `amount_paid`, `method` [cash, bank_transfer, etc.], `reference_number`, `date`, `receipt_path`).
- **Admin Interface:** A dedicated "Record Payment" screen where admins can select a member, input the received amount, choose the payment method, and upload a scanned physical receipt.
- **PDF Generation:** Use a Laravel package like `barryvdh/laravel-dompdf` to generate downloadable/printable PDF invoices and receipts dynamically.
- **User Interface:** A "Billing History" page for members to download their invoices and see payment statuses.

---

## 5. OTP-Based User Registration
**Goal:** Verify Ethiopian phone numbers seamlessly during sign-up to prevent fake accounts.

### Prerequisites (External)
- An account with a local SMS Gateway Provider (e.g., AfricasTalking, or a direct Ethio Telecom SMS gateway connection) with API access.

### Technical Requirements
- **Database:** Add `phone` (string, unique), `phone_verified_at` (timestamp, nullable), and `otp_code` (nullable) to the `users` table, or use a separate `otps` table to track attempts and expirations.
- **Registration Flow Changes:**
  - **Step 1:** User enters Name, Phone, and Password. System generates a 4-6 digit OTP, saves it (hashed or plaintext with expiration), and sends it via SMS.
  - **Step 2:** User is redirected to a "Verify Phone" screen. Enter OTP.
  - **Step 3:** If OTP matches and is not expired (e.g., standard 5-minute window), set `phone_verified_at` to `now()` and log the user in.
- **Security:** Implement rate limiting (e.g., max 3 OTP requests per hour per IP/Phone) to prevent SMS toll fraud.

---

## 6. OCR-Based Payment Verification (Telebirr/CBE Birr Screenshots)
**Goal:** Automatically read screenshots of mobile banking transfers to verify payment without manual admin approval, when direct API isn't available.

### Technical Requirements
- **External Dependency:** An Optical Character Recognition (OCR) engine. 
  - Option A: Local (`tesseract-ocr` via `thiagoalessio/tesseract_ocr`). Requires installing Tesseract on the server.
  - Option B: Cloud AI (Google Cloud Vision API or AWS Textract). Highly recommended for accuracy, especially given differing Android/iOS screenshot formats and mixed English/Amharic text.
- **Feature Flow:**
  - User goes to "Upload Receipt," selects "CBE Birr Transfer," enters the expected transaction reference, and uploads the screenshot.
  - Form submission uploads the image to Laravel Storage.
  - A Laravel Queue Job (`ProcessReceiptOcr`) is dispatched (to prevent the user from waiting 5-10 seconds).
  - The job sends the image to the OCR engine.
  - Extract text and use Regular Expressions (Regex) to find:
    - The Transaction Reference (e.g., exact 10-12 character alphanumeric string).
    - The Amount transferred.
    - The Date of transfer.
  - If the extracted data matches the user's claimed invoice, automatically mark as `paid`.
- **Admin Fallback:** If OCR confidence is low or data doesn't match perfectly, the payment status becomes `pending_review`, and an admin must manually approve or reject it from a dashboard.

---

## Next Steps
To begin development, please review the above. Let me know which feature you would like to prioritize first, and we can start setting up the required database migrations and logic.
