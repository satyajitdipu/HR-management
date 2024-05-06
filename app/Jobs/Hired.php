<?php

namespace App\Jobs;

use App\Models\ApplyForJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class Hired implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $status;
    /**
     * Create a new job instance.
     * @param ApplyForJob $status
     */
    public function __construct(ApplyForJob $status)
    {
        $this->status=$status;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $toEmail=$this->status->email;
        $toname=$this->status->name;
         $data = [
            'status'=>$this->status
            
            
        ];
        try {
            Mail::send('job.mailTemplete.hired', $data, function ($message) use ($toname, $toEmail) {
                $message->to($toEmail)->subject('Thank you For Apply');
            });
        } catch (\Exception $e) {
            
            \Log::info($e->getMessage());
        }
    }
}