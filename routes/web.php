<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Portal\DoctorController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('employee.login'));

/*
|--------------------------------------------------------------------------
| Employee Auth
|--------------------------------------------------------------------------
*/
Route::prefix('employee')->name('employee.')->group(function () {
    Route::get('/login',  [EmployeeController::class, 'login'])->name('login');
    Route::post('/login', [EmployeeController::class, 'doLogin'])->name('doLogin');
    Route::post('/logout',[EmployeeController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Import
|--------------------------------------------------------------------------
*/
Route::get('import', [EmployeeController::class, 'import']);
Route::post('/employees/import', [EmployeeController::class, 'importEmployees'])->name('employees.import');
Route::post('/admin/doctors/import', [EmployeeController::class, 'doctorImport'])->name('admin.doctors.import');
/*
|--------------------------------------------------------------------------
| Portal (Employee-facing, after login)
|--------------------------------------------------------------------------
*/
Route::prefix('portal')->name('portal.')->group(function () {

    Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');

    Route::prefix('doctors')->name('doctors.')->group(function () {
        Route::get('/',               [DoctorController::class, 'index'])->name('index');
        Route::get('/listing',        [DoctorController::class, 'listing'])->name('listing');
        Route::get('/export',         [DoctorController::class, 'export'])->name('export');
        Route::get('/create',         [DoctorController::class, 'create'])->name('create');
        Route::post('/store',         [DoctorController::class, 'store'])->name('store');
        Route::get('/{id}/edit',      [DoctorController::class, 'edit'])->name('edit');
        Route::post('/{id}/update', [DoctorController::class, 'update'])
            ->name('update');
        Route::post('/{id}/delete',   [DoctorController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/edit-data', [DoctorController::class, 'editData'])->name('editData');
    });

});

/*
|--------------------------------------------------------------------------
| Admin Panel
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // Auth
    Route::get('/login',  [AdminController::class, 'loginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'doLogin'])->name('doLogin');
    Route::post('/logout',[AdminController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // ── Employees ──────────────────────────────────────────────────────────
    Route::prefix('employees')->name('employees.')->group(function () {
        Route::get('/',                    [AdminController::class, 'employeeIndex'])->name('index');
        Route::get('/listing',             [AdminController::class, 'employeeListing'])->name('listing');
        Route::post('/store',              [AdminController::class, 'employeeStore'])->name('store');
        Route::get('/{id}/edit',           [AdminController::class, 'employeeEdit'])->name('edit');
        Route::post('/{id}/update',        [AdminController::class, 'employeeUpdate'])->name('update');
        Route::post('/{id}/toggle-status', [AdminController::class, 'employeeToggleStatus'])->name('toggleStatus');
        Route::post('/{id}/delete',        [AdminController::class, 'employeeDestroy'])->name('destroy');
    });

    // ── Doctors ────────────────────────────────────────────────────────────
    Route::prefix('doctors')->name('doctors.')->group(function () {
        Route::get('/',        [AdminController::class, 'doctorIndex'])->name('index');
        Route::get('/listing', [AdminController::class, 'doctorListing'])->name('listing');
    });

    // ── Report ─────────────────────────────────────────────────────────────
    Route::get('/report', [AdminController::class, 'Admin_report'])->name('report');

});
Route::get('/admin/export-doctors', [AdminController::class, 'exportDoctors'])->name('admin.export.doctors');
Route::get('/admin/export-employees', [AdminController::class, 'exportEmployees'])->name('admin.export.employees');
Route::post('/admin/doctors/delete/{id}', [AdminController::class, 'doctorDestroy'])
    ->name('admin.doctors.delete');
Route::get('/admin/report/export', [AdminController::class, 'exportReport'])->name('admin.report.export');
