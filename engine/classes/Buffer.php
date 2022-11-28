<?php
    class Buffer {

        private static $instance;
        private static array $search = [];

        function __construct() {
            $this->startBuffering();
            register_shutdown_function(
                array($this, 'endBuffering')
            );
        }

        public static function getInstance(): Buffer {
            if(is_null(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function startBuffering() {
            ob_start();
        }

        public function endBuffering() {
            if(ob_get_level() > 1) {
                $data = ob_get_contents();
                ob_end_clean();

                $this->insertBufferedContent($data);

                echo $data;
            }
        }

        public function insertBufferedContent(&$data) {
            self::$search = [];
            if(!empty($this->buffered)) {
                foreach($this->buffered as $id => $contentData) {
                    self::$search[] = '<!--'.$id.'-->';
                }

                $data = str_replace(self::$search, $this->buffered, $data);
            }
        }

        public static function showProperty($id) {
            if(ob_get_level() > 1) {
                echo '<!--'.$id.'-->';
            }
        }

        public function setProperty($id, $data) {
            $this->buffered[$id] = $data;
        }
    }