<?php

class T {
    
    private static $MainFolderName;
    private static $PartialFolderName;
    private static $PartialTplFilename;
    private static $Values = array();  
    private static $MainValues = array();  
    
    public static function load($PartialTPL) {
        $Partial = explode('/', $PartialTPL);
        static::$MainFolderName = $Partial[0];
        static::$PartialFolderName = $Partial[1];
        static::$PartialTplFilename = $Partial[2];
        
        if (count($Partial) > 3) {
            $tmp = [];
            foreach ($Partial as $k => $v) {
                if ($k > 0 && $k < count($Partial) - 1) $tmp[] = $v;
            }
            static::$PartialFolderName = implode('/', $tmp);
            static::$PartialTplFilename = $Partial[count($Partial)-1];
        }
        
        return new static;
    }
    
    public static function set($Key, $Value) {
        static::$Values[$Key] = $Value;
        return new static;
    }
    
    public static function setToMain($Key, $Value) {
        static::$MainValues[$Key] = $Value;
        return new static;
    }
    
    public static function render($MainTPL = FALSE) {
        if($MainTPL == FALSE) {
            $MainFolderName = static::$MainFolderName;
            $MainTplFilename = static::$MainFolderName . ".php";
        } else {
            $Main = explode("/", $MainTPL);
            $MainFolderName = $Main[0];
            $MainTplFilename = $Main[1];
        }
        
        $PathToPartialTemplate = VIEWS_PATH . "/" . static::$MainFolderName . "/" . static::$PartialFolderName . "/" . static::$PartialTplFilename;
        $PartialTemplateRendered = static::template_processor($PathToPartialTemplate, static::$Values);
        
        
        $MainValue = array(
            "Content" => $PartialTemplateRendered
        );
 
        $MainValues = array_merge(static::$MainValues, $MainValue);
        $PathToMainTemplate = VIEWS_PATH . "/" . $MainFolderName . "/" . $MainTplFilename;  
        echo static::template_processor($PathToMainTemplate, $MainValues);
    }
    
    // Pure PHP Template Processor
    private static function template_processor($tpl_file, $vars = array(), $include_globals = true) {   
        extract($vars);
        if ($include_globals) { extract($GLOBALS, EXTR_SKIP); }
        ob_start();
        require($tpl_file);
        $applied_template = ob_get_contents();  
        ob_end_clean();
        return $applied_template;
    }
}