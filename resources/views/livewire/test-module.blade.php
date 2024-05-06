<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Head content -->
</head>
<body>
    <!-- Top bar component -->
    @livewire('question-top-bar', ['name' => Auth::user()->name, 'email' => Auth::user()->email, 'userId' => Auth::id()])
@livewire('question-display')
    <!-- Main content -->
    <div class="container">
        @yield('content')
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>
