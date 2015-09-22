<?php 
require_once('htmlize.class.php');

error_reporting(E_ALL); 
ini_set('display_errors', 1); 
function custom_exception($e) { echo PHP_EOL ."<!-- Exception: " . $e->getMessage(). "-->".PHP_EOL; } 
function custom_error($en, $es, $ef, $el, $errcontext){ echo PHP_EOL ."<!-- $ef:$el - Err[$en]: $es -->".PHP_EOL; }
set_error_handler("custom_error"); 
set_exception_handler('custom_exception'); 

define("ROOT_PATH",dirname(realpath($_SERVER['SCRIPT_FILENAME']))."/" ); 
define("ROOT_URL","http://".@$_SERVER['SERVER_NAME']."/".preg_replace("|".$_SERVER['DOCUMENT_ROOT']."|",'',ROOT_PATH)); 
define("HTMLIZE_CACHE_PATH",ROOT_PATH."cache/"); 

$title = "demo"; 

$h=new htmlize(); 

/* Snipset #1 */

$h->create_container('head','head'); 
$h->add('head',"<meta name='viewport' content='user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1' />"); 
$h->add('head',"<meta charset='UTF-8' />"); 
$h->script_query('head','picocss','prop/js/picocss.js'); 
$h->script_query('head','swipe','prop/js/swipeme.js'); 
$h->script_query('head','swipe_mouse','prop/js/swipeme.mouse.min.js'); 
$h->script_query('head','nano.js','prop/js/nanoajax.js',1); 
$h->style_query('head','reset','prop/reset.css','all'); 
$h->style_query('head','font','prop/icmat/style.css', "all"); 
$h->style_query('head','style','prop/style.css','all'); 
if (file_exists(ROOT_PATH."style.css")) { $h->add('head',"<link rel='stylesheet' type='text/css' id='css_dev' href='".ROOT_URL."style.css' />"); }
$h->add('head',"<title>$title</title>"); 

/* Snipset #2 */

$h->create_container('foot','div',"class='foot__'"); 
$h->script_query('foot','function','prop/function.js'); 
if (file_exists(ROOT_PATH."function.js")) { $h->add('foot',"<script type='text/javascript' id='js_dev' href='".ROOT_URL."function.js' /></script>"); }

/* Snipset #3 */

$h->create_container('form1','form',"action='index.php' method='post'"); 
$h->form_query('form1','input','nama','text','Smith','Full Name');
$h->form_query('form1','select','sex',NULL,'male','Sex',array('male','female') );
$h->form_query('form1','textarea','address',NULL,'test','Address',NULL,array('input','output') );
$h->fq_submitter('form1','submit');
$h->fq_addAttribute('form1','nama',"autocompelete='off'");

/* Snipset #4 */

$h->create_container('content','div'); 
$h->set_cache('content',false); 
$h->query('content','h1','Some Demo',NULL,1); 
$h->query('content','p','test',NULL,NULL,array('header','footer')); 
$h->query('content','div','This element got some style attribute and red',"style='border=1px solid red'"); 
$h->query('content','p','Lorem Ipsum you say it.',NULL,2); 
$h->query('content','p','and what ever'); 
$h->add('content',"<div class='clear'></div>"); 

/* Skelton */

echo "<html>";

echo $h->build('head');  /* Snipset #1 */

echo "<body>";

echo $h->build('content'); /* Snipset #4 */
echo $h->build('form1'); /* Snipset #3 */
echo $h->build('foot'); /* Snipset #2 */

echo "</body>";
echo "</html>";

exit;

function test_func($w) {
	return "$w[0] and $w[1] are friendly element";
}

?>
