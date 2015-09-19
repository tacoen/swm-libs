<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define("ROOT_PATH",dirname(realpath($_SERVER['SCRIPT_FILENAME']))."/" );
define("ROOT_URL","http://".@$_SERVER['SERVER_NAME']."/".preg_replace("|".$_SERVER['DOCUMENT_ROOT']."|",'',ROOT_PATH) );

require_once('htmlize.class.php');

$css = new stylize();
$css->query('reset','prop/reset.css','all',0);
$css->query('font','prop/icmat/style.css', "all",1);
//$css->query('style','prop/style.css','all',2);

$js = new scriptize();
$js->query('picocss','prop/js/picocss.js',0);
$js->query('swipe','prop/js/swipeme.js');
$js->query('swipe_mouse','prop/js/swipeme.mouse.min.js');
$js->query('nano.js','prop/js/nanoajax.js',1);
$js->query('function','prop/function.js',NULL,true);

$form = new formize('index.php','post');
$form->query('data','address','textarea','testing',NULL,'Address',NULL,3);
$form->query('data','nama','input','Smith','text','Full Name');
$form->query('data','sex','select','male',NULL,'Sex',array('male','female'));
$form->add_attribute('data','nama','autocomplete=off');

$html = new htmlize(); 

$title = 'htmlize example'; 

$html->create("head");
$html->query("head","<meta name='viewport' content='user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1' />"."\n");
$html->query("head","<meta charset='UTF-8'>\n");
$html->query("head",$js->html());
$html->query("head",$css->html());
$html->query("head","<title>$title</title>\n");

$html->create("body","some-id");
$html->query('body',$form->form(array('data'),'cpna') );
$html->append("body",$js->html(true));

$html->start();
$html->build('head');
$html->build('body');
$html->end();

exit;
