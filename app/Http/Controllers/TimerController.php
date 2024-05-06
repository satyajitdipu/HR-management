<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimerController extends Controller
{
    public function updateTimer(Request $request)
    {
        dd($request->all());
        $request->session()->put('timerValue', $request->input('timerValue'));

        return response()->json(['success' => true]);
    }
}
