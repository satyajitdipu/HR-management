<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ApplyForJob;
use Illuminate\Support\Facades\Mail;

class ScheduleAlert implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $applicant;
    /**
     * Create a new job instance.
     * @param ApplyForJob $applicant
     */
    public function __construct(ApplyForJob $applicant)
    {
        $this->applicant=$applicant;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
       
        $toEmail=$this->applicant->user()->get()->first()->email;


    $toName=$this->applicant->user()->get()->first()->name;
    $data = [
        'scheduleData'=>json_decode($this->applicant->interview, true)
        
        
    ];

        try {
            Mail::send('job.mailTemplete.timeselect', $data, function ($message) use ($toName, $toEmail) {
                $message->to($toEmail)->subject('Select A timing for interview');
            });
        } catch (\Exception $e) {
            dd($e);
        }

    }
}
