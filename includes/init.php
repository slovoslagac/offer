<?php
/**
 * Created by PhpStorm.
 * User: petar
 * Date: 20.9.2018.
 * Time: 14:26
 */

//win DS = "\", Mac/Linux DS = "/"
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

defined('SITE_ROOT') ? null : define('SITE_ROOT', 'C:' . DS . 'AppServ' . DS . 'www' . DS . 'offer');
defined('ADMIN_PATH') ? null : define('ADMIN_PATH', SITE_ROOT .  DS . 'includes');
defined('CLASS_PATH') ? null : define('CLASS_PATH', ADMIN_PATH .  DS . 'classes');
defined('LAYOUT_PATH') ? null : define('LAYOUT_PATH', ADMIN_PATH .  DS . 'layouts');
defined('LIB_PATH') ? null : define('LIB_PATH', ADMIN_PATH .  DS . 'lib');
defined('CONNECTION_PATH') ? null : define('CONNECTION_PATH', ADMIN_PATH .  DS . 'conn');



//Layouts
$head = LAYOUT_PATH.DS.'head.php';
$leftmenu = LAYOUT_PATH.DS.'leftmenu.php';
$rightmenu = LAYOUT_PATH.DS.'rightmenu.php';



//Includes
include ADMIN_PATH . DS . 'functions.php';
include LIB_PATH . DS . 'simple_html_dom.php';
include CONNECTION_PATH.DS.'mysqlNewPDO.php';


//Classes

require CLASS_PATH.DS.'xscoresMatch.php';