<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('tes{id}', function (User $user, $id) {
    return true;
});
