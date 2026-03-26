<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\organAdminController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PaymentController;



use App\Http\Controllers\Auth\GoogleController;







/**Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', [HomeController::class, 'index']);

Route::get('/about', [HomeController::class, 'about'])->name('guest.about');
Route::get('/service', [HomeController::class, 'service'])->name('guest.service');
Route::get('/events', [HomeController::class, 'event'])->name('guest.events');
Route::get('/blogs', [HomeController::class, 'blog'])->name('guest.blogs');
Route::get('/contact', [HomeController::class, 'contact'])->name('guest.contact');






Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);




Route::get('/home', [HomeController::class, 'redirect'])->middleware('auth', 'verified');





Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/organ', [AdminController::class, 'organ']);
Route::get('/orgAdmin', [AdminController::class, 'orgAdmin']);
Route::post('/orgadmin_upload', [AdminController::class, 'orgadmin_upload']);
Route::get('/members', [AdminController::class, 'members']);
Route::get('/organadmin', [AdminController::class, 'organadmin']);
Route::get('/payments', [AdminController::class, 'payments']);

Route::get('/addorgan', [AdminController::class, 'muaz']);
Route::post('/uploadorgan', [AdminController::class, 'uploadorgan']);
Route::get('/editorgan/{id}', [AdminController::class, 'editorgan']);
Route::post('/updateorgan/{id}', [AdminController::class, 'updateorgan']);
Route::get('/deleteorgan/{id}', [AdminController::class, 'deleteorgan']);

Route::get('/editmembers/{id}', [AdminController::class, 'editmembers']);
Route::post('/updatemember/{id}', [AdminController::class, 'updatemember']);
Route::get('/deletemembers/{id}', [AdminController::class, 'deletemembers']);



Route::get('/member1', [organAdminController::class, 'member']);
Route::get('/event', [organAdminController::class, 'event']);
Route::get('/blog', [organAdminController::class, 'blog']);
Route::get('/payment/{plan_id}', [organAdminController::class, 'payment']);
Route::get('/upgrade', [PlanController::class, 'ShowUpgradePlan'])->name('organAdmin.plans.upgrade');
Route::post('/upgrade', [PlanController::class, 'UpgradePlan']);

Route::get('/sidebar nav', [organAdminController::class, 'sidebar']);
Route::get('/edit_profile/{id}', [organAdminController::class, 'editprofile']);
Route::post('/updateprofile/{id}', [organAdminController::class, 'updateprofile']);


Route::get('/addmember', [organAdminController::class, 'addmember']);
Route::post('/upload_member', [organAdminController::class, 'upload']);
Route::get('/edit/{id}', [organAdminController::class, 'edit']);
Route::post('/editmember/{id}', [organAdminController::class, 'editmember']);
Route::get('/deletemember/{id}', [organAdminController::class, 'deletemember']);


Route::post('/uploadevent', [organAdminController::class, 'uploadevent']);
Route::get('/editevent/{id}', [organAdminController::class, 'editevent']);
Route::post('/updateevent/{id}', [organAdminController::class, 'updateevent']);
Route::get('/deleteevent/{id}', [organAdminController::class, 'deleteevent']);

Route::post('/uploadblog', [organAdminController::class, 'uploadblog']);
Route::get('/editblog/{id}', [organAdminController::class, 'editblog']);
Route::post('/updateblog/{id}', [organAdminController::class, 'updateblog']);
Route::get('/deleteblog/{id}', [organAdminController::class, 'deleteblog']);

Route::post('/uploadpayment', [organAdminController::class, 'uploadpayment']);


Route::get('/sidebar', [MemberController::class, 'sidebar1']);

Route::get('/event1', [MemberController::class, 'event12']);

Route::get('/profile', [MemberController::class, 'profile']);

// Telebirr Manual Payment Routes
use App\Http\Controllers\TelebirrManualController;
use App\Http\Controllers\Admin\TelebirrVerificationController;

// Payment landing page (shows all payment methods)
Route::get('/payment', function() {
    return view('payment.index');
})->middleware(['auth'])->name('payment.index');

// Payment with plan selection
Route::get('/payment/{plan_id}', function($planId) {
    try {
        $plan = \App\Models\Plan::findOrFail($planId);
        return view('payment.index', compact('plan'));
    } catch (\Exception $e) {
        return redirect()->route('payment.index')
            ->with('error', 'Plan not found. Please select a valid plan.');
    }
})->middleware(['auth']);

// User routes
Route::get('/payment/telebirr/{plan_id}', [TelebirrManualController::class, 'showPaymentForm'])
    ->name('payment.telebirr.form')
    ->middleware(['auth']);

Route::post('/payment/telebirr/submit', [TelebirrManualController::class, 'submitPayment'])
    ->name('payment.telebirr.submit')
    ->middleware(['auth']);

Route::get('/payment/telebirr/success', [TelebirrManualController::class, 'showSuccess'])
    ->name('payment.telebirr.success')
    ->middleware(['auth']);

Route::get('/my-telebirr-payments', [TelebirrManualController::class, 'userPayments'])
    ->name('payment.telebirr.history')
    ->middleware(['auth']);

Route::get('/telebirr-receipt/{paymentId}/download', [TelebirrManualController::class, 'downloadReceipt'])
    ->name('payment.telebirr.download')
    ->middleware(['auth']);

// Admin routes
Route::get('/admin/telebirr-verifications', [TelebirrVerificationController::class, 'index'])
    ->name('admin.telebirr.verifications')
    ->middleware(['auth']); // Add role:SuperAdmin middleware later

Route::get('/admin/telebirr-verify/{id}', [TelebirrVerificationController::class, 'show'])
    ->name('admin.telebirr.verify.detail')
    ->middleware(['auth']);

Route::post('/admin/telebirr-approve/{id}', [TelebirrVerificationController::class, 'approve'])
    ->name('admin.telebirr.approve')
    ->middleware(['auth']);

Route::post('/admin/telebirr-reject/{id}', [TelebirrVerificationController::class, 'reject'])
    ->name('admin.telebirr.reject')
    ->middleware(['auth']);

Route::get('/admin/telebirr-history', [TelebirrVerificationController::class, 'history'])
    ->name('admin.telebirr.history')
    ->middleware(['auth']);

Route::get('/admin/telebirr-rejected', [TelebirrVerificationController::class, 'rejected'])
    ->name('admin.telebirr.rejected')
    ->middleware(['auth']);

Route::get('/admin/telebirr-search', [TelebirrVerificationController::class, 'search'])
    ->name('admin.telebirr.search')
    ->middleware(['auth']);

// Test page for Telebirr integration (remove after testing)
Route::get('/test-telebirr', function () {
    // Get pending migrations
    $files = glob(database_path('migrations/*.php'));
    $ran = \Illuminate\Support\Facades\DB::table('migrations')->pluck('migration')->toArray();

    $pendingMigrations = [];
    foreach ($files as $file) {
        $name = str_replace('.php', '', basename($file));
        if (!in_array($name, $ran)) {
            $pendingMigrations[] = $name;
        }
    }

    return view('test-telebirr', compact('pendingMigrations'));
});
