<div class="container-fluid top-bar bg-dark text-white py-2">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-9">
            <div class="d-flex align-items-center pr-5">
                <img src="{{ asset('path/to/profile/photo.jpg') }}" alt="Profile Photo" class="profile-photo rounded-circle">
                <div class="user-details me-3 pl-3">
                    <ul>
                        <li>name:{{ $name }}</li>
                        <li>email: {{ $email }}</li>
                        <li>User ID: {{ $userId }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-3 text-center">
            <div>
                Timer: {{ $timer }} <!-- Display the timer value -->
            </div>
        </div>
    </div>
</div>

@livewireScripts
@livewireStyles

<script src="{{ asset('vendor/livewire/livewire.js') }}"></script>

<script>
   
    setInterval(function() {
        window.livewire.emit('updateTimer');
    }, 1000); 
</script>
