<?php
session_unset();
session_destroy();
\util\functions::redirect('/');
