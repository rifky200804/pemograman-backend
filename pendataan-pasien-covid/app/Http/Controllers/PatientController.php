<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\StatusPatient;
use Illuminate\Http\Request;
use Validator;


class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/api/patients",
     *     operationId="getPatients",
     *     tags={"Patients"},
     *     summary="Get list of patients",
     *     description="Returns a list of patients with optional filtering and pagination.",
     *     security={
     *      {"sanctum": {}}
     *     },
     *     @OA\Parameter(
     *         name="filter[name]",
     *         in="query",
     *         description="Filter by patient name",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="filter[address]",
     *         in="query",
     *         description="Filter by patient address",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="filter[status]",
     *         in="query",
     *         description="Filter by patient status",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="filter[sort]",
     *         in="query",
     *         description="Sort the result by a specific field",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="filter[order]",
     *         in="query",
     *         description="Sort order (asc or desc)",
     *         @OA\Schema(type="string", enum={"asc", "desc"})
     *     ),
     *     @OA\Parameter(
     *         name="page[limit]",
     *         in="query",
     *         description="Number of items per page",
     *         @OA\Schema(type="integer", format="int32")
     *     ),
     *     @OA\Parameter(
     *         name="page[number]",
     *         in="query",
     *         description="Page number",
     *         @OA\Schema(type="integer", format="int32")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="error"),
     *         ),
     *     ),
     * )
     */
    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'filter.status' => 'nullable|in:positif,negatif,meninggal'
            ],[
                'filter.status.in' => 'The status field must be one of: positif, negatif, meninggal.'
            ]);

            if($validator->fails()){
                // var_dump($validator->fails());
                foreach ($validator->errors()->messages() as $key => $value) {
                    return response()->json(["message"=>"failed Get Data","error"=>$value[0]],400);       
                }
            }

            // untuk filter menggunakan 
            // filter[nama]
            // filter[sort]
            // filter[order]

            // untuk paging menggunakan
            // page[limit]
            // page[number]
            
            foreach ($request->all() as $key => $value) {
                $$key = $value;
            }

            $order = (isset($filter['order'])) ? $filter['order'] : NULL;
            if ($order == NULL) {
                $order = 'asc';
            }
            $sort = (isset($filter['sort'])) ? $filter['sort'] : NULL;
            if ($sort == NULL) {
                $sort = 'in_date_at';
            }

            $pageLimit = (isset($page['limit'])) ? $page['limit'] : 5;
            $pageNumber = (isset($page['number'])) ? $page['number'] : 1;
            $offset = ($pageNumber - 1) * $pageLimit;
            $pages = [];
            $pages['pageLimit'] = (int) $pageLimit;
            $pages['pageNumber'] = (int) $pageNumber;

            // get data
            $patients = Patient::leftJoin('status_patients','patients.status_id','=','status_patients.id');
            $name = (isset($filter['name'])) ? $filter['name'] : NULL;
            if ($name != NULL) {
                $patients = $patients->where('name','LIKE','%'.$name.'%');
            }

            $address = (isset($filter['address'])) ? $filter['address'] : NULL;
            if ($address != NULL) {
                $patients = $patients->where('address','LIKE','%'.$address.'%');
            }

            $status = (isset($filter['status'])) ? $filter['status'] : NULL;
            if ($status != NULL) {
                $patients = $patients->where('status_patients.status','LIKE','%'.$status.'%');
            }

            $patients = $patients->orderBy($sort,$order)->offset($offset)
                        ->limit($pageLimit)->get();
            // var_dump(count($patients));
            // die;
            // get total
            $patientTotal = Patient::leftJoin('status_patients','patients.status_id','=','status_patients.id');
            if ($name != NULL) {
                $patientTotal = $patientTotal->where('name','LIKE','%'.$name.'%');
            }

            if ($address != NULL) {
                $patientTotal = $patientTotal->where('address','LIKE','%'.$address.'%');
            }

            if ($status != NULL) {
                $patientTotal = $patientTotal->where('status_patients.status','LIKE','%'.$status.'%');
            }

            $pageLimit = (isset($page['limit'])) ? $page['limit'] : 5;
            $pageNumber = (isset($page['number'])) ? $page['number'] : 1;
            $offset = ($pageNumber - 1) * $pageLimit;
            
            $patientTotal = $patientTotal->count();;
            
            $pages['totalData'] = $patientTotal;
            $totalPage = ceil($patientTotal / $pageLimit);
            $pages['totalPage'] = $totalPage;
            
            $data = [];
            $data['pages'] = $pages;
            $data['table'] = $patients;
            
            if ($patientTotal > 0) {
                $result = [
                    "message" => "Success Get All Data Patient",
                    "data" => $data
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

     /**
     * @OA\Post(
     *      path="/api/patients",
     *      operationId="storePatient",
     *      tags={"Patients"},
     *      summary="Create a new patient",
     *      description="Create a new patient with the provided information",
     *      security={
     *      {"sanctum": {}}
     *     },
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="phone", type="string", example="123456789"),
     *              @OA\Property(property="address", type="string", example="123 Main St"),
     *              @OA\Property(property="status", type="string", example="positif/negatif/meninggal"),
     *              @OA\Property(property="in_date_at", type="string", format="date", example="2023-01-01"),
     *              @OA\Property(property="out_date_at", type="string", format="date", example="2023-01-02"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Patient created successfully",
     *      )
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(Request $request)
    {
         try {
            $validator = Validator::make($request->all(),[
                'name' => "required|max:200",
                'phone' => "required|min:10",
                'address' => "required",
                'status' => "required|in:positif,negatif,meninggal",
                'in_date_at'=> "nullable|date",
                'out_date_at'=> "nullable|date",
            ],[
                'status.in' => 'The status field must be one of: positif, negatif, meninggal.'
            ]);

            if($validator->fails()){
                // var_dump($validator->fails());
                foreach ($validator->errors()->messages() as $key => $value) {
                    return response()->json(["message"=>"failed Added Data","error"=>$value[0]],400);       
                }
            }

            if (!isset($request->in_date_at)) {
                $in_date_at = date('Y-m-d');
            }else{
                $in_date_at = new \DateTime($request->in_date_at);
                $in_date_at = $in_date_at->format('Y-m-d');
            }

            if (!isset($request->out_date_at)) {
                $out_date_at = NULL;
            }else{
                $out_date_at = new \DateTime($request->out_date_at);
                $out_date_at = $out_date_at->format('Y-m-d');
            }


            $inputStatusPatient = [
                'status' => $request->status,
                'in_date_at' => $in_date_at,
                'out_date_at'=> $out_date_at
            ];

            // var_dump($inputStatusPatient);
            // die;
    
            $statusPatient = StatusPatient::create($inputStatusPatient);

            $inputPatient = [
                'name'=> $request->name,
                'phone'=> $request->phone,
                'address'=> $request->address,
                'status_id'=> $statusPatient->id
            ];
            $patient = Patient::create($inputPatient);
    
            $data = [
                'message' => 'Patient is created successfully'
            ];
    
            return response()->json($data,201);
        } catch (\Throwable $th) {
            return response()->json(["message"=>"error","error"=>$th],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *      path="/api/patients/{id}",
     *      operationId="showDataPatient",
     *      tags={"Patients"},
     *      summary="Update a patient by ID",
     *      description="Update an existing patient with the provided information",
     *      security={
     *          {"sanctum": {}}
     *      },
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the patient to be Show Data",
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *              example=1
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successfully Get Data Patient",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Patient not found",
     *      )
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $findDataPatient = Patient::leftJoin('status_patients','patients.status_id','=','status_patients.id')->where('patients.id',$id)->first();
            if ($findDataPatient == NULL) {
                return response()->json(['message'=>'data not found'],404);
            }

            $data = [
                'message'=>'success get Data',
                'data'=>$findDataPatient
            ];
            return response()->json($data,200);
        } catch (\Throwable $th) {
            return response()->json(["message"=>"error","error"=>$th],500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Put(
     *      path="/api/patients/{id}",
     *      operationId="updatePatient",
     *      tags={"Patients"},
     *      summary="Update a patient by ID",
     *      description="Update an existing patient with the provided information",
     *      security={
     *          {"sanctum": {}}
     *      },
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the patient to be updated",
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *              example=1
     *          )
     *      ),
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="phone", type="string", example="123456789"),
     *              @OA\Property(property="address", type="string", example="123 Main St"),
     *              @OA\Property(property="status", type="string", example="positif/negatif/meninggal"),
     *              @OA\Property(property="in_date_at", type="string", format="date", example="2023-01-01"),
     *              @OA\Property(property="out_date_at", type="string", format="date", example="2023-01-02"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Patient updated successfully",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Patient not found",
     *      )
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'name' => 'max:200',
                'phone' => 'min:10',
                'status' => 'nullable|in:positif,negatif,meninggal'
            ],[
                'status.in' => 'The status field must be one of: positif, negatif, meninggal.'
            ]);

            if($validator->fails()){
                // var_dump($validator->fails());
                foreach ($validator->errors()->messages() as $key => $value) {
                    return response()->json(["message"=>"failed Added Data","error"=>$value[0]],400);       
                }
            }

            $findDataPatient = Patient::find($id);
            if ($findDataPatient == null) {
                return response()->json(['message'=>'data not found'],404);
            }
            $findDataStatusPatient = StatusPatient::find($findDataPatient->id);

            if (!isset($request->in_date_at)) {
                $in_date_at = $findDataStatusPatient->in_date_at;
            }else{
                $in_date_at = new \DateTime($request->in_date_at);
                $in_date_at = $in_date_at->format('Y-m-d');
            }

            if (!isset($request->out_date_at)) {
                $out_date_at = $findDataStatusPatient->out_date;
            }else{
                $out_date_at = new \DateTime($request->out_date_at);
                $out_date_at = $out_date_at->format('Y-m-d');
            }


            $inputStatusPatient = [
                'status' => (isset($request->status)) ? $request->status : $findDataStatusPatient->status,
                'in_date_at' => $in_date_at,
                'out_date_at'=> $out_date_at
            ];

    
            $statusPatient = StatusPatient::where('id',$findDataStatusPatient->status_id)->update($inputStatusPatient);

            $inputPatient = [
                'name'=> (isset($request->name)) ? $request->name : $findDataPatient->name,
                'phone'=> (isset($request->phone)) ? $request->phone : $findDataPatient->phone,
                'address'=> (isset($request->address)) ? $request->address : $findDataPatient->address,
                'status_id'=> $findDataPatient->status_id
            ];
            $patient = Patient::where('id',$findDataPatient->id)->update($inputPatient);
    
            $data = [
                'message' => 'Patient is updated successfully'
            ];
    
            return response()->json($data,201);
        } catch (\Throwable $th) {
            return response()->json(["message"=>"error","error"=>$th],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Delete(
     *      path="/api/patients/{id}",
     *      operationId="DeletePatient",
     *      tags={"Patients"},
     *      summary="Update a patient by ID",
     *      description="Update an existing patient with the provided information",
     *      security={
     *          {"sanctum": {}}
     *      },
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the patient to be Deleted",
     *          @OA\Schema(
     *              type="integer",
     *              format="int64",
     *              example=1
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successfully Get Data Patient",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Patient not found",
     *      )
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $findData = Patient::find($id);
            if ($findData == NULL) {
                return response()->json(["message"=>"data not found"],404);
            }
            StatusPatient::where('id',$findData->status_id)->delete();
            Patient::where('id',$findData->id)->delete();
            
            $data = [
                'message' => 'Patient is deleted successfully'
            ];
    
            return response()->json($data,200);
        } catch (\Throwable $th) {
            return response()->json(["message"=>"error","error"=>$th],500);
        }
    }
}
