<div>
    <div id="add_overtime" x-data="{ isOpen: false }" class="modal custom-modal fade" role="dialog" x-show="isOpen">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Overtime</h5>
                    <button type="button" class="close" @click="isOpen = false" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form wire:submit.prevent="save">
    @csrf
    <div class="form-group">
        <label>Employee <span class="text-danger">*</span></label>
        <select class="select" wire:model="employeeId">
            @foreach($employee as $id => $name)
            <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </select>
        @if(isset($this->errors['employeeId']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['employeeId'][0] }}</div>
@endif
    </div>
    <div class="form-group">
        <label>Overtime Date <span class="text-danger">*</span></label>
        <div class="cal-icon">
            <input type="date" wire:model="overtimeDate"
                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
        </div>
        @error('overtimeDate') <div class="text-red-500"><span class="text-danger">{{ $message }}</span></div> @enderror
    </div>
    <div class="form-group">
        <label>Overtime Hours <span class="text-danger">*</span></label>
        <input class="form-control" type="number" wire:model="overtimeHours">
        @if(isset($this->errors['overtimeHours']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['overtimeHours'][0] }}</div>
@endif
    </div>
    <div class="form-group">
        <label>Description <span class="text-danger">*</span></label>
        <textarea rows="4" class="form-control" wire:model="description"></textarea>
        @if(isset($this->errors['description']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['description'][0] }}</div>
@endif
    </div>
    <div class="submit-section">
        <button type="submit" class="btn btn-primary submit-btn">Submit</button>
    </div>
</form>

                </div>
            </div>
        </div>
    </div>
</div>
