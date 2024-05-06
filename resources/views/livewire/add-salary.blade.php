<div>
<div id="add_salary" x-data="{ isOpen: false }" x-show="isOpen" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Staff Salary</h5>
                    <button type="button" class="close" @click="isOpen = false" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        @csrf
                        <div class="row"> 
                            <div class="col-sm-6"> 
                                <div class="form-group">
                                    <label>Select Staff</label>
                                    <select class="select select2s-hidden-accessible @error('name') is-invalid @enderror" style="width: 100%;" tabindex="-1" aria-hidden="true" wire:model="name" required>
                                        <option value="">-- Select --</option>
                                        @foreach ($userList as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @if(isset($this->errors['name']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['name'][0] }}</div>
@endif
                                </div>
                                <input class="form-control" type="hidden" wire:model="user_id" readonly>
                            </div>
                            <div class="col-sm-6"> 
                                <div class="form-group">
                                    <label>Net Salary</label>
                                    <input class="form-control @error('salary') is-invalid @enderror" type="number" wire:model="salary" placeholder="Enter net salary" required>
                                    @if(isset($this->errors['salary']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['salary'][0] }}</div>
@endif
                                </div>
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-sm-6"> 
                                <h4 class="text-primary">Earnings</h4>
                                <div class="form-group">
                                    <label>Basic</label>
                                    <input class="form-control @error('basic') is-invalid @enderror" type="number" wire:model="basic" placeholder="Enter basic" required>
                                    @if(isset($this->errors['basic']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['basic'][0] }}</div>
@endif
                                </div>
                                <div class="form-group">
                                    <label>DA(40%)</label>
                                    <input class="form-control @error('da') is-invalid @enderror" type="number" wire:model="da" placeholder="Enter DA(40%)" required>
                                    @if(isset($this->errors['da']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['da'][0] }}</div>
@endif
                                </div>
                                <div class="form-group">
                                        <label>HRA(15%)</label>
                                        <input class="form-control @error('hra') is-invalid @enderror" type="number"  name="hra" wire:model="hra" id="hra" value="{{ old('hra') }}" placeholder="Enter HRA(15%)">
                                        @if(isset($this->errors['hra']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['hra'][0] }}</div>
@endif
                                    </div>
                                    <div class="form-group">
                                        <label>Conveyance</label>
                                        <input class="form-control @error('conveyance') is-invalid @enderror" type="number"  name="conveyance" wire:model="conveyance" id="conveyance" value="{{ old('conveyance') }}" placeholder="Enter conveyance">
                                        @if(isset($this->errors['conveyance']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['conveyance'][0] }}</div>
@endif
                                    </div>
                                    <div class="form-group">
                                        <label>Allowance</label>
                                        <input class="form-control @error('allowance') is-invalid @enderror" type="number"  name="allowance" wire:model="allowance" id="allowance" value="{{ old('allowance') }}" placeholder="Enter allowance">
                                        @if(isset($this->errors['allowance']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['allowance'][0] }}</div>
@endif
                                    </div>
                                    <div class="form-group">
                                        <label>Medical  Allowance</label>
                                        <input class="form-control @error('medical_allowance') is-invalid @enderror" type="number" name="medical_allowance" wire:model="medical_allowance" id="medical_allowance" value="{{ old('medical_allowance') }}" placeholder="Enter medical  allowance">
                                        @if(isset($this->errors['medical_allowance']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['medical_allowance'][0] }}</div>
@endif
                                    </div>
                                </div>
                              
                            <div class="col-sm-6">  
                                <h4 class="text-primary">Deductions</h4>
                                <div class="form-group">
                                    <label>TDS</label>
                                    <input class="form-control @error('tds') is-invalid @enderror" type="number" wire:model="tds" placeholder="Enter TDS">
                                    @if(isset($this->errors['tds']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['tds'][0] }}</div>
@endif
                                </div>
                                <div class="form-group">
                                    <label>ESI</label>
                                    <input class="form-control @error('esi') is-invalid @enderror" type="number" wire:model="esi" placeholder="Enter ESI">
                                    @if(isset($this->errors['esi']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['esi'][0] }}</div>
@endif
                                </div>
                                <div class="form-group">
                                        <label>PF</label>
                                        <input class="form-control @error('pf') is-invalid @enderror" type="number" name="pf"wire:model="pf" id="pf" value="{{ old('pf') }}" placeholder="Enter PF">
                                        @if(isset($this->errors['pf']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['pf'][0] }}</div>
@endif
                                    </div>
                                    <div class="form-group">
                                        <label>Leave</label>
                                        <input class="form-control @error('leave') is-invalid @enderror" type="text" name="leave" id="leave" wire:model="leave" value="{{ old('leave') }}" placeholder="Enter leave">
                                        @if(isset($this->errors['leave']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['leave'][0] }}</div>
@endif
                                    </div>
                                    <div class="form-group">
                                        <label>Prof. Tax</label>
                                        <input class="form-control @error('prof_tax') is-invalid @enderror" type="number" name="prof_tax" wire:model="prof_tax" id="prof_tax" value="{{ old('prof_tax') }}" placeholder="Enter Prof. Tax">
                                        @if(isset($this->errors['prof_tax']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['prof_tax'][0] }}</div>
@endif
                                    </div>
                                    <div class="form-group">
                                        <label>Loan</label>
                                        <input class="form-control @error('labour_welfare') is-invalid @enderror" type="number" name="labour_welfare" wire:model="labour_welfare" id="labour_welfare" value="{{ old('labour_welfare') }}" placeholder="Enter Loan">
                                        @if(isset($this->errors['labour_welfare']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['labour_welfare'][0] }}</div>
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
</div>
