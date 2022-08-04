<?php
declare(strict_types=1);

class system {
    public static function init(): void {
        $_SESSION['config'] = new \system\config();
    }

    public static function config(): object { return $_SESSION['config']; }
}
