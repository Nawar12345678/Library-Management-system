<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Book;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewBookAddedNotification;


class SendNewBookNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Book $book,

    )
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->book && $this->book->user && $this->book->user->email) {
            $mail = new NewBookAddedNotification($this->book);
        
            Mail::to($this->book->user->email)->send($mail);
        }
    }
}
