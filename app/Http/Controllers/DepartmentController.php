<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    public function index()
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $departments = Department::orderBy('name')->get();

        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10', 'unique:departments,code'],
            'description' => ['nullable', 'string', 'max:500'],
            'average_consultation_time' => ['required', 'integer', 'min:1'],
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    public function edit(Department $department)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:10', 'unique:departments,code,' . $department->id],
            'description' => ['nullable', 'string', 'max:500'],
            'average_consultation_time' => ['required', 'integer', 'min:1'],
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        abort_if(Auth::user()->role !== 'admin', 403);

        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }
}
