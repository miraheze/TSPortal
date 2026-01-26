<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AtRiskAlert extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * ID of report the email is regarding.
     *
     * @var int
     */
    private $id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Report $report)
    {
        $this->id = $report->id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this->text('emails.atrisk');
    }
}
