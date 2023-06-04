<?php

class Index {

    public function __construct() {
        
    }

// Execute before function
    public static function init() {

        require_once(CONFIG_PATH . '/autoloader.php');
        return new static;
    }

    public function index() {

        Q::table("lists")
        ->select()
        ->where("Status = 'active'")
        ->execute($Lists, true);

        T::load("portal/index/landingpage.php")
        ->set("Lists", $Lists)
        ->render();
    }

    public function error_page() {
        T::load("portal/index/error_page.php")
                ->render();
    }

    public function logout() {

        $Location = BASE_URL . "/";

        unset($_SESSION["UserID"]);
        unset($_SESSION["GuestSession"]);

        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        ob_end_clean();
        header("Location: " . $Location);
    }

    public function login() {

        T::load("portal/index/user_login.php")
                ->render();
    }

    public function register() {
        T::load("portal/index/user_register.php")
                ->render();
    }

    public function show_list() {

        $ListText = Q::escapeString($_POST["ListText"]);

        Q::table("lists")
                ->insert("ListText, Status")
                ->values("'$ListText', 'active'")
                ->execute();

    }

    public function edit_list() {

        $ID = Q::escapeString($_POST["ID"]);
        $ListText = Q::escapeString($_POST["ListText"]);

        Q::table("lists")
        ->update("ListText = '$ListText'")
        ->where("ID = '$ID'")
        ->execute();
    }

    public function delete_list() {

        $ID = Q::escapeString($_POST["ID"]);

        Q::table("lists")
        ->update("Status = 'deleted'")
        ->where("ID = '$ID'")
        ->execute();
    }

    public function mark_completed() {

        $ID = Q::escapeString($_POST["ID"]);
        $Value = Q::escapeString($_POST["Value"]);

        if($Value == "1") {

            Q::table("lists")
            ->update("Completed = '1'")
            ->where("ID = '$ID'")
            ->execute();


        } else {

            Q::table("lists")
            ->update("Completed = '0'")
            ->where("ID = '$ID'")
            ->execute();
        }

    }


}
