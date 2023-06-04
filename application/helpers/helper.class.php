<?php

# USE: Helper::...

class Helper {

    static function redirect($Url) {
        ob_end_clean();

        if (Helper::checkIfBrowserIsFirefox()) {
            header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
            header("Pragma: no-cache"); // HTTP 1.0.
            header("Expires: 0");
        }

        header("Location: " . $Url);
        exit;
    }

    static function getSingleData($Table, $ID, $Element, $Return) {

        if ($Element == '') {
            $Element = "ID";
        }

        Q::table($Table)
                ->select($Return)
                ->where("$Element = '$ID'")
                ->orderBy("ID DESC")
                ->limit("1")
                ->execute($Single, false);

        return $Single->$Return;
    }
    
    static function checkIfBrowserIsFirefox() {
        $browser = $_SERVER['HTTP_USER_AGENT'];
        $chrome = '/Chrome/';
        $firefox = '/Firefox/';
        $ie = '/MSIE/';

        if (preg_match($firefox, $browser)) {
            return true;
        } else {
            return false;
        }
    }

    static function checkIfUserLoggedIn() {
        if (isset($_SESSION["UserID"]) && $_SESSION["UserID"] != "") {
            return true;
        } else {
            return false;
        }
    }

    static function bcrypt($password) {
        return password_hash($password, PASSWORD_BCRYPT, []);
    }

    static function bcheck($password, $hash) {
        return password_verify($password, $hash);
    }

    static function generateUUID() {

        $TokenLength = 16;

        if (function_exists('random_bytes')) {
            $RandBytes = random_bytes($TokenLength);
        } else if (function_exists('openssl_random_pseudo_bytes')) {
            $RandBytes = openssl_random_pseudo_bytes($TokenLength);
        } else if (function_exists('mcrypt_create_iv')) {
            $RandBytes = mcrypt_create_iv($TokenLength, MCRYPT_DEV_URANDOM);
        }

        $RandBytes[6] = chr(ord($RandBytes[6]) & 0x0f | 0x40); // set version to 0100
        $RandBytes[8] = chr(ord($RandBytes[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($RandBytes), 4));
    }


}
