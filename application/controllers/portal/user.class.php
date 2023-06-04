<?php

class User {

    public function __construct() {
        
    }

    // Execute before function
    public static function init() {
        require_once(CONFIG_PATH . '/autoloader.php');
        return new static;
    }

    public function user_login() {
        $Email = Q::escapeString($_POST["Email"]);
        $Password = $_POST["Password"];

        Q::table("users")
                ->select()
                ->where("Email = '$Email' AND Status = 'active'")
                ->orderBy("ID ASC")
                ->limit("1")
                ->execute($User, false);

        $EmailStatus = false;
        $PasswordStatus = false;

        if ($User) {
            $EmailStatus = true;
          
            if (Helper::bcheck($Password, $User->Password)) {

                $PasswordStatus = true;

                $UserID = $User->ID;
                $_SESSION["UserID"] = $UserID;
                $ServerName = SERVER_NAME;

                Q::table("users")
                        ->update("ServerName = '$ServerName'")
                        ->where("ID = '$UserID'")
                        ->execute();

            }
        }

        if ($EmailStatus == false && $PasswordStatus == false) {
            $StatusText = "E-pošta in geslo sta napačni!";
        } elseif ($EmailStatus == false && $PasswordStatus == true) {
            $StatusText = "E-pošta je napačna!";
        } elseif ($EmailStatus == true && $PasswordStatus == false) {
            $StatusText = "Geslo je napačno!";
        }

        if ($EmailStatus == true && $PasswordStatus == true) {
                $Location = BASE_URL . "/profil";
        } else {
            $Location = "";
        }

        $ReturnArray = array(
            "StatusText" => $StatusText,
            "Location" => $Location
        );

        echo json_encode($ReturnArray);
    }

    public function user_register() {
        $Email = Q::escapeString($_POST["Email"]);
        $Password = Helper::bcrypt($_POST["Password"]);
        $Firstname = Q::escapeString($_POST["Firstname"]);
        $Lastname = Q::escapeString($_POST["Lastname"]);

        Q::table("users")
                ->select()
                ->where("Email = '$Email' AND Status = 'active' AND Email != ''")
                ->orderBy("ID ASC")
                ->limit("1")
                ->execute($CheckUser, false);

        $StatusText = "false";

        if (!$CheckUser) {

            $UUID = Helper::generateUUID();
            $ServerName = SERVER_NAME;

            Q::table("users")
                    ->insert("UUID, Email, Password, Firstname, Lastname, CreatedAt, Status, Role, ServerName")
                    ->values("'$UUID', '$Email', '$Password', '$Firstname', '$Lastname', NOW(), 'active', 'user', '$ServerName'")
                    ->execute();

            $UserID = Q::lastID();
            
            Q::table("users")
                    ->select()
                    ->where("ID = '$UserID' AND Email = '$Email' AND Status = 'active'")
                    ->orderBy("ID DESC")
                    ->limit("1")
                    ->execute($NewUser, false);
            
            if($NewUser){
                $_SESSION["UserID"] = $UserID;
            }
            
            $UserID = $NewUser->ID;

            $StatusText = "";
            $Location = BASE_URL . "/";
            
        } else {
            $Link = "<a href='" . BASE_URL . "/prijava'>povezavi</a>";
            $ExistsText = "Račun že obstaja";
            $StatusText = str_replace("#LINK#", $Link, $ExistsText);
            $Location = "";
        }

        $ReturnArray = array(
            "StatusText" => $StatusText,
            "Location" => $Location
        );

        echo json_encode($ReturnArray);
    }


}
