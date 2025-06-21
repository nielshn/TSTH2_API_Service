<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function getUnreadNotifications()
{
    $user = auth()->user();
    $notifications = $user->unreadNotifications()->get()->map(function ($notif) {
        return [
            'title' => $notif->data['title'] ?? 'Notifikasi',
            'message' => $notif->data['message'] ?? '',
        ];
    });

    return response()->json($notifications);
}

}
