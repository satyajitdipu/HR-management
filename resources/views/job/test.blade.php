<livewire:timer-component :question-top-bar="$timer" />

<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('timerUpdated', function (timerValue) {
            // Update the displayed timer value
            document.getElementById('timer').innerText = timerValue;
        });

        Livewire.on('timerExpired', function () {
            // Handle timer expiration
            // For example, display a message or perform a redirect
        });
    });
</script>