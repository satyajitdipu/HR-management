<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Hash;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /** regiter page */
    public function register()
    {
        $role = DB::table('role_type_users')->get();
        return view('auth.register', compact('role'));
    }

    /** insert new users */
    public function storeUser(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role_name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);
        try {
            $dt = Carbon::now();
            $todayDate = $dt->toDayDateTimeString();

            User::create([
                'name' => $request->name,
                'avatar' => $request->image,
                'email' => $request->email,
                'join_date' => $todayDate,
                'role_name' => $request->role_name,
                'status' => 'Active',
                'password' => Hash::make($request->password),
            ]);
            if ($request->role_name == 'Client') {
                $client = new Client();
                $client->name = $request->name;
                $client->email = $request->email;
                $client->status = 'Active';
                $client->save();
            }
            Toastr::success('Create new account successfully :)', 'Success');
            return redirect('login');
        } catch (\Exception $e) {
            \Log::info($e);
            DB::rollback();
            Toastr::error('Add new employee fail :)', 'Error');
            return redirect()->back();
        }
    }
}