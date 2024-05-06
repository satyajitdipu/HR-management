<div>
    <div x-data="{ isOpen: false }">
        <div id="add_expense" class="modal custom-modal fade" role="dialog" x-show="isOpen">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Expense</h5>
                        <button type="button" class="close" @click="isOpen = false" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <form wire:submit.prevent="save" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Item Name</label>
                <input class="form-control" type="text" wire:model="item_name" required>
                @error('item_name') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Purchase From</label>
                <input class="form-control" type="text" wire:model="purchase_from">
                @error('purchase_from') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Purchase Date</label>
                <div class="cal-icon">
                    <input class="form-control datetimepicker" type="text" wire:model="purchase_date">
                    @error('purchase_date') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Purchased By</label>
                <select class="select" wire:model="employeeId">
                    @foreach($employee as $name => $id)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                @error('employeeId') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Amount</label>
                <input placeholder="$50" class="form-control" type="text" wire:model="amount" required>
                @error('amount') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Paid By</label>
                <select class="select" wire:model="paid_by">
                    <option value="Cash">Cash</option>
                    <option value="Cheque">Cheque</option>
                </select>
                @error('paid_by') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Status</label>
                <select class="select" wire:model="status">
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                </select>
                @error('status') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Attachments</label>
                <input class="form-control" type="file" wire:model="attachments">
                @error('attachments') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
    <div class="attach-files">
    <ul>
        @if ($attachments)
            <li>
                <img src="{{ $attachments->temporaryUrl() }}" alt="">
                <a href="#" class="fa fa-close file-remove"></a>
            </li>
        @endif
    </ul>
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
</div>
