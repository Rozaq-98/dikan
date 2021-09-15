<?php
    namespace App\Helpers;

    class GlobalHelper
    {
        public static function removeSpecialChar($text, $ignore, $replace){
            $specialChar = [
                "+", "-", "=", ".", ",", "&&", "||", "!", "(", ")",
                "{", "}", "[", "]", "^", "~", "*", "?", ":", "//",
                "\\", "_", "`", "'", '"', " ", "@", "#", "$", "%",
                "&", "|", ';', "<", ">", "/",
            ];
            $arrRemove = array_diff($specialChar, $ignore);
            $output    = str_replace($arrRemove, $replace, $text);
            return $output;
        }

        public static function formatDateToDmy($date){
            $output = date("d-m-Y", strtotime($date));
            return $output;
        }

        public static function numToAlpha($n) {
            $n = $n-1;
            for($r = ""; $n >= 0; $n = intval($n / 26) - 1)
                $r = chr($n%26 + 0x41) . $r;
            return $r;
        }
        public static function alphaToNum2($a) {
            $l = strlen($a);
            $n = 0;
            for($i = 0; $i < $l; $i++)
                $n = $n*26 + ord($a[$i]) - 0x40;
            return $n-1;
        }
        
         function alphaToNum($a) {
            $l = strlen($a);
            $n = 0;
            for($i = 0; $i < $l; $i++)
                $n = $n*26 + ord($a[$i]) - 0x40;
            return $n-1;
        }
    }
?>
