<?php
// app/Notifications/CustomVerifyEmail.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends Notification
{
    use Queueable;

    protected $pendingEmail;

    public function __construct($pendingEmail)
    {
        $this->pendingEmail = $pendingEmail;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifikasi Email Baru')
            ->line('Anda telah meminta untuk mengubah email Anda.')
            ->line('Klik tombol di bawah ini untuk memverifikasi email baru Anda.')
            ->action('Verifikasi Email', $verificationUrl)
            ->line('Jika Anda tidak meminta perubahan ini, abaikan email ini.')
            ->line('Link ini akan kedaluwarsa dalam 60 menit.');
    }

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($this->pendingEmail),
            ]
        );
    }


// Update method updateEmail untuk menggunakan custom notification:
public function updateEmail(Request $request) {
    // ... validasi sama seperti sebelumnya ...

    try {
        // Kirim custom notification
        $user->notify(new \App\Notifications\CustomVerifyEmail($request->email));

        \Log::info('Custom email verification sent to: ' . $request->email);

        return response()->json([
            'message' => 'Link verifikasi telah dikirim ke email baru. Harap cek inbox Anda.',
        ]);

    } catch (\Exception $e) {
        \Log::error('Failed to send verification email: ' . $e->getMessage());

        return response()->json([
            'message' => 'Gagal mengirim email verifikasi. Silakan coba lagi.',
            'error' => $e->getMessage()
        ], 500);
    }
}
