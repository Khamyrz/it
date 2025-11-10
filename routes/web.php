<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InternController;
use App\Http\Controllers\InternAuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\TimeLogController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\SupervisorController;  
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;
/*
|--------------------------------------------------------------------------
| Default Redirect
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'))->name('welcome');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginRegister'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/register', [AuthController::class, 'register'])->name('register');
// Secret Admin API routes
Route::get('/admin/check-exists', [AdminController::class, 'checkAdminExists'])->name('admin.checkExists');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/register', [AdminController::class, 'register'])->name('admin.register');
Route::post('/admin/forgot-password', [AdminController::class, 'forgotPassword'])->name('admin.forgot');
Route::post('/admin/verify-forgot-otp', [AdminController::class, 'verifyForgotOtp'])->name('admin.verify-forgot-otp');
// OTP Verification Routes (Users)
Route::get('/verify-otp', [AuthController::class, 'showOtpForm'])->name('otp.verify');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.verify.submit');
Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('otp.resend');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::post('/password/email', [AuthController::class, 'sendPasswordResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.update');

// OTP-based forgot password routes
Route::post('/password/forgot', [AuthController::class, 'forgotPassword'])->name('password.forgot');
Route::post('/password/verify-otp', [AuthController::class, 'verifyForgotPasswordOtp'])->name('password.verify-otp');
Route::post('/password/resend-otp', [AuthController::class, 'resendForgotPasswordOtp'])->name('password.resend-otp');
Route::post('/password/reset-submit', [AuthController::class, 'resetPasswordWithOtp'])->name('password.reset.submit');

// Admin routes removed

// Supervisor Login & Registration (PUBLIC)
Route::get('/supervisor/login', [SupervisorController::class, 'showLoginForm'])->name('supervisor.login');
Route::post('/supervisor/login', [SupervisorController::class, 'login'])->name('supervisor.login.submit');
Route::post('/supervisor/register', [SupervisorController::class, 'register'])->name('supervisor.register.submit');



/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    
    // Admin Dashboard
    Route::get('/admin-dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/ip-details/{ipAddress}', [AdminController::class, 'getIpDetails'])->name('admin.ipDetails');

    // Intern Management
    Route::get('/interns', [DashboardController::class, 'interns'])->name('interns');
    // Invitation link generation (12-hour token) - MUST come before /interns/{id} to avoid route conflict
    Route::get('/interns/invite-link', [DashboardController::class, 'generateInviteLink'])->name('interns.invite-link');
    Route::get('/interns/{id}', [InternController::class, 'show'])->name('intern.show');
    Route::get('/interns/{id}/edit', [InternController::class, 'edit'])->name('intern.edit');
    Route::put('/interns/{id}', [InternController::class, 'update'])->name('intern.update');
    // Delete pending/accepted intern via DashboardController for admin view consistency
    Route::delete('/interns/{id}', [DashboardController::class, 'destroyIntern'])->name('intern.destroy');
    Route::post('/interns/{id}/archive', [DashboardController::class, 'archiveIntern'])->name('intern.archive');
    Route::post('/interns/{id}/accept', [InternController::class, 'accept'])->name('intern.accept');
    Route::delete('/interns/delete-all', [DashboardController::class, 'deleteAllInterns'])->name('interns.deleteAll');
    
    // Phase Management
    Route::post('/interns/{id}/accept-pre-enrollment', [InternController::class, 'acceptPreEnrollment'])->name('intern.accept.pre-enrollment');
    Route::post('/interns/{id}/accept-pre-deployment', [InternController::class, 'acceptPreDeployment'])->name('intern.accept.pre-deployment');
    Route::post('/interns/{id}/accept-mid-deployment', [InternController::class, 'acceptMidDeployment'])->name('intern.accept.mid-deployment');
    Route::post('/interns/{id}/accept-deployment', [InternController::class, 'acceptDeployment'])->name('intern.accept.deployment');

    // Phase Rejection/Reset
    Route::post('/interns/{id}/reject-pre-deployment', [InternController::class, 'rejectPreDeployment'])->name('intern.reject.pre-deployment');
    Route::post('/interns/{id}/reject-mid-deployment', [InternController::class, 'rejectMidDeployment'])->name('intern.reject.mid-deployment');
    Route::post('/interns/{id}/reject-deployment', [InternController::class, 'rejectDeployment'])->name('intern.reject.deployment');

    // Document Viewing
    Route::get('/documents/view/{filename}', [InternController::class, 'viewDocument'])->name('documents.view');
    Route::get('/documents/{id}/endorsement', [InternController::class, 'viewEndorsement'])->name('documents.endorsement');

    // Grades
    Route::get('/grades', [DashboardController::class, 'grades'])->name('grades');
    Route::post('/grades/request', [DashboardController::class, 'sendGradeRequest'])->name('grades.request');

    // Documents
    Route::get('/documents', [DashboardController::class, 'documents'])->name('documents');
    Route::get('/documents/archive', [DashboardController::class, 'documentsArchive'])->name('documents.archive');
    Route::get('/documents/{id}/dtr', [TimeLogController::class, 'showDTR'])->name('documents.dtr');
    Route::get('/documents/{id}/journal', [JournalController::class, 'adminView'])->name('admin.journal');

    // QR Code
    Route::get('/qr', [DashboardController::class, 'qr'])->name('qr');
    Route::get('/show-qr-code', [QrCodeController::class, 'display'])->name('show.qr');

    

    // Messaging
    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
    Route::get('/messages/{internId}', [MessageController::class, 'conversation'])->name('messages.conversation');
    Route::post('/messages/send', [MessageController::class, 'sendToIntern'])->name('messages.send');
    Route::post('/messages/broadcast', [MessageController::class, 'broadcast'])->name('messages.broadcast');
    Route::delete('/messages/clear/{intern}', [MessageController::class, 'clearConversation'])->name('messages.clear');
    Route::get('/api/messages/{internId}/new', [MessageController::class, 'getNewMessages'])->name('api.messages.new');

    // Supervisor Authentication
    // REMOVE supervisor login/register routes from here if present

    // Admin Supervisor Management
    Route::get('/supervisors', [SupervisorController::class, 'index'])->name('supervisors');
    Route::post('/supervisors/{id}/accept', [SupervisorController::class, 'accept'])->name('supervisor.accept');
    Route::post('/supervisors/{id}/reject', [SupervisorController::class, 'reject'])->name('supervisor.reject');
    Route::put('/supervisors/{id}/update', [SupervisorController::class, 'update'])->name('supervisor.update');
    Route::delete('/supervisors/{id}/delete', [SupervisorController::class, 'delete'])->name('supervisor.delete');
});

// Admin connect interns routes removed

/*
|--------------------------------------------------------------------------
| Intern Registration via QR Code
|--------------------------------------------------------------------------
*/
Route::get('/qr-register', fn () => view('qr'))->name('qr.register');
Route::post('/intern/store', [InternController::class, 'store'])->name('intern.store');
Route::get('/api/invite/verify', [InternController::class, 'verifyInvite'])->name('api.invite.verify');

/*
|--------------------------------------------------------------------------
| Intern Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/intern/login', [InternAuthController::class, 'showLoginForm'])->name('intern.login');
Route::post('/intern/login', [InternAuthController::class, 'login'])->name('intern.login.submit');
Route::post('/intern/logout', [InternAuthController::class, 'logout'])->name('intern.logout');

// Intern OTP routes
Route::post('/intern/verify-otp', [InternController::class, 'verifyOtp'])->name('intern.otp.verify.submit');
Route::post('/intern/resend-otp', [InternController::class, 'resendOtp'])->name('intern.otp.resend');

// Intern Forgot Password routes
Route::post('/intern/password/forgot', [InternController::class, 'forgotPassword'])->name('intern.password.forgot');
Route::post('/intern/password/verify-otp', [InternController::class, 'verifyForgotPasswordOtp'])->name('intern.password.verify-otp');
Route::post('/intern/password/resend-otp', [InternController::class, 'resendForgotPasswordOtp'])->name('intern.password.resend-otp');
Route::post('/intern/password/reset', [InternController::class, 'resetPassword'])->name('intern.password.reset.submit');
Route::get('/intern/phase-submission', [InternAuthController::class, 'phaseSubmission'])->name('intern.phase-submission');


/*
|--------------------------------------------------------------------------
| Intern Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:intern'])->group(function () {

    // Dashboard
    Route::get('/intern/dashboard', [InternAuthController::class, 'dashboard'])->name('intern.dashboard');

    // Grades (.docx upload)
    Route::get('/intern/send-data', [InternAuthController::class, 'showSendDataForm'])->name('intern.send-data');
    Route::post('/intern/upload-docx', [InternAuthController::class, 'uploadDocx'])->name('intern.uploadDocx');

    // Messaging
    Route::get('/intern/messages', [MessageController::class, 'internMessages'])->name('intern.messages');
    Route::post('/intern/messages/send', [MessageController::class, 'sendFromIntern'])->name('intern.messages.send');
    Route::get('/api/intern/messages/new', [MessageController::class, 'getNewInternMessages'])->name('api.intern.messages.new');
    Route::get('/api/intern/message/stats', [MessageController::class, 'getInternMessageStats'])->name('api.intern.message.stats');

    // Time In / Time Out (DTR)
    Route::get('/intern/dtr', [InternAuthController::class, 'dtr'])->name('intern.dtr');
    Route::post('/intern/time-in', [TimeLogController::class, 'timeIn'])->name('intern.timein');
    Route::post('/intern/time-out', [TimeLogController::class, 'timeOut'])->name('intern.timeout');
    
    // Real-time DTR Tracking
    Route::get('/intern/dtr/real-time', [TimeLogController::class, 'getRealTimeDTR'])->name('intern.dtr.real-time');
    Route::get('/intern/dtr/summary', [TimeLogController::class, 'getDTRSummary'])->name('intern.dtr.summary');

    // Journal
    Route::get('/intern/journal', [JournalController::class, 'show'])->name('intern.journal');
    Route::post('/intern/journal', [JournalController::class, 'submit'])->name('intern.journal.submit');

    // Endorsement letter (auto-generated & downloadable/printable)
    Route::get('/intern/endorsement', [InternAuthController::class, 'endorsement'])->name('intern.endorsement');
    // Auto-generated documents
    Route::get('/intern/acceptance-letter', [InternAuthController::class, 'acceptanceLetter'])->name('intern.acceptance');
    Route::get('/intern/memorandum', [InternAuthController::class, 'memorandum'])->name('intern.memorandum');
    Route::get('/intern/internship-contract', [InternAuthController::class, 'internshipContract'])->name('intern.contract');

    // Phase Document Submission
    Route::post('/intern/submit-pre-deployment', [InternController::class, 'submitPreDeployment'])->name('intern.submit.pre-deployment');
    Route::post('/intern/submit-mid-deployment', [InternController::class, 'submitMidDeployment'])->name('intern.submit.mid-deployment');
    Route::post('/intern/submit-deployment', [InternController::class, 'submitDeployment'])->name('intern.submit.deployment');
    
    // Phase Submission Page
    Route::get('/intern/phase-submission', [InternAuthController::class, 'phaseSubmission'])->name('intern.phase-submission');
});

// Supervisor Registration
// REMOVE supervisor registration route from here if present

// Supervisor Protected Routes
Route::middleware(['auth:supervisor'])->group(function () {
    Route::get('/supervisor/dashboard', [SupervisorController::class, 'dashboard'])->name('supervisor.dashboard');
    Route::post('/supervisor/release-attendance', [SupervisorController::class, 'releaseAttendance'])->name('supervisor.releaseAttendance');
    Route::post('/supervisor/logout', [SupervisorController::class, 'logout'])->name('supervisor.logout');
    
    // Attendance management routes
    Route::get('/supervisor/attendance/status', [AttendanceController::class, 'getAttendanceStatus'])->name('supervisor.attendance.status');
    Route::post('/supervisor/attendance/mark-absent/{intern}', [AttendanceController::class, 'markAbsent'])->name('supervisor.attendance.mark-absent');
    Route::post('/supervisor/attendance/reset', [AttendanceController::class, 'resetAttendance'])->name('supervisor.attendance.reset');
});

// Intern attendance routes
Route::middleware(['auth:intern'])->group(function () {
    // Attendance marking
    Route::post('/intern/attendance/mark', [AttendanceController::class, 'markAttendance'])->name('intern.attendance.mark');
    Route::get('/intern/attendance', [AttendanceController::class, 'showAttendance'])->name('intern.attendance');
});
