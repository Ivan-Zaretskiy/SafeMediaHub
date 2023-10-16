<?php
class ApplicationHelper {

    private static string $mode;

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

    static function getRouteClass() {
        $page = $_GET['page'] ?? 'homepage';
        switch ($page) {
            case '404' :
                doError('Page not found!');
                break;
            default :
                return new $page();
        }
    }

    static function getRouteAction() {
        return $_GET['action'] ?? 'show';
    }

    static function initRoute(): void {
        self::getRouteClass()->{self::getRouteAction()}();
    }
    static function setAppMode(): void {
        $mode = $_REQUEST['appMode'] ?? null;
        self::$mode = match($mode) {
            'load' => 'load',
            default => 'main'
        };
    }
    static function getAppMode(): string {
        return self::$mode;
    }

    static function init(): void {
        spl_autoload_register('ApplicationHelper::autoload');

        register_shutdown_function('ApplicationHelper::handleShutdown');

        Dotenv\Dotenv::createImmutable(__MAINDIR__)->load();

        PDOHelper::init();

        SessionUser::init();

        ApplicationHelper::setAppMode();

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
