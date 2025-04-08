<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::apiResource('departments', App\Http\Controllers\Admin\DepartmentController::class);
    Route::apiResource('employees', App\Http\Controllers\Admin\EmployeeController::class);

    // Custom route for role assignment
    Route::post('assign-role', [App\Http\Controllers\Admin\RoleController::class, 'assignRole']);
});
Route::middleware(['auth:sanctum', 'role:manager'])->prefix('manager')->group(function () {
    Route::get('employees', [App\Http\Controllers\Manager\EmployeeController::class, 'index']);
    Route::put('employees/{employee}', [App\Http\Controllers\Manager\EmployeeController::class, 'update']);
});

Route::middleware(['auth:sanctum', 'role:employee'])->prefix('employee')->group(function () {
    Route::get('profile', [App\Http\Controllers\Employee\ProfileController::class, 'show']);
});

Route::post('login', [AuthController::class, 'login']);
