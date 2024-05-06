<div x-data="{ isOpen: false }">
    <div id="add_todaywork" class="modal custom-modal fade" role="dialog" x-show="isOpen">
        <!-- Modal content -->
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Today Work details</h5>
                    <button type="button" class="close" @click="isOpen = false" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="savetask">
                        @csrf
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="project">Project <span class="text-danger">*</span></label>
                                <select class="select" wire:model="projects">
                                    <option>Select Project</option>
                                    @foreach( $this->project as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                                @if(isset($this->errors['projects']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['projects'][0] }}</div>
@endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="employee">Employee <span class="text-danger">*</span></label>
                                <select class="select" wire:model="employees">
                                    <option>Select Employee</option>
                                    @foreach($this->employee as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                                @if(isset($this->errors['employees']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['employees'][0] }}</div>
@endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="hours">Hours <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" wire:model="hours">
                                @if(isset($this->errors['hours']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['hours'][0] }}</div>
@endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description">Description <span class="text-danger">*</span></label>
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
