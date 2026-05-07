<?php
// Temporary debug file - remove after fixing
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__.'/../vendor/autoload.php';

\ = require_once __DIR__.'/../bootstrap/app.php';

\ = \->make(Illuminate\Contracts\Http\Kernel::class);

\ = \->handle(
    \ = Illuminate\Http\Request::capture()
);

\->send();

\->terminate(\, \);
