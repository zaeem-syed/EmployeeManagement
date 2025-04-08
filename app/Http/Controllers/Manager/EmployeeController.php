<?php

namespace App\Http\Controllers\Manager;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Manager\EmployeeResource;

class EmployeeController extends Controller
{
    public function index()
    {
        $departmentId = Auth::user()->department_id;

        $employees = User::role('employee')
            ->where('department_id', $departmentId)
            ->get();

        return EmployeeResource::collection($employees);
    }

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

        return new EmployeeResource($employee);
    }
}
