<?php
    class User {
        private static $instance;
        private static SafeMySQL $SQL;

        private function __construct() {
            global $sql;
            self::$SQL = $sql;
        }

        public static function getInstance(): User {
            if(is_null(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public static function getId(): int {
            if($_SESSION['USER']) {
                $userId    = $_SESSION['USER']['ID'];
                $userToken = $_SESSION['USER']['USER_TOKEN'];

                $getId = self::$SQL->getRow(
                    "SELECT id AS ID FROM users WHERE id = ?i AND token = ?s",
                    $userId, // ?i ID
                    $userToken // ?s TOKEN
                );

                return (int) $getId['ID'];
            }

            return 0;
        }

        public static function getList($ids = []): Array {
            $returnArray = [];

            $getList = self::$SQL->getAll(
                'SELECT * FROM users WHERE id IN (?a)',
                $ids
            );

            foreach($getList as $key => $arr) {
                $returnArray[$arr['id']] = $arr;
            }

            return (count($returnArray) ? $returnArray : []);
        }

        public static function getInfo(): Array {
            $userId = self::getId();

            if($userId) {
                $userInfo = self::$SQL->getRow(
                    "SELECT * FROM users WHERE id = ?i",
                    $userId // ?i ID
                );

                return $userInfo;
            }

            return [];
        }

        public static function isAuthorized(): bool {
            $getInfo = count(self::getInfo());
            if($_SESSION['USER']) {
                if($getInfo != 0) {
                    return true;
                } else {
                    self::LogOut();
                    return false;
                }
            } else {
                return false;
            }
        }

        public static function isAdmin(): bool {
            $userInfo = self::getInfo();
            return $userInfo['admin'] == 1;
        }

        public static function createToken($data = []): string {
            $token = MAIN_SALT;

            foreach($data as $key => $value)
                $token .= $key.":".$value;

            $token .= $_SESSION['local'];

            for($i = 0; $i <= 10; $i++)
                $token = md5($token);

            return md5($token);
        }

        private static function CheckPassword($password, $password_repeat = null): Array {
            if(strlen($password) <= PASSWORD_MIN_LENGTH) {
                return [
                    'RESULT' => 'ERROR',
                    'MESSAGE' => 'Password must contain at least '.PASSWORD_MIN_LENGTH.' characters',
                ];
            }

            if($password_repeat !== null) {
                if($password !== $password_repeat) {
                    return [
                        'RESULT' => 'ERROR',
                        'MESSAGE' => 'Passwords don\'t match',
                    ];
                }
            }

            return [];
        }

        public static function createUser($userData, $requiredFields): Array {
            foreach($userData as $key => $value) {
                $userData[$key] = htmlspecialchars(trim($value));
            }

            $userData['token']    = self::createToken($userData); // User token
            $userData['reg_date'] = time(); // Registration date

            # Check required

            $checkRequired = Core::CheckRequired($userData, $requiredFields);
            if($checkRequired) {
                $checkRequired['USER_DATA'] = $userData;
                return $checkRequired;
            }

            # Check password

            $checkPassword = self::CheckPassword($userData['password'], $userData['password_repeat']);

            if($checkPassword['RESULT'] === 'ERROR') {
                $checkPassword['USER_DATA'] = $userData;
                return $checkPassword;
            }

            # Hash user password

            $userData['password'] = md5($userData['password'] . $userData['token']);

            $insertData = [
                'username' => $userData['username'],
                'password' => $userData['password'],
                'token'    => $userData['token'],
                'reg_date' => $userData['reg_date'],
                'admin'    => 0
            ];

            if(Core::getInstance()->checkInfo('users', 'username', $userData['username'])) {
                return [
                    'RESULT'    => 'ERROR',
                    'MESSAGE'   => 'User with the same username is already registered',
                    'USER_DATA' => $userData
                ];
            }

            # Create user in db

            $createUser = self::$SQL->query(
                'INSERT INTO users SET ?u',
                $insertData
            );

            # Start user session

            $_SESSION['USER'] = [
                'ID' => self::$SQL->insertId(),
                'USER_TOKEN' => $userData['token']
            ];

            if($createUser) {
                return [
                    'RESULT' => 'SUCCESS',
                    'ACTION' => 'REDIRECT',
                    'URL' => '/',
                    'MESSAGE' => 'Successfully registered!',
                    'USER_DATA' => $userData
                ];
            }

            return [
                'RESULT' => 'ERROR',
                'MESSAGE' => 'Unknown error',
                'USER_DATA' => $userData
            ];
        }

        public static function authUser($userData): Array {
            foreach($userData as $key => $value) {
                $userData[$key] = htmlspecialchars(trim($value));
            }

            // 1. Поиск пользователя по логину, получение токена
            $getUserByLogin = self::$SQL->getRow(
                "SELECT * FROM users WHERE username = ?s",
                $userData['username']
            );

            $userToken = $getUserByLogin['token'];

            if(!$userToken) {
                return [
                    'RESULT' => 'ERROR',
                    'MESSAGE' => 'User not found'
                ];
            }

            // 2. Формирование пароля

            $userData['password'] = md5($userData['password'] . $userToken);

            // 3. Получение пользователя по логину и сформированному паролю

            $getUser = self::$SQL->getRow(
                "SELECT * FROM users WHERE username = ?s AND password = ?s",
                $userData['username'],
                $userData['password']
            );

            if($getUser['id']) {
                $_SESSION['USER'] = [
                    'ID' => $getUser['id'],
                    'USER_TOKEN' => $getUser['token']
                ];

                return [
                    'RESULT' => 'SUCCESS',
                    'ACTION' => 'REDIRECT',
                    'URL' => '/',
                    'MESSAGE' => '',
                    'USER_DATA' => $getUser
                ];
            }

            return [
                'RESULT' => 'ERROR',
                'MESSAGE' => 'User not found',
                'USER_DATA' => $userData
            ];
        }

        public static function LogOut(): bool {
            unset($_SESSION['USER']);

            return true;
        }
    }