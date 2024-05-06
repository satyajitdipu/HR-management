<div id="add_employee" class="modal custom-modal fade" role="dialog">
    <!-- Modal content -->
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if(count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form wire:submit.prevent="submitForm" id="employeeForm">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Full Name</label>
                                <select wire:model="selectedName" class="select select2s-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="name" name="name" required>
                                    <option value="">-- Select --</option>
                                    @foreach ($userList as $user)
                                        <option value="{{ $user->name }}" data-employee_id="{{ $user->user_id }}" data-email="{{ $user->email }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                <input wire:model="email" class="form-control" type="email" id="email" name="email" placeholder="Auto email" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Birth Date</label>
                                <div class="cal-icon">
                                    <input wire:model="date" class="form-control datetimepicker" type="text" id="birthDate" name="birthDate">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gender</label>
                                <select wire:model.defer="selectedGender" class="select form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" id="gender" name="gender">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Employee ID <span class="text-danger">*</span></label>
                                <input type="text" wire:model="employee_id" class="form-control" id="employee_id" name="employee_id" placeholder="Auto id employee" readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Line Manager</label>
                                <select wire:model="manager" class="select select2s-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" id="company" name="company" required>
                                    <option value="">-- Select --</option>
                                    @foreach ($userList as $key=>$user)
                                        <option value="{{ $user->name }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="submit-section">
                        <button type="submit" wire:click="submitForm" class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        Livewire.on('formSubmitted', function() {
            $('#add_employee').modal('hide');
        });
    </script>
@endpush