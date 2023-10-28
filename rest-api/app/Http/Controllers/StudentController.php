<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $student = Student::all();
    
            $data = [
                "message" => "Get All Users",
                "data" => $student
            ];
    
            return response()->json($data,200);
            
        } catch (\Throwable $th) {
           return response()->json(["message"=>"error","error"=>$th]);
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $input = [
                'nama' => $request->nama,
                'nim' => $request->nim,
                'email' => $request->email,
                'jurusan' => $request->jurusan
            ];
    
            $students = Student::create($input);
    
            $data = [
                'message' => 'Student is created successfully',
                'data' => $students
            ];
    
            return response()->json($data,201);
        } catch (\Throwable $th) {
            return response()->json(["message"=>"error"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = (int) $id;
        $input = [
            'nama' => $request->nama,
            'nim' => $request->nim,
            'email' => $request->email,
            'jurusan' => $request->jurusan
        ];
        $student = Student::find($id)->update($input);

        $data = [
            'message' => "Student is updated successfully",
            'data' => $student
        ];

        return response()->json($data,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = (int) $id;
        $student = Student::destroy($id);

        $data = [
            'message' => "Student is deleted successfully",
            'data' => $student
        ];

        return response()->json($data,200);
    }
}
