<?php

namespace App\Http\Livewire;

use App\Models\Employee;
use App\Models\Expense;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddExpance extends Component
{
    use WithFileUploads;
    public $employee;
    public $item_name;
    public $purchase_from;
    public $purchase_date;
    public $employeeId;
    public $amount;
    public $paid_by;
    public $status;
    public $attachments;
    public function mount(){
        $this->employee=Employee::pluck('id','name');
    }
    public function save()
    {
        
        $validatedData = $this->validate([
            'item_name' => 'required',
            'purchase_from' => 'nullable',
            'purchase_date' => 'required|date',
            'employeeId' => 'required',
            'amount' => 'required|numeric',
            'paid_by' => 'required',
            'status' => 'required',
            'attachments.*' => 'nullable|image|max:1024', // Max file size of 1MB
        ]);
        DB::beginTransaction();
        try {
           
            $attachments = time() . '.' . $this->attachments->extension();
            // dd($attachments);
            $this->attachments->storeAs('documents', $attachments, 'public');
          

            $expense = new Expense;
            $expense->item_name = $this->item_name;
            $expense->purchase_from = $this->purchase_from;
            $expense->purchase_date = $this->purchase_date;
            $expense->purchased_by = Employee::find($this->employeeId)->name;
            $expense->amount = $this->amount;
            $expense->paid_by = $this->paid_by;
            $expense->status = $this->status;
            $expense->attachments = $attachments;
            $expense->save();

            DB::commit();
            Toastr::success('Create new Expense successfully :)', 'Success');

            return redirect(request()->header('Referer'));
        } catch (\Exception $e) {
            DB::rollback();
            
            Toastr::error('Add Expense fail :)'.$e->getMessage(), 'Error');
            return redirect(request()->header('Referer'));
        }
        // Save logic here

        // Clear form fields after successful save
        $this->reset([
            'item_name', 'purchase_from', 'purchase_date', 'employeeId', 'amount', 'paid_by', 'status', 'attachments'
        ]);

        // Optionally, you can show a success message
        
    }

    public function render()
    {
        return view('livewire.add-expance');
    }
}
