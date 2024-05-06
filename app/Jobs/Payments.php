<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;

class Payments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $payment;
    /**
     * Create a new job instance.
     * @param Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment=$payment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $payment=$this->payment;
    $toEmail=$payment->user()->get()->first()->email;


    $toName=$payment->user()->get()->first()->name;
    $data = [
        'payment'=>$this->payment,
        
        
    ];

        try {
            Mail::send('job.mailTemplete.paymentsuccess', $data, function ($message) use ($toName, $toEmail) {
                $message->to($toEmail)->subject('New job application alert');
            });
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
