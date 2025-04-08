<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
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
        $employees = User::role('employee')->with('department')->get();
        return EmployeeResource::collection($employees);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string',
            'password' => 'required|min:6',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'department_id' => $validated['department_id'] ?? null,
        ]);

        $user->assignRole('employee');

        return new EmployeeResource($user->load('department'));
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
