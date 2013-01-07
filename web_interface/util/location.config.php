<?php
/**************************************************
 * Place all the path locations of SKEMO here ^__^
 * ------------------------------------------------
 **************************************************/


/**
 * IMAGES
 *
 */
define('IMG_SKEMO_LOGO','images/1skemo_logo.png');
define('IMG_SKEMO_FAVICON','images/favicon_skemo.ico');
/**
 * JAVASCRIPT FILES FOR SKEMO
 *
 */
define('JS_SKEMO','js/skemo.js');
define('JS_MOOTOOLS','js/mootools1.2.js');
define('JS_HELPER_TOOLKIT','js/helpers javascript toolkit/helpers.toolkit.js');
define('JS_JSON','js/json2.js');
define('JS_DEFAULT','js/default.js');
define('JS_PRINT','js/print.js');
define('JS_SAVE','js/save.js');
define('JS_RESULT','js/result.js');

/**
 * MAJOR PAGES LINKS
 */
define('INDEX','index.php');
define('MYSKEMO','mySkemo.php');
define('MYPROFILE','myProfile.php');
define('SKEMO_BROWSE','skemoBrowse.php');
define('RESULT','result.php');
define('SKEMO_NOW','skemoNow.php');
define('DEVELOPER','developer.php');
define('LOGIN','login.php');
/**
 * CONTROLLER FILES
 */
define('CTR_RESULT','controller/result.ctr.php');

/**
 * UTILITIES
 */
define('UTIL_HELPERS','util/helpers.inc.php');
define('UTIL_DB_CONN','util/db_conn.inc.php');

/**
 * MARKUPS (usually used by javascript)
 */
define('UTIL_LOADING_SCREEN_MARKUP','util/loadingScreen.inc.php');
define('UTIL_DIALOG_MARKUP','util/dialog.inc.php');

/**
 * SKEMO CLASSES
 */
define('CLASS_SKEMO_PREFERENCE_HANDLER','classes/SKEMO_Preference_Handler.class.php');
define('CLASS_SKEMO_TIME_HANDLER','classes/SKEMO_TimeHandler.class.php');
define('CLASS_SKEMO_SUBJECT_DESC','classes/SKEMO_Subject_Desc.class.php');
define('CLASS_SKEMO_PAGER','classes/SKEMO_Pager.class.php');

/**
 * FUNCTIONS
 */
define('UTIL_TIME_CONVERTERS','util/skemoTimeConverters.inc.php');

/**
 * MODEL FILES
 */
define('MODEL_SKEMO_SCHEDULE_MASTER','model/SKEMO_Schedule_Master.mod.php');

/**
 * TEMPLATE ENGINE CLASS
 */
define('SKEMO_TEMPLATE_ENGINE','tengine/template.class.php');
/**
 * REDIRECTION SCRIPT
 */
define('SKEMO_NOT_ALLOWED','index.php');
?>