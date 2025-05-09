<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\HospitalRegistrationController;
use App\Http\Controllers\UserRegistrationController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\RadiologistDashboardController;
use App\Http\Controllers\RadiographerActivityController;
use App\Http\Controllers\RadiographerPatientController;
use App\Http\Controllers\PatientHistoryController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorNotificationController;
use App\Http\Controllers\RadiographerNotificationController;
use App\Http\Controllers\PatientNotificationController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserLogController;
use App\Models\HospitalUser; 
use App\Http\Controllers\ConsultationController;


// Fallback Home Route
Route::get('/', function () {
    return view('home');
})->name('home');

// Route to show the scan upload form for a patient
Route::get('/patient/{patient}/upload-scan', [PatientController::class, 'uploadScanForm'])
     ->name('radiographer.upload-scan');

// Route to process the scan upload
Route::post('/patient/{patient}/upload-scan', [PatientController::class, 'uploadScanStore'])
     ->name('radiographer.upload.store');
     
Route::get('/radiologist/patient/{patient}/upload-report', [PatientController::class, 'uploadReportForm'])
     ->name('radiologist.report');

Route::post('/radiologist/patient/{patient}/upload-report', [PatientController::class, 'uploadReportStore'])
     ->name('radiologist.upload.report.store');

// Optionally, a route to view patient details (if needed)
Route::get('/patient/{patient}/view', [PatientController::class, 'view'])
     ->name('patient.view');

//RADIOLOGIST-patient
Route::get('/radiographer/patient', [RadiographerPatientController::class, 'index'])
     ->name('radiographer.patient.search');


Route::post('radiologist/patients/{patient}/complete', [
     PatientController::class, 'markComplete'
])->name('radiologist.patients.complete');

Route::get('/patient/history/{id}', [PatientHistoryController::class, 'show'])
     ->name('patient.history');


Route::get('/doctor/profile', function () {
     $id   = session('hospital_user');
     $user = HospitalUser::findOrFail($id);
     return view('doctor.profile', compact('user'));
})->name('doctor.profile');
      
//DOCTOR
Route::prefix('doctor')
     ->name('doctor.')
     ->group(function() {
    // Show the review page
    Route::get(
        'patient/{patient}/review',
        [DoctorDashboardController::class, 'review']
    )->name('review');

    // Handle the review form submission
    Route::post(
        'patient/{patient}/review',
        [DoctorDashboardController::class, 'storeReview']
    )->name('review.store');

    // SUPPORT / SETTINGS / PRIVACY
    Route::get('support',   [DoctorDashboardController::class, 'supportForm'])
         ->name('support');
    Route::post('support',  [DoctorDashboardController::class, 'submitSupport'])
         ->name('support.submit');

    Route::get('settings',  [DoctorDashboardController::class, 'editProfile'])
         ->name('settings');
    Route::post('settings', [DoctorDashboardController::class, 'updateProfile'])
         ->name('settings.update');

    Route::get('privacy', function () {
        return view('doctor.privacy');
    })->name('privacy');

    Route::get('consultation', [ConsultationController::class,'create'])
         ->name('consultation.create');

    // POST  /doctor/consultation
    Route::post('consultation', [ConsultationController::class,'store'])
         ->name('consultation.store');
});




Route::get('/loading', function () {
     return view('loading');
})->name('loading');
      

//Named Routes
Route::get('/home', function () {
     return view('home');
 })->name("home");
 
Route::get('/login', function () {
    return view('login');
})->name("login");

Route::get('/about', function () {
    return view('about');
})->name("about");

Route::get('/demo', function () {
    return view('demo');
})->name("demo");

Route::get('/license', function () {
    return view('license');
})->name("license");

Route::get('/contact', function () {
    return view('contact');
})->name("contact");

//logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('home');
})->name('logout');



// Patient 
Route::get('/hospital-registration', [HospitalRegistrationController::class, 'create'])
     ->name('hospital.create');

// Handle form submission
Route::post('/hospital-registration', [HospitalRegistrationController::class, 'store'])
     ->name('hospital.store');


//User
Route::get('/user-register', [UserRegistrationController::class, 'create'])
     ->name('register.create');

Route::post('/user-register', [UserRegistrationController::class, 'store'])
     ->name('register.store');

//login
Route::get('/login', [LoginController::class, 'showLoginForm'])
     ->name('login');

Route::post('/login', [LoginController::class, 'login'])
     ->name('login.process');

Route::get('/management/profile', function () {
     $id   = session('hospital_user');
     $user = HospitalUser::findOrFail($id);
     return view('management.profile', compact('user'));
})->name('management.profile');
      
// ========== MANAGEMENT AREA ==========
Route::prefix('management')
     ->name('management.')
     ->group(function() {


    Route::get('dashboard', [ManagementController::class, 'dashboard'])
         ->name('dashboard');

    // HOSPITAL / USER / PATIENT MANAGEMENT
    Route::get('manage-hospital', [ManagementController::class, 'manageHospital'])
         ->name('manage-hospital');
    Route::get('manage-user',     [ManagementController::class, 'manageUser'])
         ->name('manage-user');
    Route::get('manage-patient',  [ManagementController::class, 'managePatient'])
         ->name('manage-patient');

     // User Logs listing
    Route::get('user/logs', [ManagementController::class, 'userLogs'])
         ->name('user.logs');
    // EDIT SINGLE PATIENT
    Route::get('patient/{patient}/edit',   [ManagementController::class, 'editPatient'])
         ->name('patient.edit');
    Route::put('patient/{patient}',        [ManagementController::class, 'updatePatient'])
         ->name('patient.update');

    // *** THIS IS THE ROUTE YOU NEED FOR “management.user.patients” ***
    Route::get('user/{user}/patients',     [ManagementController::class, 'editUserPatients'])
         ->name('user.patients');
    Route::put('user/{user}/patients',     [ManagementController::class, 'updateUserPatients'])
         ->name('user.patients.update');

    // User Logs
    Route::get('user/logs', [ManagementController::class, 'userLogs'])
         ->name('user.logs');

    // new: show edit form
    Route::get('/management/patients/{patient}/edit', [AdminController::class, 'editPatient'])
         ->name('patients.edit');

    // APPOINTMENTS
    Route::get('appointment/{patient}',    [AppointmentController::class, 'create'])
         ->name('appointment.create');
    Route::post('appointment/{patient}',   [AppointmentController::class, 'store'])
         ->name('appointment.store');

    // SUPPORT / SETTINGS / PRIVACY
    Route::get('support',   [ManagementController::class, 'supportForm'])
         ->name('support');
    Route::post('support',  [ManagementController::class, 'submitSupport'])
         ->name('support.submit');

    Route::get('settings',  [ManagementController::class, 'editProfile'])
         ->name('settings');
    Route::post('settings', [ManagementController::class, 'updateProfile'])
         ->name('settings.update');

    Route::get('privacy', function () {
        return view('management.privacy');
    })->name('privacy');

    Route::get('patient', [ManagementController::class, 'index'])
     ->name('patient.search');

     Route::get('history/{id}', [ManagementController::class, 'show'])
     ->name('history');

});

Route::get('/admin/profile', function () {
    $id   = session('hospital_user');
    $user = HospitalUser::findOrFail($id);
    return view('admin.profile', compact('user'));
})->name('admin.profile');

Route::prefix('admin')
     ->name('admin.')
     ->group(function() {

    // existing...
    Route::get('dashboard', [AdminController::class, 'dashboard'])
         ->name('dashboard');

    // User Logs
    Route::get('user/logs', [AdminController::class, 'userLogs'])
         ->name('user.logs');

    // new: show edit form
    Route::get('/admin/patients/{patient}/edit', [AdminController::class, 'editPatient'])
         ->name('patients.edit');

    // new: handle update
    Route::put('/admin/patients/{patient}', [AdminController::class, 'updatePatient'])
         ->name('patients.update');
         
     Route::get('support', function () {
          return view('admin.support');
     })->name('support');
  
     Route::post('support', [AdminController::class, 'submitSupport'])
          ->name('support.submit');
  
    // SETTINGS
    Route::get('settings', [AdminController::class, 'editProfile'])
         ->name('settings');

    Route::post('settings', [AdminController::class, 'updateProfile'])
         ->name('settings.update');
  
     // — PRIVACY —
     Route::get('privacy', function () {
          return view('admin.privacy');
     })->name('privacy');
});

//RADIOGRAPHER
Route::get('/radiographer/profile', function () {
     $id   = session('hospital_user');
     $user = HospitalUser::findOrFail($id);
     return view('radiographer.profile', compact('user'));
 })->name('radiographer.profile');
 
Route::prefix('radiographer')
     ->name('radiographer.')
     ->group(function() {
          Route::put('/radiographer/patients/{patient}', [AdminController::class, 'updatePatient'])
          ->name('radiographer.update');
          
      Route::get('support', function () {
           return view('radiographer.support');
      })->name('support');
   
      Route::post('support', [RadiographerActivityController::class, 'submitSupport'])
           ->name('support.submit');
   
     // SETTINGS
     Route::get('settings', [RadiographerActivityController::class, 'editProfile'])
          ->name('settings');
 
     Route::post('settings', [RadiographerActivityController::class, 'updateProfile'])
          ->name('settings.update');
   
      // — PRIVACY —
      Route::get('privacy', function () {
           return view('radiographer.privacy');
      })->name('privacy');  
      
      Route::get('history/{id}', [RadiographerActivityController::class, 'show'])
      ->name('history');
 
});



//RADIOLOGIST
Route::get('/radiologist/profile', function () {
     $id   = session('hospital_user');
     $user = HospitalUser::findOrFail($id);
     return view('radiologist.profile', compact('user'));
 })->name('radiologist.profile');
 
Route::prefix('radiologist')
     ->name('radiologist.')
     ->group(function() {
          Route::put('/radiologist/patients/{patient}', [AdminController::class, 'updatePatient'])
          ->name('radiologist.update');
          
      Route::get('support', function () {
           return view('radiologist.support');
      })->name('support');
   
      Route::post('support', [RadiologistDashboardController::class, 'submitSupport'])
           ->name('support.submit');
   
     // SETTINGS
     Route::get('settings', [RadiologistDashboardController::class, 'editProfile'])
          ->name('settings');
 
     Route::post('settings', [RadiologistDashboardController::class, 'updateProfile'])
          ->name('settings.update');
   
      // — PRIVACY —
      Route::get('privacy', function () {
           return view('radiologist.privacy');
      })->name('privacy');  
      
      Route::get('patient', [RadiologistDashboardController::class, 'search'])
      ->name('patient.search');
      
      Route::get('history/{id}', [RadiologistDashboardController::class, 'show'])
      ->name('history');

      Route::get('notifications', [RadiologistDashboardController::class,'notifications'])
      ->name('notifications.index');
      
    // Show the report‐upload form
    Route::get('patient/{patient}/report', [PatientController::class, 'uploadReportForm'])
         ->name('report.form');

    // Handle the report‐upload POST
    Route::post('patient/{patient}/report', [PatientController::class, 'uploadReportStore'])
         ->name('report.store');
});


// Apply the custom middleware to all dashboard routes

Route::get('/radiographer/dashboard', [RadiographerActivityController::class, 'index'])
->name('radiographer.dashboard');

Route::get('/radiologist/dashboard', [RadiologistDashboardController::class, 'index'])
->name('radiologist.dashboard');

Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'index'])
->name('doctor.dashboard');

Route::get('/patient/dashboard', [PatientController::class, 'index'])
    ->name('patient.dashboard');

    // Optionally secure your home route too:
Route::get('/', function () {
     return view('home');
})->name('home');



// Show notifications
Route::get('/admin/notifications', [NotificationController::class, 'index'])
     ->name('admin.notifications.index');

// Mark all read
Route::post('/admin/notifications/read', [NotificationController::class, 'markAllRead'])
     ->name('admin.notifications.read');

Route::get('/doctor/notifications', [DoctorNotificationController::class, 'index'])
     ->name('doctor.notifications.index');
Route::post('/doctor/notifications/read', [DoctorNotificationController::class, 'markAllRead'])
     ->name('doctor.notifications.read');

Route::get('/radiographer/notifications', [RadiographerNotificationController::class, 'index'])
     ->name('radiographer.notifications.index');
Route::post('/radiographer/notifications/read', [RadiographerNotificationController::class, 'markAllRead'])
     ->name('radiographer.notifications.read');

 Route::get('/patient/notifications', [PatientNotificationController::class, 'index'])
     ->name('patient.notifications.index');
Route::post('/patient/notifications/read', [PatientNotificationController::class, 'markAllRead'])
     ->name('patient.notifications.read');



// Profile page – the profile route expects a user id parameter
Route::get('/profile/{id}', [ProfileController::class, 'show'])
     ->name('profile.show');

// Profile edit route (if you want a separate route for editing)
Route::get('/profile/{id}/edit', [ProfileController::class, 'edit'])
     ->name('profile.edit');

// Profile update route
Route::put('/profile/{id}', [ProfileController::class, 'update'])
     ->name('profile.update');
     
// Manage Hospital page (for example)
Route::get('/hospital/manage', [HospitalController::class, 'manage'])
     ->name('hospital.manage');

// Example route for user logs
Route::get('/user/logs', function(){
    return view('user.logs'); // create a view if needed
})->name('user.logs');


// Support, Settings, Privacy routes (as examples)
Route::get('/support', function () {
    return view('sidebar.support');
})->name('support');

Route::post('/support', [SettingsController::class, 'submitSupport'])
     ->name('support.submit');

Route::get('/settings', [SettingsController::class, 'editProfile'])
     ->name('settings');

Route::post('/settings', [SettingsController::class, 'updateProfile'])
     ->name('settings.update');

Route::get('/privacy', function () {
    return view('sidebar.privacy');
})->name('privacy');

// Signup/show form & send already exist
Route::get('/index',  [OtpController::class, 'showSignupForm'])
     ->name('otp.signup.form');
Route::post('/index', [OtpController::class, 'send'])
     ->name('otp.signup.send');

// VERIFY routes:
Route::get('/verify',  [OtpController::class, 'showVerifyForm'])
     ->name('otp.verify.form');
Route::post('/verify', [OtpController::class, 'verify'])
     ->name('otp.verify.submit');

Route::get('/manage-hospitals', [HospitalController::class, 'manage'])->name('hospital.index');
Route::get('/manage-hospitals/create', [HospitalController::class, 'create'])->name('hospital.create');
Route::post('/manage-hospitals', [HospitalController::class, 'store'])->name('hospital.store');
Route::get('/manage-hospitals/{id}/edit', [HospitalController::class, 'edit'])->name('hospital.edit');
Route::put('/manage-hospitals/{id}', [HospitalController::class, 'update'])->name('hospital.update');
Route::delete('/manage-hospitals/{id}', [HospitalController::class, 'destroy'])->name('hospital.destroy');
 
Route::get('/radiographer/user-logs', [UserLogController::class, 'index'])->name('radiographer.user.logs');
Route::get('/user/{id}/profile', [UserLogController::class, 'show'])->name('user.profile'); // Optional