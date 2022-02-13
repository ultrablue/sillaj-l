<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Report extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $events;
    public $group;
    public $total;
    public $startTime;
    public $reportHeader;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($eventsCollection, $groupDisplayArray, $totalTime, $startTime, $reportHeader)
    {
        $this->events = $eventsCollection;
        $this->group = $groupDisplayArray;
        $this->total = $totalTime;
        $this->startTime = $startTime;
        $this->reportHeader = $reportHeader;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // TODO add "from" info to .env, please.
        $this->from('noreply@ultraspace.com', 'Hours Emailer');
        $this->subject('Daily Hours for ' . $this->startTime->format('l F j, Y'));
        // $this->to(auth()->user()->email, auth()->user()->name);
        // dd('here '.__FILE__.':'.__LINE__);
        // Composes a plain text email.
        // return $this->text('emails.report_plain');
        // Composes a formatted (HTML) email.
        return $this->view('emails.report_html');
    }
}
