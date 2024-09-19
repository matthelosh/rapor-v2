<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel("asesmen.{id}", function ($user, $id) {
    Log::info('User mencoba mengakses channel', ['user' => $user]);
    return (int) $user->id === (int) $id;
    // return true;
});
