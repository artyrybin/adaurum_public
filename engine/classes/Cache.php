<?php
    class Cache extends Core {
        public static function CacheJS(): bool {

            $cacheFileName = md5($_SERVER['REQUEST_URI']) . ".cache.js";
            $cacheFilePath = Core::$cachePath . $cacheFileName;

            if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $cacheFilePath) && count(self::$js)) {
                ob_start();
                foreach(self::$js as $part) {
                    echo "\n/** START: " . $part['FILE']['NAME'] . " */\n";
                    echo file_get_contents($part['FILE']['NAME']);
                    echo "\n/** END: " . $part['FILE']['NAME'] . " */\n";
                }
                $out = ob_get_contents();
                if (ob_get_length()) ob_end_clean();

                if($out) {
                    $fileStream = fopen($_SERVER['DOCUMENT_ROOT'] . $cacheFilePath, "w");
                    fwrite($fileStream, $out);
                }
            }

            if(is_file($_SERVER['DOCUMENT_ROOT'] . $cacheFilePath)) {
                Buffer::getInstance()->setProperty('ENGINE_CACHED_JS', "<script src='{$cacheFilePath}'></script>");
            }

            return $cacheFilePath;
        }

        public static function CacheCSS(): bool {
            $CSSCacheFileName = md5($_SERVER['REQUEST_URI']) . ".cache.css";
            $CSSCacheFilePath = self::$cachePath . $CSSCacheFileName;

            if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $CSSCacheFilePath) && count(self::$css)) {
                ob_start();
                foreach(self::$css as $part) {
                    echo "\n/** START: " . $part['FILE']['NAME'] . " */\n";
                    echo file_get_contents($part['FILE']['NAME']);
                    echo "\n/** END: " . $part['FILE']['NAME'] . " */\n";
                }
                $out = ob_get_contents();
                if (ob_get_length()) ob_end_clean();

                if($out) {
                    $fileStream = fopen($_SERVER['DOCUMENT_ROOT'] . $CSSCacheFilePath, "w");
                    fwrite($fileStream, $out);
                }
            }

            if(is_file($_SERVER['DOCUMENT_ROOT'] . $CSSCacheFilePath)) {
                Buffer::getInstance()->setProperty('ENGINE_CACHED_CSS', "<link rel='stylesheet' href='{$CSSCacheFilePath}'>");
            }

            return true;
        }

        public static function ClearCache() {
            $dirFiles = glob($_SERVER['DOCUMENT_ROOT'] . self::$cachePath . "*");
            foreach($dirFiles as $file) {
                if(is_file($file)) unlink($file);
            }
        }
    }