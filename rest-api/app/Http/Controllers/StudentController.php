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
            $students = Student::all();
    
            if (count($students) > 0) {
                $result = [
                    "message" => "Get All Users",
                    "data" => $students
                ];
            }else{
                $result = [
                    'message' => "data not found"
                ];
            }
    
            return response()->json($result,200);
            
        } catch (\Throwable $th) {
           return response()->json(["message"=>"error","error"=>$th],500);
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
    
            return response()->json($data,2001);
        } catch (\Throwable $th) {
            return response()->json(["message"=>"error",'error'=>$th],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {
        try {
            $data = Student::find($id);
            if ($data) {
                $result = [
                    'message' => "success get data",
                    'data' => $data
                ];
            }else{
                $result = [
                    'message' => 'failed to get data',
                    'data' => 'data not found'
                ];
            }
            return response()->json($result,200);
        } catch (\Throwable $th) {
            return response()->json(["message"=>"error","error"=>$th],500);
        }

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
        try {
            $id = (int) $id;
            $student = Student::find($id);
            $input = [
                'nama' => $request->nama ?? $student->nama,
                'nim' => $request->nim ?? $student->nim,
                'email' => $request->email ?? $student->email,
                'jurusan' => $request->jurusan ?? $student->jurusan
            ];
            if ($student) {  
                $student->update($input);
                $result = [
                    'message' => "Student is updated successfully",
                    'data' => $student
                ];
            }else{
                $result = [
                    'message' => 'failed to updated student',
                    'data' => 'data not found'
                ];
            }
            return response()->json($result,200);
        } catch (\Throwable $th) {
            return response()->json(["message"=>"error","error"=>$th],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $id = (int) $id;
            $student = Student::find($id);
            if ($student) {
                $student->delete();
                $result = [
                    'message' => "error",
                    'data' => "student is not found"
                ];
            }else{
                
                $result = [
                    'message' => "deleted Student issss successfully",
                    'data' => $student
                ];
            };
            
            return response()->json($result,200);
            
        } catch (\Throwable $th) {
            return response()->json(["message"=>"error","error"=>$th],500);
        }

    }
}
