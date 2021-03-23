<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
        public function index()
    {

        $students = Student::all();
        return view('student.list', compact('students','students'));
    }

   
    public function create()
    {
        //
        return view('student.create');
    }

   
    public function store(Request $request)
    {
        //
        $request->validate([
            'txtFirstName'=>'required',
            'txtLastName'=> 'required',
            'txtAddress' => 'required'
        ]);

        $student = new Student([
            'first_name' => $request->get('txtFirstName'),
            'last_name'=> $request->get('txtLastName'),
            'address'=> $request->get('txtAddress')
        ]);

        $student->save();
        return redirect('/student')->with('success', 'Student has been added');
    }

    
    public function show(Student $student)
    {
        //
        return view('student.view',compact('student'));
    }

   
    public function edit(Student $student)
    {
        //
        return view('student.edit',compact('student'));
    }

    
    public function update(Request $request,$id)
    {
        //

        $request->validate([
            'txtFirstName'=>'required',
            'txtLastName'=> 'required',
            'txtAddress' => 'required'
        ]);


        $student = Student::find($id);
        $student->first_name = $request->get('txtFirstName');
        $student->last_name = $request->get('txtLastName');
        $student->address = $request->get('txtAddress');

        $student->update();

        return redirect('/student')->with('success', 'Student updated successfully');
    }
    
    public function destroy(Student $student)
    {
        //
        $student->delete();
        return redirect('/student')->with('success', 'Student deleted successfully');
    }
}
?>