<?php
    class Companies {
        private static $instance;
        private static SafeMySQL $SQL;

        private function __construct() {
            global $sql;
            self::$SQL = $sql;
        }

        public static function getInstance(): Companies {
            if(is_null(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public static function getList(): Array {
            return self::$SQL->getAll(
                "SELECT * FROM companies ORDER BY id DESC"
            );
        }

        public static function getByID($id): Array {
            $returnItem = self::$SQL->getRow(
                "SELECT * FROM companies WHERE id = ?i LIMIT 1",
                $id // ?i ID
            );
            return ($returnItem != null ? $returnItem : []);
        }

        public static function getByXML($xml_id): Array {
            $returnItem = self::$SQL->getRow(
                "SELECT * FROM companies WHERE xml_id = ?s LIMIT 1",
                $xml_id // ?s XML_ID
            );
            return ($returnItem != null ? $returnItem : []);
        }

        public static function getFieldTypes($setKeys = false): Array {
            $getFieldTypes = self::$SQL->getAll(
                "SELECT * FROM field_types ORDER BY id DESC"
            );

            if($setKeys) {
                foreach($getFieldTypes as $key => $field) {
                    $getFieldTypes[$field['xml_id']] = $field;
                    unset($getFieldTypes[$key]);
                }
            }

            return $getFieldTypes;
        }

        public static function getFields(): Array {
            return self::$SQL->getAll(
                "SELECT * FROM fields ORDER BY id DESC"
            );
        }

        public static function getCompanyFields($id): Array {
            return self::$SQL->getAll(
                "SELECT * FROM fields WHERE company_id = ?i ORDER BY id DESC",
                $id // ?i COMPANY_ID
            );
        }

        public static function getNotes($companyId = null): Array {
            $returnArray = [];

            $getNotes = self::$SQL->getAll(
                "SELECT * FROM field_notes WHERE company_id = ?i ORDER BY id DESC",
                $companyId // ?i COMPANY_ID
            );

            foreach($getNotes as $index => $note) {
                $returnArray[$note['field_type']][] = $note;
            }

            return $returnArray;
        }

        public static function getCompanyComments($companyId): Array {
            $returnArray = self::$SQL->getAll(
                "SELECT * FROM comments WHERE company_id = ?i ORDER BY id DESC",
                $companyId // ?i COMPANY_ID
            );
            return ($returnArray !== null ? $returnArray : []);
        }

        public static function Update($fields): Array {

            foreach($fields as $key => $field) {
                $fields[$key] = htmlspecialchars($field);
            }

            $insertFields = [];
            $updateInfo   = [];

            // 1. Получение полей.
            $fieldTypes = self::getInstance()->getFieldTypes(true);

            foreach($fields as $key => $value) {
                if(in_array($key, array_keys($fieldTypes))) {
                    $insertFields[] = [
                        'type' => $key,
                        'company_id' => $fields['COMPANY_ID'],
                        'value' => $value
                    ];
                    unset($fields[$key]);
                }
            }

            // 3. Удаление всех старых значений полей (Я считаю, что быстрее, чем проходиться с проверками по каждому полю)

            self::$SQL->query(
                'DELETE FROM fields WHERE company_id = ?i',
                (int) $fields['COMPANY_ID']
            );

            self::$SQL->query(
                'INSERT INTO fields ?k VALUES ?m',
                $insertFields,
                $insertFields
            );

            // 4. Внесение основной информации о компании

            foreach($fields as $key => $field) {
                if(empty($field))
                    return [
                        'RESULT'  => 'ERROR',
                        'MESSAGE' => 'All required fields must be filled'
                    ];
            }

            $updateInfo = [
                'name' => $fields['company_name'],
            ];

            self::$SQL->query(
                'UPDATE companies SET ?u WHERE id = ?i',
                $updateInfo,
                $fields['COMPANY_ID']
            );

            $updateInfo['RESULT'] = 'SUCCESS';
            $updateInfo['MESSAGE'] = 'Successfully saved!';

            return $updateInfo;
        }

        public static function Delete($id) {
            // Удаление компании
            self::$SQL->query(
                'DELETE FROM companies WHERE id = ?i',
                $id
            );

            // Удаление заметок, связанных с полями компании
            self::$SQL->query(
                'DELETE FROM field_notes WHERE company_id = ?i',
                $id
            );

            // Удаление полей компании
            self::$SQL->query(
                'DELETE FROM fields WHERE company_id = ?i',
                $id
            );

            // Удаление комментариев о компании
            /*self::$SQl->query(
                'DELETE FROM comments WHERE company_id = ?i',
                $id
            );*/

            return true;
        }

        public static function Create($data, $requiredData, $picture) {
            foreach($data as $key => $value) {
                $data[$key] = htmlspecialchars(trim($value));
            }

            $checkRequired = Core::CheckRequired($data, $requiredData);
            if($checkRequired) {
                $checkRequired['DATA'] = $data;
                return $checkRequired;
            }

            $insertData = [
                'name' => $data['name'],
                'xml_id' => $data['xml_id'],
                'create_date' => time()
            ];

            // Массив с допустимыми форматами изображения
            $acceptExtensions = [
                'jpg', 'jpeg', 'gif', 'bmp', 'png', 'wepb'
            ];

            foreach($data as $key => $value) {
                $data[$key] = htmlspecialchars(trim($value));
            }

            // Проверка XML ID на уникальность
            $checkXML = self::$SQL->getAll(
                'SELECT * FROM companies WHERE xml_id = ?s',
                $data['xml_id']
            );

            if(count($checkXML) !== 0) {
                return [
                    'RESULT'    => 'ERROR',
                    'MESSAGE'   => 'Company with the same XML_ID is already registered',
                    'DATA'      => $data,
                ];
            }

            if(empty($picture['name'])) {
                return [
                    'RESULT'  => 'ERROR',
                    'MESSAGE' => 'Please select an image',
                    'DATA'    => $data,
                ];
            }

            $fileExtension = pathinfo($picture['name'])['extension'];

            if(!in_array($fileExtension, $acceptExtensions)) {
                return [
                    'RESULT'  => 'ERROR',
                    'MESSAGE' => 'Invalid file format',
                    'DATA'    => $data,
                ];
            }

            // Upload picture

            $uploadTo = '/upload/'.uniqid();
            $uploadFile = $uploadTo . '/' . uniqid() . '.' . $fileExtension;

            if(!is_dir($_SERVER['DOCUMENT_ROOT'] . $uploadTo)) {
                if(!mkdir(
                    $_SERVER['DOCUMENT_ROOT'] . $uploadTo,
                    0777,
                    true
                )) {
                    return [
                        'RESULT'  => 'ERROR',
                        'MESSAGE' => 'Cannot create directory ' . $_SERVER['DOCUMENT_ROOT'] . $uploadTo,
                        'DATA'    => $data,
                    ];
                }
            }

            if(!move_uploaded_file($picture['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $uploadFile)) {
                return [
                    'RESULT'  => 'ERROR',
                    'MESSAGE' => 'File upload error ' . $picture['error'],
                    'DATA'    => $data,
                ];
            }

            $insertData['picture'] = $uploadFile;

            $insertQuery = self::$SQL->query(
                'INSERT INTO companies SET ?u',
                $insertData
            );

            $insertId = self::$SQL->insertId();

            // Create default fields

            $insertFields = [];
            $fieldTypes = self::getInstance()->getFieldTypes(true);

            foreach($fieldTypes as $key => $value) {
                if(in_array($key, array_keys($fieldTypes))) {
                    $insertFields[] = [
                        'type' => $key,
                        'company_id' => $insertId,
                        'value' => ''
                    ];
                    unset($fields[$key]);
                }
            }

            self::$SQL->query(
                'INSERT INTO fields ?k VALUES ?m',
                $insertFields,
                $insertFields
            );

            if($insertQuery) {
                return [
                    'RESULT' => 'SUCCESS',
                    'ACTION' => 'REDIRECT',
                    'MESSAGE' => 'Company created',
                    'URL' => "/company/{$data['xml_id']}",
                    'DATA' => $data,
                ];
            } else {
                return [
                    'RESULT' => 'ERROR',
                    'MESSAGE' => 'Unknown error'
                ];
            }
        }

        public static function SendComment($data): Array {
            foreach($data as $key => $value) {
                $data[$key] = htmlspecialchars(trim($value));
            }

            if(empty($data['message'])) {
                return [
                    'RESULT' => 'ERROR',
                    'MESSAGE' => 'Please, enter your message'
                ];
            }

            $insertData = [
                'user_id'      => User::getInstance()->getId(),
                'company_id'   => $data['company_id'],
                'text'         => $data['message'],
                'publish_date' => time()
            ];

            self::$SQL->query(
                "INSERT INTO comments SET ?u",
                $insertData
            );

            return [
                'RESULT' => 'SUCCESS',
                'DATA' => $insertData,
            ];
        }

        public static function SendNote($data): Array {
            foreach($data as $key => $value) {
                $data[$key] = htmlspecialchars(trim($value));
            }

            if(empty($data['message'])) {
                return [
                    'RESULT' => 'ERROR',
                    'MESSAGE' => 'Please, enter your message'
                ];
            }

            $insertData = [
                'user_id'      => User::getInstance()->getId(),
                'company_id'   => $data['company_id'],
                'field_type'   => $data['field_type'],
                'text'         => $data['message'],
                'publish_date' => time()
            ];

            self::$SQL->query(
                "INSERT INTO field_notes SET ?u",
                $insertData
            );

            return [
                'RESULT' => 'SUCCESS',
                'DATA' => $insertData,
            ];
        }
    }