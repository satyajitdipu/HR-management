<div x-data="{ isOpen: false }">
    

    <div x-show="isOpen" id="add_leave" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Leave</h5>
                    <button type="button" class="close" @click="isOpen = false" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form wire:submit.prevent="saveLeave">
    <div class="form-group">
        <label>Leave Type <span class="text-danger">*</span></label>
        <select class="select" wire:model="leave_type" x-model="leaveType">
            <option>Select Leave Type</option>
            <option value="Casual Leave">Casual Leave</option>
            <option value="Medical Leave">Medical Leave</option>
            <option value="Loss of Pay">Loss of Pay</option>
        </select>
        @if(isset($this->errors['leave_type']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['leave_type'][0] }}</div>
@endif
    </div>
    <div class="form-group">
        <label>From <span class="text-danger">*</span></label>
        <div class="cal-icon">
            <input wire:model="from_date" class="form-control datetimepicker" type="text" x-model="from_date">
            @if(isset($this->errors['from_date']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['from_date'][0] }}</div>
@endif
        </div>
    </div>
    <div class="form-group">
        <label>To <span class="text-danger">*</span></label>
        <div class="cal-icon">
            <input wire:model="to_date" class="form-control datetimepicker" type="text" x-model="to_date">
            @if(isset($this->errors['to_date']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['to_date'][0] }}</div>
@endif
        </div>
    </div>
    <div class="form-group">
        <label>Number of days <span class="text-danger">*</span></label>
        <input wire:model="numberOfDays" class="form-control" readonly type="text" x-model="numberOfDays">
        @error('numberOfDays') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="form-group">
        <label>Leave Reason <span class="text-danger">*</span></label>
        <textarea rows="4" wire:model="leave_reason" class="form-control" x-model="leave_reason"></textarea>
        @if(isset($this->errors['leave_reason']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['leave_reason'][0] }}</div>
@endif
    </div>
    <div class="submit-section">
        <button class="btn btn-primary submit-btn" @click="saveLeave">Submit</button>
    </div>
</form>

                </div>
            </div>
        </div>
    </div>
</div>
