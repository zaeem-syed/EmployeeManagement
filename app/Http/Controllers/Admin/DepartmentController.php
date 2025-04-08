<?php

namespace App\Http\Controllers\Admin;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentResource;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    return DepartmentResource::collection(Department::all());
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string',
        'code' => 'required|string|unique:departments',
        'description' => 'nullable|string',
    ]);

    $department = Department::create($validated);
    return new DepartmentResource($department);
}

public function update(Request $request, Department $department)
{
    $validated = $request->validate([
        'name' => 'required|string',
        'code' => 'required|string|unique:departments,code,' . $department->id,
        'description' => 'nullable|string',
    ]);
    $department->update($validated);

    return new DepartmentResource($department);
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        //
        $department->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
