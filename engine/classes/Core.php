<?php
	class Core extends Buffer {

        // Вспомогательный класс

        private static $instance;
        private static SafeMySQL $SQL;

        protected static $js;
        protected static $css;

        public static $pageTitle = "CORE::SITE_TITLE";
        public static $pageHeaders = "";

        protected static string $cachePath = "/engine/cache/";

        private function __construct() {
            global $sql;
            self::$SQL = $sql;
        }

        public static function getInstance(): Core {
            if(is_null(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public static function CheckAccess($access, $authPage = "/auth") {
            $currentPage = self::CurrentPage();

            if(substr($currentPage, -1) == '/') {
                $currentPage = substr($currentPage, 0, -1);
            }

            foreach($access as $index => $value) {
                if($currentPage == $index) {
                    switch($value) {
                        case 'D': // Denied
                            header("/");
                            break;
                        case 'U': // Only authorized
                            if(empty($_SESSION['USER'])) {
                                $query = [
                                    'from' => $_SERVER['REQUEST_URI']
                                ];
                                header("Location: ".$authPage."?".http_build_query($query));
                            }
                            break;
                        /*case 'A': // Only authorized admin
                            if(empty($_SESSION['USER_ID'])) {
                                $query = [
                                    'from' => $_SERVER['REQUEST_URI']
                                ];
                                header("Location: ".$authPage."?".http_build_query($query));
                            }

                            if(!User::getInstance()->adminPermissions($_SESSION['USER_ID'])) {
                                header('Location: /');
                            }

                            break;*/
                        case 'NU': // Only not authorized
                            if(!empty($_SESSION['USER'])) {
                                header("Location: /");
                            }
                            break;
                        default: // All
                            break;
                    }
                }
            }
        }

        public static function GetComponent($component, $template, $data = []) {
            $ROOT_COMPONENT_PATH = $_SERVER['DOCUMENT_ROOT'] . '/engine/components/' . $component . '/';
            $SITE_COMPONENT_PATH = '/engine/components/' . $component . '/';

            $ROOT_TEMPLATE_PATH = $ROOT_COMPONENT_PATH . 'templates/' . $template . '/';
            $SITE_TEMPLATE_PATH = $SITE_COMPONENT_PATH . 'templates/' . $template . '/';

            $UNIQUE_COMPONENT_ID = str_replace(".", "_", "component_".uniqid()."_".$component);

            $data += [
                'COMPONENT_ROOT_PATH' => $ROOT_COMPONENT_PATH,
                'COMPONENT_SITE_PATH' => $SITE_COMPONENT_PATH,
                'ROOT_TEMPLATE_PATH'  => $ROOT_TEMPLATE_PATH,
                'SITE_TEMPLATE_PATH'  => $SITE_TEMPLATE_PATH,
                'UNIQUE_COMPONENT_ID' => $UNIQUE_COMPONENT_ID
            ];

            $initPath      = $ROOT_COMPONENT_PATH . 'init.php';
            $scriptPath    = $ROOT_COMPONENT_PATH . 'script.js';
            $templatePath  = $ROOT_TEMPLATE_PATH  . 'template.php';
            $stylesPath    = $ROOT_TEMPLATE_PATH  . 'style.css';

            if(file_exists($templatePath)) {

                if(file_exists($initPath))
                    include $initPath;

                include $templatePath;

                self::AddJS($scriptPath);
                self::AddCSS($stylesPath);

                unset($data);
            } else {
                echo "<font color='red'>Template \"".$template."\" for component \"".$component."\" not found in ".$ROOT_COMPONENT_PATH."</font>";
            }

            unset($ROOT_COMPONENT_PATH, $SITE_COMPONENT_PATH);
        }

        public static function Set404() {
            http_response_code(404);
            die();
        }

        public static function SetHTTPStatus($status) {
            http_response_code($status);
            die();
        }

        public static function StartSession(): bool {
            $session = uniqid();

            if(empty($_SESSION['local']))
                $_SESSION['local'] = $session;

            return true;
        }

        public static function CurrentPage(): String {
            return $_SERVER['REQUEST_URI'];
        }

        public static function EmptyCheck($data, $required): Array {
            $checkResult = [];

            foreach($required as $data_key => $field_name) {
                if(empty($data[$data_key]))
                    $checkResult[] = $field_name;
            }

            return $checkResult;
        }

        public static function CheckPassword($password, $password_repeat = null): Array {
            if(strlen($password) <= PASSWORD_MIN_LENGTH) {
                return [
                    'STATE' => 'ERROR',
                    'TEXT' => 'Password must contain at least '.PASSWORD_MIN_LENGTH.' characters',
                    'LANG_CODE' => 'system::password_length',
                ];
            }

            if($password_repeat !== null) {
                if($password !== $password_repeat) {
                    return [
                        'STATE' => 'ERROR',
                        'TEXT' => 'Passwords don\'t match',
                        'LANG_CODE' => 'system::password_match',
                    ];
                }
            }

            return ['STATE' => 'OK'];
        }

        public static function Location($link) {
            header("Location: {$link}");
        }

        public static function AddJS($file): bool {

            $id = uniqid();

            if(is_file($file)) {
                self::$js[$id]['FILE'] = [
                    'NAME' => $file,
                    'SIZE' => filesize($file),
                    'PATH' => pathinfo($file)
                ];
                return true;
            }

            return false;
        }

        public static function AddCSS($file): bool {

            $id = uniqid();

            if(is_file($file)) {
                self::$css[$id]['FILE'] = [
                    'NAME' => $file,
                    'SIZE' => filesize($file),
                    'PATH' => pathinfo($file)
                ];
                return true;
            }

            return false;
        }

        public static function SetPageTitle($title) {
            Buffer::getInstance()->setProperty('PAGE_TITLE', $title);
        }

        public static function ShowPageTitle() {
            Buffer::getInstance()->showProperty('PAGE_TITLE');
        }

        public static function showHeaders() {
            Buffer::getInstance()->showProperty('ENGINE_CACHED_CSS');
            Buffer::getInstance()->showProperty('ENGINE_CACHED_JS');
        }

        public static function CheckRequired($fieldData, $requiredFields): Array {
            $returnResult = [];

            foreach($requiredFields as $key => $value) {
                if(empty($fieldData[$key])) {
                    $returnResult[] = $value;
                }
            }

            if(count($returnResult)) {
                $returnMessage = 'Please complete these fields: <br>';
                $returnMessage .= implode('<br>', $returnResult);

                return [
                    'RESULT' => 'ERROR',
                    'MESSAGE' => $returnMessage
                ];
            } else {
                return [];
            }
        }

        public static function checkInfo($database, $where, $data): bool {
            $check = self::$SQL->getAll(
                "SELECT * FROM ?n WHERE ?n = ?s",
                $database, $where, $data
            );

            if(count($check)) return true;
            return false;
        }
	}