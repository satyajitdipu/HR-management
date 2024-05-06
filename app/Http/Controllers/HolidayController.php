<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Holiday;
use DB;

class HolidayController extends Controller
{
    /** holidays page */
    public function holiday()
    {
        $holiday = Holiday::all();
        return view('employees.holidays',compact('holiday'));
    }
    public function DeleteRecord(Request $request)
{
    try {
        // Validate the request
        $request->validate([
            'holiday_id' => 'required|integer',
        ]);

        // Find the holiday
        $holiday = Holiday::find($request->holiday_id);

        if (!$holiday) {
            Toastr::error('Holiday not found.', 'Error');
            return redirect()->back();
        }

        // Delete the holiday
        $holiday->delete();

        Toastr::success('Holiday deleted successfully.', 'Success');
        return redirect()->back();
    } catch (\Exception $e) {
        Toastr::error('Failed to delete holiday.', 'Error');
        return redirect()->back();
    }
}
    /** save record */
    public function saveRecord(Request $request)
    {
        try {
            $request->validate([
                'nameHoliday' => 'required|string|max:255',
                'holidayDate' => 'required|date|after_or_equal:today',
            ]);
    
            DB::beginTransaction();
    
            $holiday = new Holiday;
            $holiday->name_holiday = $request->nameHoliday;
            $holiday->date_holiday  = $request->holidayDate;
            $holiday->save();
    
            DB::commit();
            Toastr::success('Create new holiday successfully :)', 'Success');
            return redirect()->back();
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            Toastr::error('Validation error: ' . $e->getMessage(), 'Error');
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Add Holiday fail :)', 'Error');
            return redirect()->back();
        }
    }
    
    /** update record */
    public function updateRecord( Request $request)
    {
        DB::beginTransaction();
        try{
            $id           = $request->id;
            $holidayName  = $request->holidayName;
            $holidayDate  = $request->holidayDate;

            $update = [

                'id'           => $id,
                'name_holiday' => $holidayName,
                'date_holiday' => $holidayDate,
            ];

            Holiday::where('id',$request->id)->update($update);
            DB::commit();
            Toastr::success('Holiday updated successfully :)','Success');
            return redirect()->back();

        }catch(\Exception $e){
            DB::rollback();
            Toastr::error('Holiday update fail :)','Error');
            return redirect()->back();
        }
    }
   
}
