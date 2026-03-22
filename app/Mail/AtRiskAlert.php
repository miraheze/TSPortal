<?php

declare( strict_types = 1 );

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AtRiskAlert extends Mailable
{
	use Queueable, SerializesModels;

	/**
	 * ID of report the email is regarding.
	 */
	private readonly int $id;

	/**
	 * Create a new message instance.
	 */
	public function __construct( Report $report )
	{
		$this->id = $report->id;
	}

	/**
	 * Get the message envelope.
	 */
	public function envelope(): Envelope
	{
		return new Envelope(
			subject: __( 'atrisk-email-subject' ),
		);
	}

	/**
	 * Get the message content definition.
	 */
	public function content(): Content
	{
		return new Content(
			view: 'emails.atrisk',
			with: [ 'id' => $this->id ],
		);
	}

	/**
	 * Get the attachments for the message.
	 *
	 * @return array<int, Attachment>
	 */
	public function attachments(): array
	{
		return [];
	}
}
