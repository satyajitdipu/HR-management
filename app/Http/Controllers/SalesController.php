<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Payment;
use App\Models\Project;
use App\Models\User;
use Auth;
use DB;
use App\Models\Expense;
use App\Models\Estimates;
use App\Models\EstimatesAdd;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class SalesController extends Controller
{
    /** page estimates */
    public function estimatesIndex()
    {
        $estimates = DB::table('estimates')->get();
        $estimatesJoin = DB::table('estimates')
            ->join('estimates_adds', 'estimates.estimate_number', 'estimates_adds.estimate_number')
            ->select('estimates.*', 'estimates_adds.*')->get();
        return view('sales.estimates', compact('estimates', 'estimatesJoin'));
    }

    /** page create estimates */
    public function createEstimateIndex()
    {
       $client=Client::pluck('name','id');
       $project=Project::pluck('name','id');
        return view('sales.createestimate', compact('client','project'));
    }

    /** page edit estimates */
    public function editEstimateIndex($estimate_number)
    {
        $estimates = DB::table('estimates')->where('estimate_number', $estimate_number)->first();
        $estimatesJoin = DB::table('estimates')
            ->join('estimates_adds', 'estimates.estimate_number', 'estimates_adds.estimate_number')
            ->select('estimates.*', 'estimates_adds.*')->where('estimates_adds.estimate_number', $estimate_number)->get();
        return view('sales.editestimate', compact('estimates', 'estimatesJoin'));
    }

    /** view page estimate */
    public function viewEstimateIndex($estimate_number)
    {
        
        $estimatesJoin = DB::table('estimates')
            ->join('estimates_adds', 'estimates.estimate_number', 'estimates_adds.estimate_number')
            ->select('estimates.*', 'estimates_adds.*')->where('estimates_adds.estimate_number', $estimate_number)->get();
        return view('sales.estimateview', compact('estimatesJoin'));
    }

    /** save record create estimate */
    public function createEstimateSaveRecord(Request $request)
    {
        // dd($request->all());
        $client = Client::where('name', $request->client)->first();
        // dd($client->id);
        $user = Auth::user();
        $request->validate([
            'client' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            if($request->email==null){
                Toastr::error('plese enter value', 'Error');
            return redirect()->back();
            }
            $project = null;
            if($request->project == 'other') {
                $project = new Project;
                $project->name = $request->other_project;
                $project->client_id = $request->client; // Use the retrieved client ID
                $project->employee_id = $user->id;
                $project->price = $request->total;
                $project->save();
        
                $client=Client::find($request->client);
                $client->total_project = $client->total_project + 1;
                $client->save();
            }
        
            $estimates = new Estimates;
            $estimates->client = $request->client;
            $estimates->project = $project ? $project->id : $request->project; // Use the new project ID if it was created
            $estimates->email = $request->email;
            $estimates->tax = $request->tax;
            $estimates->client_address = $request->client_address;
            $estimates->billing_address = $request->billing_address;
            $estimates->estimate_date = $request->estimate_date;
            $estimates->expiry_date = $request->expiry_date;
            $estimates->total = $request->total;
            $estimates->tax_1 = $request->tax_1;
            $estimates->discount = $request->discount;
            $estimates->grand_total = $request->grand_total;
            $estimates->other_information = $request->other_information;
            $estimates->save();
        
            $estimate_number = Estimates::orderBy('estimate_number', 'DESC')->pluck('estimate_number')->first();
        
            foreach ($request->item as $key => $items) {
                $estimatesAdd = [
                    'item' => $items,
                    'estimate_number' => $estimate_number,
                    'description' => $request->description[$key],
                    'unit_cost' => $request->unit_cost[$key],
                    'qty' => $request->qty[$key],
                    'amount' => $request->amount[$key],
                ];
        
                EstimatesAdd::create($estimatesAdd);
            }
        
            DB::commit();
            Toastr::success('Create new Estimates successfully :)', 'Success');
            return redirect()->route('form/estimates/page');
        } catch (\Exception $e) {
            DB::rollback();
            
            Toastr::error($e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function searchEstimateIndex(Request $request){
        $query = DB::table('estimates');

        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }
    
       
        $estimates = $query->get();
    
        $estimatesJoin = DB::table('estimates')
            ->join('estimates_adds', 'estimates.estimate_number', 'estimates_adds.estimate_number')
            ->select('estimates.*', 'estimates_adds.*')
            ->get();
    
        return view('sales.estimates', compact('estimates', 'estimatesJoin'));
    }
    /** update record estimate */
    public function EstimateUpdateRecord(Request $request)
    {
        DB::beginTransaction();
        try {
            $update = [
                'id' => $request->id,
                'client' => $request->client,
                'project' => $request->project,
                'email' => $request->email,
                'tax' => $request->tax,
                'client_address' => $request->client_address,
                'billing_address' => $request->billing_address,
                'estimate_date' => $request->estimate_date,
                'expiry_date' => $request->expiry_date,
                'total' => $request->total,
                'tax_1' => $request->tax_1,
                'discount' => $request->discount,
                'grand_total' => $request->grand_total,
                'other_information' => $request->other_information,
            ];
            Estimates::where('id', $request->id)->update($update);
            /** delete record */
            foreach ($request->estimates_adds as $key => $items) {
                DB::table('estimates_adds')->where('id', $request->estimates_adds[$key])->delete();
            }
            return redirect()->back();
            /** insert new record */
            foreach ($request->item as $key => $item) {
                $estimatesAdd['estimate_number'] = $request->estimate_number;
                $estimatesAdd['item'] = $request->item[$key];
                $estimatesAdd['description'] = $request->description[$key];
                $estimatesAdd['unit_cost'] = $request->unit_cost[$key];
                $estimatesAdd['qty'] = $request->qty[$key];
                $estimatesAdd['amount'] = $request->amount[$key];

                EstimatesAdd::create($estimatesAdd);
            }

            DB::commit();
            Toastr::success('Updated Estimates successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Update Estimates fail :)', 'Error');
            return redirect()->back();
        }
    }

    /** delete record estimate add */
    public function EstimateAddDeleteRecord(Request $request)
    {
        DB::beginTransaction();
        try {
            EstimatesAdd::destroy($request->id);
            DB::commit();
            Toastr::success('Estimates deleted successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Estimates deleted fail :)', 'Error');
            return redirect()->back();
        }
    }

    /** delete record estimate */
    public function updateExpense(Request $request){
        // dd($request->all());
        $estimate=Expense::find($request->id);
        $estimate->status=$request->status;
        $estimate->save();
        
        Toastr::success('Estimates Update successfully :)', 'Success');
            return redirect()->back();

    }
    public function EstimateDeleteRecord(Request $request)
    {
        DB::beginTransaction();
        try {

            /** delete record table estimates_adds */
            $estimate_number = DB::table('estimates_adds')->where('estimate_number', $request->estimate_number)->get();
            foreach ($estimate_number as $key => $id_estimate_number) {
                DB::table('estimates_adds')->where('id', $id_estimate_number->id)->delete();
            }

            /** delete record table estimates */
            Estimates::destroy($request->id);
            DB::commit();
            Toastr::success('Estimates deleted successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Estimates deleted fail :)', 'Error');
            return redirect()->back();
        }
    }

    /** view payments page */
    public function Payments()
    {
        $invoices=Payment::all();
        // dd($invoices);
        return view('sales.payments',compact('invoices'));
    }

    /** expenses page index */
    public function Expenses()
    {
        /** get data show data on table page expenses */
        $data = DB::table('expenses')->get();
        $employee=Employee::pluck('id','name');
        return view('sales.expenses', compact('data','employee'));
    }

    /** save record */
    public function saveRecord(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'purchase_from' => 'required|string|max:255',
            'purchase_date' => 'required|string|max:255',
            'purchased_by' => 'required|string|max:255',
            'amount' => 'required|string|max:255',
            'paid_by' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'attachments' => 'required',
        ]);

        DB::beginTransaction();
        try {

            $attachments = time() . '.' . $request->attachments->extension();
            $request->attachments->move(public_path('assets/images'), $attachments);

            $expense = new Expense;
            $expense->item_name = $request->item_name;
            $expense->purchase_from = $request->purchase_from;
            $expense->purchase_date = $request->purchase_date;
            $expense->purchased_by = $request->purchased_by;
            $expense->amount = $request->amount;
            $expense->paid_by = $request->paid_by;
            $expense->status = $request->status;
            $expense->attachments = $attachments;
            $expense->save();

            DB::commit();
            Toastr::success('Create new Expense successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Add Expense fail :)', 'Error');
            return redirect()->back();
        }
    }

    /** update record */
    public function updateRecord(Request $request)
    {
        DB::beginTransaction();
        try {

            $attachments = $request->hidden_attachments;
            $attachment = $request->file('attachments');
            if ($attachment != '') {
                unlink('assets/images/' . $attachments);
                $attachments = time() . '.' . $attachment->getClientOriginalExtension();
                $attachment->move(public_path('assets/images'), $attachments);
            } else {
                $attachments;
            }

            $update = [

                'id' => $request->id,
                'item_name' => $request->item_name,
                'purchase_from' => $request->purchase_from,
                'purchase_date' => $request->purchase_date,
                'purchased_by' => $request->purchased_by,
                'amount' => $request->amount,
                'paid_by' => $request->paid_by,
                'status' => $request->status,
                'attachments' => $attachments,
            ];

            Expense::where('id', $request->id)->update($update);
            DB::commit();
            Toastr::success('Expense updated successfully :)', 'Success');
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Expense update fail :)', 'Error');
            return redirect()->back();
        }
    }

    // delete
    public function deleteRecord(Request $request)
    {
    
        DB::beginTransaction();
        try {

            Expense::destroy($request->id);
            unlink('storage/documents/' . $request->attachments);
            DB::commit();
            Toastr::success('Expense deleted successfully :)', 'Success');
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Expense deleted fail :)'.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    /** search record */
    public function searchRecord(Request $request)
    {
        $data = DB::table('expenses')->get();

        // search by item name
        if (!empty($request->item_name) && empty($request->from_date) && empty($request->to_data)) {
            $data = Expense::where('item_name', 'LIKE', '%' . $request->item_name . '%')->get();
        }

        // search by from_date to_data
        if (empty($request->item_name) && !empty($request->from_date) && !empty($request->to_date)) {
            $data = Expense::whereBetween('purchase_date', [$request->from_date, $request->to_date])->get();
        }

        // search by item name and from_date to_data
        if (!empty($request->item_name) && !empty($request->from_date) && !empty($request->to_date)) {
            $data = Expense::where('item_name', 'LIKE', '%' . $request->item_name . '%')
                ->whereBetween('purchase_date', [$request->from_date, $request->to_date])
                ->get();
        }

        return view('sales.expenses', compact('data'));
    }
}
