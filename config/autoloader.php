<?php
ob_start();


require_once(CORE_PATH . '/query.class.php');
require_once(CORE_PATH . '/template.class.php');
require_once(HELPERS_PATH . '/helper.class.php');


if (is_dir(MODELS_PATH)) {
    if ($dh = opendir(MODELS_PATH)) {
        while (($file = readdir($dh)) !== false) {
            if ($file != '.' && $file != '..') {
                require_once(MODELS_PATH . '/' . $file);
            }
        }
        closedir($dh);
    }
}

$LoadClasses = ob_get_contents();  
ob_end_clean();
echo $LoadClasses;