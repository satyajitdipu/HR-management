<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use Illuminate\Http\Request;
use DB;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Training;
use Illuminate\Support\Facades\Validator;
use Session;
use Auth;

class TrainingController extends Controller
{
    // index page
    public function index()
    {
        $training = DB::table('trainings')
                ->join('users', 'users.user_id', '=', 'trainings.trainer_id')
                ->select('trainings.*', 'users.avatar','users.user_id')
                ->get();
        $user = DB::table('users')->get();
        return view('training.traininglist',compact('user','training'));
    }
    // save record training
    public function addNewTraining(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'training_type'   => 'required|string|max:255',
            'trainer'         => 'required|string|max:255',
            'employees'       => 'required|string|max:255',
            'training_cost'   => 'required|string|max:255',
            'start_date'      => 'required|string|max:255',
            'end_date'        => 'required|string|max:255',
            'description'     => 'required|string|max:255',
            'status'          => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            Toastr::error('Add Training fail :)','Error');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::beginTransaction();
        try {

            $training = new Training;
            $training->trainer_id    = $request->trainer_id;
            $training->employees_id  = $request->employees_id;
            $training->training_type = $request->training_type;
            $training->trainer       = $request->trainer;
            $training->employees     = $request->employees;
            $training->training_cost = $request->training_cost;
            $training->start_date    = $request->start_date;
            $training->end_date      = $request->end_date;
            $training->description   = $request->description;
            $training->status        = $request->status;
            $training->save();
            
            DB::commit();
            Toastr::success('Create new Training successfully :)','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Add Training fail :)','Error');
            return redirect()->back();
        }
    }

    // delete record
    public function deleteTraining(Request $request)
    {
        try {

            Training::destroy($request->id);
            Toastr::success('Training deleted successfully :)','Success');
            return redirect()->back();
        
        } catch(\Exception $e) {

            DB::rollback();
            Toastr::error('Training delete fail :)','Error');
            return redirect()->back();
        }
    }
    // update record
    public function updateTraining(Request $request)
    {
        DB::beginTransaction();
        try {

            $update = [
                'id'            => $request->id,
                'trainer_id'    => $request->trainer_id,
                'employees_id'  => $request->employees_id,
                'training_type' => $request->training_type,
                'trainer'       => $request->trainer,
                'employees'     => $request->employees,
                'training_cost' => $request->training_cost,
                'start_date'    => $request->start_date,
                'end_date'      => $request->end_date,
                'description'   => $request->description,
                'status'        => $request->status,
            ];
            
            Training::where('id',$request->id)->update($update);
            DB::commit();
            Toastr::success('Updated Training successfully :)','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Update Training fail :)','Error');
            return redirect()->back();
        }
    }
    public function updateStatustrainer(Request $request){
        // dd($request->all());
        $trainner=Trainer::find($request->trainer_id);
        $trainner->status=$request->status;
        $trainner->save();
        Toastr::success('Updated Trainer status successfully :)','Success');
        return redirect()->back();
    }
}
