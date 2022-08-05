<?php

declare(strict_types=1);

namespace user;

session_unset();
session_destroy();
util::redirect('/');
