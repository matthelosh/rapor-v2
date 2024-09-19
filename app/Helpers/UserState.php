<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserState
{
    public static function isOnline($userId)
    {
        if (!$userId) {
            return false;
        }

        $session = DB::table('sessions')
            ->where('user_id', $userId)
            ->orderBy('last_activity', 'desc')
            ->first();

        if (!$session) return false;

        return (Carbon::now()->diffInSeconds(Carbon::createFromTimeStamp($session->last_activity)) <= 300);
    }
}
