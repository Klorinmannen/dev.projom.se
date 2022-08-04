<?php
declare(strict_types=1);
//set_include_path(__DIR__);

// Auto load classes
require_once('auto_loader.php');

// Start session after auto_loader
session_start();

\system::init();
\http\request::init();
\user::init();
