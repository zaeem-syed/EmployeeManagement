<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Admin\EmployeeResource;
use App\Models\Employee;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::all();
        return EmployeeResource::collection($employees);
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:employees,email',
        'phone' => 'nullable|string|max:20',
        'department' => 'required|string|max:255',
        'joining_date' => 'required|date',
    ]);

    $employee = Employee::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'] ?? null,
        'department' => $validated['department'],
        'joining_date' => $validated['joining_date'],
    ]);

    // Agar Spatie package use kar rahe ho
    // $employee->assignRole('employee');

    return new EmployeeResource($employee);
}

    public function update(Request $request, User $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'phone' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $employee->update($validated);

        return new EmployeeResource($employee->load('department'));
    }

    public function show(string $id)
    {
        $employee = User::role('employee')->with('department')->findOrFail($id);
        return new EmployeeResource($employee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $employee)
    {
        $employee->delete();
        return response()->json(['message' => 'Employee deleted']);
    }
}
