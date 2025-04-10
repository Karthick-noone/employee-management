<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::orderBy('id', 'desc')->paginate(10);
        return view('dashboard.user', compact('employees'));
    }

    public function admin(Request $request)
    {
        $employees = Employee::orderBy('id', 'desc')->paginate(5);

        $editEmployee = null;
        if ($request->has('edit')) {
            $editEmployee = Employee::findOrFail($request->edit);
        }

        return view('dashboard.admin', compact('employees', 'editEmployee'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|unique:employees,employee_id', //for unique employee id
            'name' => ['required', 'regex:/^[a-zA-Z\s]+$/'], // name should have only characters
            'email' => 'required|email:rfc,dns|unique:employees,email', // unique and proper email format
            'dob' => 'required|date',
            'doj' => 'required|date|after:dob',
        ], [
            'name.regex' => 'The name may only contain letters and spaces.',
            'email.email' => 'Enter a valid email address.',
        ]);
        
        
        Employee::create($request->only(['employee_id', 'name', 'email', 'dob', 'doj']));

        return redirect()->route('admin.dashboard')->with('success', 'Employee added successfully!');
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

       $request->validate([
    'employee_id' => 'required|unique:employees,employee_id,' . $employee->id,
    'name' => ['required', 'regex:/^[a-zA-Z\s]+$/'], // name should have only characters
    'email' => 'required|email:rfc,dns|unique:employees,email,' . $employee->id, // unique and proper email format
    'dob' => 'required|date',
    'doj' => 'required|date|after:dob',
], [
    'name.regex' => 'The name may only contain letters and spaces.',
    'email.email' => 'Enter a valid email address.',
]);


        $employee->update($request->only(['employee_id', 'name', 'email', 'dob', 'doj']));

        return redirect()->route('admin.dashboard')->with('success', 'Employee updated successfully!');
    }

    public function destroy($id)
    {
        Employee::findOrFail($id)->delete();
        return back();
    }
}
