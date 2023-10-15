<?php
class ApplicationHelper {
    static function autoload($name): bool {
        $dirs = [
            __MAINDIR__ . '/core/classes/' . $name . '/' . $name . '.php',
            __MAINDIR__ . '/core/db/' . $name . '.php',
            __MAINDIR__ . '/core/services/' . $name . '.php',
            __MAINDIR__ . '/core/services/classHelpers/' . $name . '.php',
        ];
        foreach ($dirs as $dir) {
            if (file_exists($dir)) {
                include_once $dir;
                return true;
            }
        }
        return false;
    }


    static function handleShutdown(): void {
        $error = error_get_last();
        if ($error !== null) {
            $errorType = $error['type'];
            $errorMessage = $error['message'];
            $errorFile = $error['file'];
            $errorLine = $error['line'];

            $errorText = "Ошибка типа $errorType: $errorMessage в файле $errorFile на строке $errorLine" . PHP_EOL;;
            $errorAppend = "-------------------------" . PHP_EOL;

            file_put_contents('logs/log_' . date("d.m.Y") . '.log', $errorText . $errorAppend, FILE_APPEND);
            exit;
        }
    }

    static function init() {

        spl_autoload_register('ApplicationHelper::autoload');

        register_shutdown_function('ApplicationHelper::handleShutdown');

        Dotenv\Dotenv::createImmutable(__MAINDIR__)->load();

        PDOHelper::init();

        SessionUser::init();

        ApplicationHelper::login();

        KeyHelper::init();
    }

    static function login () {
        new login();
    }

    static function exit() {
        exit();
    }
}
