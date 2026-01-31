<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Peminjaman;

class OverdueReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $peminjaman;

    public function __construct(Peminjaman $peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }

    public function build()
    {
        return $this->subject('Pengingat Peminjaman Terlambat - Perpustakaan')
                    ->view('emails.overdue_reminder');
    }
}
