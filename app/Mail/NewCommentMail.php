<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Comment;

class NewCommentMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Có bình luận mới trên bài viết của bạn!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new_comment', // View hiển thị nội dung email
        );
    }
}