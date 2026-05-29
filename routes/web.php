<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CompanyInfoController as AdminCompanyInfoController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BiuletynController;
use App\Http\Controllers\CompanyInfoController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Manager\ManagerContactController;
use App\Http\Controllers\Manager\ManagerDashboardController;
use App\Http\Controllers\Manager\ManagerDocumentController;
use App\Http\Controllers\Manager\ScheduleController;
use App\Http\Controllers\Manager\WorkLogController;
use App\Http\Controllers\UserWorkLogController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();

        // Najpierw sprawdzamy najwyższe możliwe uprawnienie
        if ($user->hasRole('administrator')) {
            return redirect()->route('admin.dashboard');
        }

        // Jeśli nie jest adminem, ale ma kolejną w hierarchii rolę
        if ($user->hasRole('kierownik')) {
            return redirect()->route('manager.dashboard');
        }

        // Jeśli nie ma żadnej z powyższych ról, ładujemy panel pracownika
        $schedules = $user->schedules()->orderBy('day_of_week')->get();

        return view('dashboard', compact('schedules'));
    })->name('dashboard');

    // User Work Logs
    Route::get('/work-logs', [UserWorkLogController::class, 'index'])->name('user.work-logs.index');
    Route::post('/work-logs', [UserWorkLogController::class, 'store'])->name('user.work-logs.store');
    Route::post('/work-logs/{workLog}/comment', [UserWorkLogController::class, 'comment'])->name('user.work-logs.comment');

    Route::middleware(['role:administrator'])->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        Route::resource('admin/users', UserController::class)->names([
            'index' => 'admin.users.index',
            'store' => 'admin.users.store',
            'update' => 'admin.users.update',
            'destroy' => 'admin.users.destroy',
            'create' => 'admin.users.create',
            'edit' => 'admin.users.edit',
            'show' => 'admin.users.show',
        ]);

        // Company Info (Admin)
        Route::get('/admin/company', [AdminCompanyInfoController::class, 'index'])->name('admin.company.index');
        Route::post('/admin/company', [AdminCompanyInfoController::class, 'update'])->name('admin.company.update');
    });

    // Public Company Info
    Route::get('/company', [CompanyInfoController::class, 'index'])->name('company.show');

    Route::middleware(['role:administrator,kierownik'])->group(function () {
        Route::get('/manager/dashboard', [ManagerDashboardController::class, 'index'])->name('manager.dashboard');

        // Manager Work Logs
        Route::get('/manager/work-logs', [WorkLogController::class, 'index'])->name('manager.work-logs.index');
        Route::post('/manager/work-logs', [WorkLogController::class, 'store'])->name('manager.work-logs.store');
        Route::post('/manager/work-logs/{workLog}/comment', [WorkLogController::class, 'comment'])->name('manager.work-logs.comment');

        // Manager Schedules
        Route::get('/manager/schedules', [ScheduleController::class, 'index'])->name('manager.schedules.index');
        Route::post('/manager/schedules', [ScheduleController::class, 'store'])->name('manager.schedules.store');
    });

    Route::resource('biuletyn', BiuletynController::class)->names([
        'index' => 'biuletyn.index',
        'create' => 'biuletyn.create',
        'store' => 'biuletyn.store',
        'show' => 'biuletyn.show',
        'edit' => 'biuletyn.edit',
        'update' => 'biuletyn.update',
        'destroy' => 'biuletyn.destroy',
    ]);

    // Contact System (User)
    Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
    Route::get('/contact/create', [ContactController::class, 'create'])->name('contact.create');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    Route::get('/contact/{thread}', [ContactController::class, 'show'])->name('contact.show');
    Route::post('/contact/{thread}/reply', [ContactController::class, 'reply'])->name('contact.reply');

    // Contact System (Manager/Admin)
    Route::middleware(['role:administrator,kierownik'])->group(function () {
        Route::get('/manager/contact', [ManagerContactController::class, 'index'])->name('manager.contact.index');
        Route::post('/manager/contact', [ManagerContactController::class, 'store'])->name('manager.contact.store');
        Route::get('/manager/contact/{thread}', [ManagerContactController::class, 'show'])->name('manager.contact.show');
        Route::post('/manager/contact/{thread}/reply', [ManagerContactController::class, 'reply'])->name('manager.contact.reply');
        Route::post('/manager/contact/mark-all-read', [ManagerContactController::class, 'markAllRead'])->name('manager.contact.mark-all-read');
        Route::post('/manager/contact/{thread}/open', [ManagerContactController::class, 'open'])->name('manager.contact.open')->middleware('role:administrator');
        Route::post('/manager/contact/{thread}/close', [ManagerContactController::class, 'close'])->name('manager.contact.close');
        Route::delete('/manager/contact/{thread}', [ManagerContactController::class, 'destroy'])->name('manager.contact.destroy');
    });

    // Document Library (User)
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

    // Document Library (Manager/Admin)
    Route::middleware(['role:administrator,kierownik'])->group(function () {
        Route::get('/manager/documents', [ManagerDocumentController::class, 'index'])->name('manager.documents.index');
        Route::post('/manager/documents', [ManagerDocumentController::class, 'store'])->name('manager.documents.store');
        Route::delete('/manager/documents/{document}', [ManagerDocumentController::class, 'destroy'])->name('manager.documents.destroy');
    });
});
