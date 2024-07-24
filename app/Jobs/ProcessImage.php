<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProcessImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $image;
    public function __construct(string $image)
    {
        $this->image = $image;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read(public_path($this->image));
        $image->scale(width:50);
        $image->toWebp()->save(public_path("uploads\posts\\").time().'.webp');
    }
}
