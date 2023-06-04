<?php

class Q {

    private static $Table;
    private static $Query;
    private static $QueryDuplicate;
    private static $Result;
    private static $LeftJoin = false;
    private static $Join = false;
    private static $Objects = array();

    // -- (SET TABLE NAME) -----------------------------------------------------
    public static function table($Table, $DatabaseName = DATABASE) {
        static::$Table = $DatabaseName . "." . $Table;
        return new static;
    }

    // -------------------------------------------------------------------------
    // -- (GET TABLE FIELDS) -----------------------------------------------------
    public static function getFields(&$Fields) {
        require_once CORE_PATH . "/database.class.php";
        static::$Query = "SHOW COLUMNS FROM " . static::$Table;
        static::$Result = mysqli_query(Database::mysqli()->getLink(), static::$Query) or die(mysqli_error(Database::mysqli()->getLink()));

        $Fields = array();
        while ($Row = mysqli_fetch_object(static::$Result)) {
            array_push($Fields, $Row);
        }

        return new static;
    }

    // -------------------------------------------------------------------------
    // -- (GET TABLES) -----------------------------------------------------
    public static function getTables($Schema) {
        require_once CORE_PATH . "/database.class.php";

        $dbname = $Schema;

        if (!mysql_connect(HOST, USERNAME, PASSWORD)) {
            echo 'Could not connect to mysql';
            exit;
        }

        $sql = "SHOW TABLES FROM $dbname";
        $result = mysql_query($sql);

        if (!$result) {
            echo "DB Error, could not list tables\n";
            echo 'MySQL Error: ' . mysql_error();
            exit;
        }

        $ArrayOfTables = array();

        while ($row = mysql_fetch_row($result)) {
            $ArrayOfTables[] = $row[0];
        }

        mysql_free_result($result);
        return $ArrayOfTables;
    }

    // -------------------------------------------------------------------------
    // -- (DUPLICATE SCHEMA DB) -----------------------------------------------------
    public static function duplicateSchemaDB($NewSchemaDBName) {
        require_once CORE_PATH . "/database.class.php";

        /*         * ******************* START CONFIGURATION ******************** */
        $DB_SRC_HOST = HOST;
        $DB_SRC_USER = USERNAME;
        $DB_SRC_PASS = PASSWORD;
        $DB_SRC_NAME = DEFAULT_COMPANY_SCHEMA;

        $DB_DST_HOST = HOST;
        $DB_DST_USER = USERNAME;
        $DB_DST_PASS = PASSWORD;
        $DB_DST_NAME = $NewSchemaDBName;

        /*         * ********************* GRAB OLD SCHEMA ********************** */
        $db1 = mysql_connect($DB_SRC_HOST, $DB_SRC_USER, $DB_SRC_PASS) or die(mysql_error());
        mysql_select_db($DB_SRC_NAME, $db1) or die(mysql_error());

        $result = mysql_query("SHOW TABLES;", $db1) or die(mysql_error());
        $buf = "set foreign_key_checks = 0;\n";
        $constraints = '';
        while ($row = mysql_fetch_array($result)) {
            $result2 = mysql_query("SHOW CREATE TABLE " . $row[0] . ";", $db1) or die(mysql_error());
            $res = mysql_fetch_array($result2);
            if (preg_match("/[ ]*CONSTRAINT[ ]+.*\n/", $res[1], $matches)) {
                $res[1] = preg_replace("/,\n[ ]*CONSTRAINT[ ]+.*\n/", "\n", $res[1]);
                $constraints .= "ALTER TABLE " . $row[0] . " ADD " . trim($matches[0]) . ";\n";
            }
            $buf .= $res[1] . ";\n";
        }

        $buf .= $constraints;
        $buf .= "set foreign_key_checks = 1";

        /*         * ************** CREATE NEW DB WITH OLD SCHEMA *************** */
        $db2 = mysql_connect($DB_DST_HOST, $DB_DST_USER, $DB_DST_PASS) or die(mysql_error());
        $sql = 'CREATE DATABASE ' . $DB_DST_NAME;
        if (!mysql_query($sql, $db2))
            die(mysql_error());
        mysql_select_db($DB_DST_NAME, $db2) or die(mysql_error());
        $queries = explode(';', $buf);
        foreach ($queries as $query)
            if (!mysql_query($query, $db2))
                die(mysql_error());
    }

    // -------------------------------------------------------------------------
    // -- (ADD FIELD DB) -----------------------------------------------------
    public static function addNewField($Schema, $NumberOfSchemas, $Table, $Field, $Type) {
        require_once CORE_PATH . "/database.class.php";

        /*         * ******************* START CONFIGURATION ******************** */

        $i = 0;

        $DB_SRC_HOST = HOST;
        $DB_SRC_USER = USERNAME;
        $DB_SRC_PASS = PASSWORD;

        if ($NumberOfSchemas == 0) {
            $DB_SRC_NAME = $Schema;
            $db1 = mysql_connect($DB_SRC_HOST, $DB_SRC_USER, $DB_SRC_PASS) or die(mysql_error());
            mysql_select_db($DB_SRC_NAME, $db1) or die(mysql_error());
            $Query = mysql_query("ALTER TABLE " . $Table . " ADD " . $Field . " " . $Type, $db1) or die(mysql_error());

            //ADD TO DEVELOPE SCHEMA
            $DB_SRC_NAME = "omegaDev_system";
            $db1 = mysql_connect($DB_SRC_HOST, $DB_SRC_USER, $DB_SRC_PASS) or die(mysql_error());
            mysql_select_db($DB_SRC_NAME, $db1) or die(mysql_error());
            $Query = mysql_query("ALTER TABLE " . $Table . " ADD " . $Field . " " . $Type, $db1) or die(mysql_error());
        } else {
            for ($i = 0; $i <= $NumberOfSchemas; $i++) {
                $DB_SRC_NAME = $Schema . "_" . $i;
                $db1 = mysql_connect($DB_SRC_HOST, $DB_SRC_USER, $DB_SRC_PASS) or die(mysql_error());
                mysql_select_db($DB_SRC_NAME, $db1) or die(mysql_error());
                $Query = mysql_query("ALTER TABLE " . $Table . " ADD " . $Field . " " . $Type, $db1) or die(mysql_error());
            }

            //ADD TO DEVELOPE SCHEMA
            for ($i = 0; $i <= $NumberOfSchemas; $i++) {
                $DB_SRC_NAME = $Schema . "Dev_" . $i;
                $db1 = mysql_connect($DB_SRC_HOST, $DB_SRC_USER, $DB_SRC_PASS) or die(mysql_error());
                mysql_select_db($DB_SRC_NAME, $db1) or die(mysql_error());
                $Query = mysql_query("ALTER TABLE " . $Table . " ADD " . $Field . " " . $Type, $db1) or die(mysql_error());
            }
        }
    }

    // -------------------------------------------------------------------------
    // -- (GET NUM ROWS) -------------------------------------------------------
    public static function getNumRows() {
        if (!is_bool(static::$Result)) {
            return mysqli_num_rows(static::$Result);
        }
    }

    // -------------------------------------------------------------------------
    // -- (ESCAPE STRING) ------------------------------------------------------
    public static function escapeString($String) {
        require_once(CORE_PATH . '/database.class.php');

        /* $String = str_replace(" SELECT ", ":)", $String);
          $String = str_replace(" OR ", ":)", $String);
          $String = str_replace(" AND ", ":)", $String);
          $String = str_replace(" DELETE ", ":)", $String);
          $String = str_replace(" WHERE ", ":)", $String);
          $String = str_replace(" FROM ", ":)", $String); */

        return mysqli_real_escape_string(Database::mysqli()->getLink(), $String);
    }

    // -------------------------------------------------------------------------
    // -- (GET LAST ID) ------------------------------------------------------
    public static function lastID() {
        return mysqli_insert_id(Database::mysqli()->getLink());
    }

    // -------------------------------------------------------------------------
    // -- (SELECT) -------------------------------------------------------------
    public static function select() {
        static::$Query = "SELECT ";
        $num_args = func_num_args();

        if ($num_args == 0) {
            static::$Query .= "* ";
        } else if ($num_args == 1) {
            $alias = explode(" ", static::$Table);

            if (isset($alias[1])) {
                static::$Query .= $alias[1] . ".ID as ID, ";
            } else {
                static::$Query .= "ID, ";
            }

            $args_list = func_get_args();
            static::$Query .= $args_list[0] . " ";
        }
        static::$Query .= "FROM " . static::$Table . " ";

        return new static;
    }

    // -------------------------------------------------------------------------
    // -- (UPDATE) -------------------------------------------------------------
    public static function update() {
        static::$Query = "UPDATE " . static::$Table . " SET ";
        $num_args = func_num_args();

        if ($num_args == 1) {
            $args_list = func_get_args();
            static::$Query .= $args_list[0] . " ";
        }
        return new static;
    }

    // -------------------------------------------------------------------------   
    // -- (INSERT) -------------------------------------------------------------
    public static function insert() {
        static::$Query = "INSERT INTO " . static::$Table . " (";
        $num_args = func_num_args();

        if ($num_args == 1) {
            $args_list = func_get_args();
            static::$Query .= $args_list[0] . ") ";
        }

        return new static;
    }

    //-- (INSERT VALUES) -------------------------------------------------------
    public static function values() {
        static::$Query .= "VALUES (";
        $num_args = func_num_args();

        if ($num_args == 1) {
            $args_list = func_get_args();
            static::$Query .= $args_list[0] . ")";
        }

        return new static;
    }

    // -------------------------------------------------------------------------
    // 
    //-- (LIMIT) -------------------------------------------------------
    public static function limit() {
        static::$Query .= "LIMIT ";
        $num_args = func_num_args();

        if ($num_args == 1) {
            $args_list = func_get_args();
            static::$Query .= $args_list[0];
        }

        return new static;
    }

    // -------------------------------------------------------------------------
    // -- (DELETE) -------------------------------------------------------------
    public static function delete() {
        // Insted of delete user UPDATE Status = 'delete'
        static::$Query = "DELETE FROM " . static::$Table . " ";
        return new static;
    }

    // -------------------------------------------------------------------------   
    //-- (LEFT JOIN) -----------------------------------------------------------

    public static function leftJoin() {
        static::$LeftJoin = true;
        static::$Query .= "LEFT JOIN ";
        $num_args = func_num_args();

        if ($num_args == 1) {
            $args_list = func_get_args();
            static::$Query .= $args_list[0] . " ";
        } elseif ($num_args == 2) {
            $args_list = func_get_args();
            static::$Query .= $args_list[1] . "." . $args_list[0] . " ";
        }

        return new static;
    }

    // -------------------------------------------------------------------------   
    //-- (JOIN) -----------------------------------------------------------

    public static function join() {
        static::$Join = true;
        static::$Query .= "JOIN ";
        $num_args = func_num_args();

        if ($num_args == 1) {
            $args_list = func_get_args();
            static::$Query .= $args_list[0] . " ";
        } elseif ($num_args == 2) {
            $args_list = func_get_args();
            static::$Query .= $args_list[1] . "." . $args_list[0] . " ";
        }

        return new static;
    }

    // -------------------------------------------------------------------------
    //-- (LEFT JOIN ON ) -------------------------------------------------------

    public function on() {
        static::$Query .= "ON ";
        $num_args = func_num_args();

        if ($num_args == 1) {
            $args_list = func_get_args();
            static::$Query .= $args_list[0] . " ";
        }

        return new static;
    }

    // -------------------------------------------------------------------------
    // -- (WHERE) --------------------------------------------------------------
    public static function where() {
        static::$Query .= "WHERE ";
        $num_args = func_num_args();

        if ($num_args == 1) {
            $args_list = func_get_args();
            static::$Query .= $args_list[0] . " ";
        } else {
            die("Lack of conditions.");
        }
        return new static;
    }

    // -------------------------------------------------------------------------
    // -- (WHERE) --------------------------------------------------------------
    public static function having() {
        static::$Query .= "HAVING ";
        $num_args = func_num_args();

        if ($num_args == 1) {
            $args_list = func_get_args();
            static::$Query .= $args_list[0] . " ";
        } else {
            die("Lack of conditions.");
        }
        return new static;
    }

    // -------------------------------------------------------------------------
    // 
    // -- (GROUP BY) --------------------------------------------------------------
    public static function groupBy() {
        static::$Query .= "GROUP BY ";
        $num_args = func_num_args();

        if ($num_args == 1) {
            $args_list = func_get_args();
            static::$Query .= $args_list[0] . " ";
        } else {
            die("Lack of conditions.");
        }
        return new static;
    }

    // -------------------------------------------------------------------------
    // -- (ORDER BY) --------------------------------------------------------------
    public static function orderBy() {
        if (func_get_arg(0) != "") {
            static::$Query .= " ORDER BY ";
        } else {
            static::$Query .= "";
        }
        $num_args = func_num_args();

        if ($num_args == 1) {
            $args_list = func_get_args();
            static::$Query .= $args_list[0] . " ";
        } else {
            die("Lack of conditions.");
        }
        return new static;
    }

    // -------------------------------------------------------------------------
    // 
    // -- (ORDER BY FIELD) --------------------------------------------------------------
    public static function orderByField() {
        if (func_get_arg(0) != "") {
            static::$Query .= " ORDER BY FIELD ";
        } else {
            static::$Query .= "";
        }
        $num_args = func_num_args();

        if ($num_args == 1) {
            $args_list = func_get_args();
            static::$Query .= "(".$args_list[0] . ") ";
        } else {
            die("Lack of conditions.");
        }
        return new static;
    }

    // -------------------------------------------------------------------------
    // -- (EXECUTE) ------------------------------------------------------------
    public static function execute(&$Result = FALSE, $Array = TRUE, $Show = FALSE) {
        require_once CORE_PATH . "/database.class.php";

        // IF $array = true always returns array of objects, else returns object if result is one row
        // Example: get user by id -> use default execute();
        // IF $Show = true, print query on screen
        // IF $sql_log = true, save sql to DB, good for INSERT, UPDATE
        static::$Query .= ";";

        $QueryStr = explode(" ", static::$Query);

        // INSERT QUERY TO QUERY LOG TABLE
        if ($QueryStr[0] != "SELECT") {
            $Query = static::$Query;

            if (isset($_SESSION["UserID"])) {
                $UserID = $_SESSION["UserID"];
            } else {
                $UserID = 0;
            }

            //$Query = str_replace("'", "", $Query);
            //$q = "INSERT INTO query_log (UserID, QueryString, Timestamp) VALUES ('$UserID', '$Query', NOW())";
            //mysqli_query(Database::mysqli()->getLink(), $q) or die(mysqli_error(Database::mysqli()->getLink()));
        }

        // SHOW QUERY ON SCREEN
        if ($Show) {
            echo static::$Query;
        }

        // EXECUTE QUERY


        static::$Result = mysqli_query(Database::mysqli()->getLink(), static::$Query) or die(mysqli_error(Database::mysqli()->getLink()));

        // OTHER STUFF
        static::$Query = "";
        static::$Objects = null;

        //if($this->result) { return true; }
        if (static::getNumRows() == 0) {
            $Result = FALSE;
            return FALSE;
        }

        $Index = 0;
        $ID = 0;
        while ($Row = mysqli_fetch_object(static::$Result)) {

            if (static::$LeftJoin == true || static::$Join == true) {
                static::$Objects[$Index] = $Row;
            } else {
                static::$Objects[$Row->ID] = $Row;
                $ID = $Row->ID;
            }
            $Index++;
        }
        static::$LeftJoin = false;
        static::$Join = false;

        if ($Array == TRUE) {
            $Result = static::$Objects;
        } else {
            if (static::getNumRows() == 1) {
                $Result = static::$Objects[$ID];
            } else {
                $Result = static::$Objects;
            }
        }
        /*
          if(static::getNumRows() == 0) {
          $Result = FALSE;
          } else if(static::getNumRows() == 1) {
          $Result = static::$Objects[$ID];
          } else if(static::getNumRows() > 1) {
          $Result = static::$Objects;
          } */
    }

    // -------------------------------------------------------------------------
}
