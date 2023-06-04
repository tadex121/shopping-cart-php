<?php
ob_start();

require_once('../../../config/common.php');
require_once('../../../core/query.class.php');
require_once('../../../core/database.class.php');
require_once('../../../core/template.class.php');

require_once('../../../application/helpers/helper.class.php');


$LoadClasses = ob_get_contents();  
ob_end_clean();
echo $LoadClasses;