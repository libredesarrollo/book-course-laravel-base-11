<?php

namespace App\Jobs;

use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $r = rand(1, 3);
        Log::error($r);
        sleep(2);
        if ($r > 2) {
            // 3
            Mail::to($this->user->email)->send(new WelcomeEmail($this->user));
        } else {
            // 1 2
            Mail::to($this->user->email)->send(new WelcomeEmail());
        }
    }
}
