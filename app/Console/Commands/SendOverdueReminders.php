<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\OverdueReminder;
use App\Models\Peminjaman;
use Carbon\Carbon;

class SendOverdueReminders extends Command
{
    protected $signature = 'reminders:send-overdue';

    protected $description = 'Send email reminders for overdue loans';

    public function handle()
    {
        $cutoff = Carbon::now();

        $peminjaman = Peminjaman::where('status', 'dipinjam')
            ->whereNotNull('tanggal_jatuh_tempo')
            ->whereDate('tanggal_jatuh_tempo', '<', $cutoff)
            ->where(function($q) {
                $q->whereNull('last_reminder_sent_at')
                  ->orWhere('last_reminder_sent_at', '<', now()->subDays(1));
            })
            ->with(['anggota.user', 'buku'])
            ->get();

        foreach ($peminjaman as $p) {
            if ($p->anggota && $p->anggota->user && $p->anggota->user->email) {
                Mail::to($p->anggota->user->email)->send(new OverdueReminder($p));
                $p->last_reminder_sent_at = now();
                $p->save();
                $this->info("Reminder sent for peminjaman #{$p->id}");
            }
        }

        return 0;
    }
}
