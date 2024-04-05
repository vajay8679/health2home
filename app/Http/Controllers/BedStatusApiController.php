<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BedStatus;
use Illuminate\Support\Facades\DB;
use Validator;

class BedStatusApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function allBedsStatus()
    {
        //
        $bedStatus = DB::table('bed_statuses')
        ->select('beds.*','hospitals.*','bed_statuses.*')
            ->leftJoin('hospitals', 'hospitals.id', '=', 'bed_statuses.hospital_id')
            ->leftJoin('beds', 'beds.id', '=', 'bed_statuses.bed_id')
            
            // ->where('beds.hospital_id',$input['id'])
            ->orderBy('beds.created_at', 'desc')
            ->get();
            
        if ($bedStatus) {
            return response()->json([
                "result" => $bedStatus,
                "message" => 'Success',
                "status" => 1
            ]);
        } else {
            return response()->json([
                "message" => 'Sorry, something went wrong !',
                "status" => 0
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'bed_id' => 'required|numeric',
            'hospital_id' => 'required|numeric',
            'status' => 'required',
            
        ]);

        if ($validator->fails()) {
            // return $this->sendError($validator->errors());
            // return response()->json([
            //     "message" => $validator->errors(),
            //     "status" => 404
            // ]);
            return response()->json(['message' => $validator->errors()], '404');
        }

        
        // $input['status'] = 1;
        

        $bedStatus = BedStatus::create($input);
        $cus = BedStatus::where('id',$bedStatus->id)->first();

        if (is_object($cus)) {
          
            return response()->json([
                "result" => $cus,
                "message" => 'Add New Successfully',
                "status" => 1
            ]);
        } else {
            return response()->json([
                "message" => 'Sorry, something went wrong !',
                "status" => 0
            ]);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
