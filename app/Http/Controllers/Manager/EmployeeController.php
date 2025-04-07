<?php

namespace App\Http\Controllers\Manager;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function index()
    {
        $departmentId = Auth::user()->department_id;

        $employees = User::role('employee')
            ->where('department_id', $departmentId)
            ->get();

        return response()->json($employees);
    }

    // Update employee in same department only
    public function update(Request $request, User $employee)
    {
        $managerDepartment = Auth::user()->department_id;

        if ($employee->department_id !== $managerDepartment) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'phone' => 'nullable|string',
        ]);

        $employee->update($validated);

        return response()->json($employee);
    }
}
