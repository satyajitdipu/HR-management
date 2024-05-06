<?php

namespace App\Http\Controllers;

use App\Jobs\Payments;
use App\Models\ApplyForJob;
use App\Models\Payment;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Session;
use Razorpay\Api\Api;
use Twilio\Rest\Client;

class Razorpay extends Controller
{
    public function store(Request $request) {
     
        $input = $request->all();
       $apply_job=ApplyForJob::where('user_id',$request->user_id)->where('job_id',$request->id);
   
        $api = new Api (env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        $payment = $api->payment->fetch($input['razorpay_payment_id']);
        if(count($input) && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));
               
                $payment = Payment::create([
                    'r_payment_id' => $response['id'],
                    'method' => $response['method'],
                    'currency' => $response['currency'],
                    'user_email' => $response['email'],
                    'amount' => $response['amount']/100,
                    'json_response' => json_encode((array)$response),
                    'user_id'=>$request->user_id,
                    'job_id'=>$request->id,
                    'apply_job'=>$apply_job->get()->first()->id
                ]);

                $job=ApplyForJob::find($apply_job->get()->first()->id);
                $job->round=1;
                $job->save();
                $sid    = env("TWILIO_ID");
                $token  = env("TWILIO_TOKEN");
                $twilio = new Client($sid, $token);
            
                $message = $twilio->messages
                  ->create("+91".$apply_job->get()->first()->phone, 
                    array(
                      "from" => "+16303181081",
                      "body" => "Your Payment Successfully and payment id is :-". $payment->r_payment_id
                    )
                  );
                Payments::dispatch($payment);
            Queue::pushOn('emails', new Payments($payment));

            } catch(Exception $e) {
                Toastr::error($e->getMessage(), 'Error');
                
                Session::put('error',$e->getMessage());
                return redirect()->back();
            }
        }
        Toastr::success('Create new Estimates successfully :)', 'Success');
        return redirect()->back();
    }
}
