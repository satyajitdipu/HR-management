<div x-data="{ isOpen: false }">
    <!-- Button to open the modal -->


    <!-- Modal -->
    <div x-show="isOpen" class="modal custom-modal fade" id="add_holiday" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Holiday</h5>
                    <button type="button" @click="isOpen = false" class="close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Your form content here -->
                    <form wire:submit.prevent="saveHoliday">
                    <div class="form-group">
    <label>Holiday Name <span class="text-danger">*</span></label>
    <input class="form-control" type="text" wire:model="nameHoliday">
    @if(isset($this->errors['nameHoliday']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['nameHoliday'][0] }}</div>
@endif

</div>
<div class="form-group">
    <label>Holiday Date <span class="text-danger">*</span></label>
    <div class="cal-icon">
        <input class="form-control datetimepicker" type="text" wire:model="holidayDate">
        @if(isset($this->errors['holidayDate']))
    <div class="text-red-500"><span class="text-danger">{{ $this->errors['holidayDate'][0] }}</div>
@endif
    </div>
</div>

                        <div class="submit-section">
    <button type="submit" @click="saveHoliday()" class="btn btn-primary submit-btn">Submit</button>
</div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('closeModal', function () {
            console.log('ssss');
            $('#add_holiday').modal('hide');
        });
    });
</script>

