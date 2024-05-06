<?php

namespace App\Jobs;

use App\Mail\Jobapply;
use App\Models\ApplyForJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $apply_job;



    /**
     * Create a new job instance.
     * @param   ApplyForJob $apply_job
     */
    public function __construct(ApplyForJob $apply_job)
    {
        $this->apply_job = $apply_job;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $toName = $this->apply_job ? $this->apply_job->name : null;
        $toEmail = $this->apply_job ? $this->apply_job->email : null;
        $data = [
            'apply_job' => $this->apply_job,
            
        ];

        try {
            Mail::send('job.mailTemplete.mail', $data, function ($message) use ($toName, $toEmail) {
                $message->to($toEmail)->subject('New job application alert');
            });
        } catch (\Exception $e) {
            dd($e);
        }
    }
}