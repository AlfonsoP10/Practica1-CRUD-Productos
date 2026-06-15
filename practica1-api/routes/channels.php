<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('admin-panel', function ($user) {
    return true;
});