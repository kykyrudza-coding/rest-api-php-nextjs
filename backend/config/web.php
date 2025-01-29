<?php

use Backend\Controllers\Controller;
use Kernel\Backend\Routing\Route;

return [
    Route::get('/', [Controller::class, 'index']),
];