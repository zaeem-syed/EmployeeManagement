<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Admin\EmployeeResource;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Fetching employees with department details
    $employees = Employee::with('department')->get();

    // Fetching all departments
    $departments = Department::all();

    // Transform the employee data using EmployeeResource and departments in a separate format
    return response()->json([
        'employees' => EmployeeResource::collection($employees),
        'departments' => $departments->map(function ($department) {
            return [
                'id' => $department->id,
                'name' => $department->name,
            ];
        }),
    ]);
}
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:employees,email',
        'phone' => 'nullable|string|max:20',
        'department_id' => 'required|exists:departments,id',  // Validation for department_id
        'joining_date' => 'required|date',
    ]);

    // Create employee with department_id instead of department name
    $employee = Employee::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'] ?? null,
        'department_id' => $validated['department_id'], // Save department as ID
        'joining_date' => $validated['joining_date'],
    ]);

    // If using Spatie for roles
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
