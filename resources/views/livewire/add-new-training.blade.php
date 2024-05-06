<div id="add_training" x-data="{ isOpen: false }" x-init="() => { isOpen = true }" x-show.transition="isOpen" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Training</h5>
                <button type="button" class="close" @click="isOpen = false" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="save">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label class="col-form-label">Training Type</label>
                <select class="select" wire:model="trainingType">
                    <option selected disabled>-- Select --</option>
                    <option value="Node Training">Node Training</option>
                    <option value="Swift Training">Swift Training</option>
                </select>
                @if(isset($this->errors['trainingType']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['trainingType'][0] }}</div>
@endif
            </div>
        </div>
        <div class="col-sm-6">
    <div class="form-group">
        <label class="col-form-label">Trainer</label>
        <select class="select" id="trainer" wire:model="trainer" @error('trainer') is-invalid @enderror>
            <option value="" selected disabled>-- Select --</option>
            @foreach ($this->user as $item)
                <option value="{{ $item->id }}" >{{ $item->name }}</option>
            @endforeach
            @if(isset($this->errors['trainer']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['trainer'][0] }}</div>
@endif
        </select>
    </div>
</div>
<input type="hidden" class="form-control" id="trainer_id" wire:model="trainer_id" readonly>
        <div class="col-sm-6">
    <div class="form-group">
        <label class="col-form-label">Employees</label>
        <select class="select" wire:model="employees" @error('employees') is-invalid @enderror>
            <option selected disabled>-- Select --</option>
            @foreach ($user as $item)
                <option value="{{ $item->user_id }}">{{ $item->name }}</option>
            @endforeach
        </select>
        @if(isset($this->errors['employees']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['employees'][0] }}</div>
@endif
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
        <label class="col-form-label">Training Cost <span class="text-danger">*</span></label>
        <input class="form-control" type="text" wire:model="training_cost" @error('training_cost') is-invalid @enderror>
        @if(isset($this->errors['training_cost']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['training_cost'][0] }}</div>
@endif
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
        <label>Start Date <span class="text-danger">*</span></label>
        <div class="cal-icon">
            <input class="form-control datetimepicker" type="text" wire:model="start_date" @error('start_date') is-invalid @enderror>
            @if(isset($this->errors['start_date']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['start_date'][0] }}</div>
@endif
        </div>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
        <label>End Date <span class="text-danger">*</span></label>
        <div class="cal-icon">
            <input class="form-control datetimepicker" type="text" wire:model="end_date" @error('end_date') is-invalid @enderror>
            @if(isset($this->errors['end_date']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['end_date'][0] }}</div>
@endif
        </div>
    </div>
</div>
<div class="col-sm-12">
    <div class="form-group">
        <label>Description <span class="text-danger">*</span></label>
        <textarea class="form-control" rows="3" wire:model="description" @error('description') is-invalid @enderror></textarea>
        @if(isset($this->errors['description']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['description'][0] }}</div>
@endif
    </div>
</div>
<div class="col-sm-12">
    <div class="form-group">
        <label class="col-form-label">Status</label>
        <select class="select" wire:model="status" @error('status') is-invalid @enderror>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
        </select>
        @if(isset($this->errors['status']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['status'][0] }}</div>
@endif
    </div>
</div>

    </div>
    <div class="submit-section">
        <button type="submit" class="btn btn-primary submit-btn">Submit</button>
    </div>
</form>
                    </div>
                </div>
            </div>
        </div>