# swm-libs

A collection of class to make web pages creation easier and will being manageable

## Basic Idea

What this lib do?

### php
```
$h=new htmlize(); 
$h->create_container('head','head'); 
$h->add('head',"<meta charset='UTF-8' />"); 
$h->script_query('head','picocss','prop/js/picocss.js');
$h->style_query('head','reset','prop/reset.css','all'); 
echo $h->build('head'); 
```

#### $h->create_container( $container , $elemenet ); 

Create $container within $elemenet

#### $h->add( $container , $htmlLine ); 

Add $htmlLine to $container

#### $h->script_query( $container , $name , $script_url ); 

Query script $script_url as $name in $container

#### $h->style_query( $container , $name, $style_url , $media ); 

Query script $style_url as $name in $container for $media

### Output

The outpur shall be

```
<head>
<meta charset='UTF-8' />
<script type='text/javascript' id='js_pico' src='prop/js/picocss.js'></script>
<link rel='stylesheet' type='text/css' media='all' id='css_reset' href='prop/reset.css'/>
</head>
```



## Example

ok, now in full example

```
$h=new htmlize(); 
$h->create_container('head','head'); 
$h->create_container('foot','div',"class='foot__'"); 
$h->add('head',"<meta name='viewport' content='user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1' />"); 
$h->add('head',"<meta charset='UTF-8' />"); 
$h->script_query('head','picocss','prop/js/picocss.js'); 
$h->script_query('head','swipe','prop/js/swipeme.js'); 
$h->script_query('head','swipe_mouse','prop/js/swipeme.mouse.min.js'); 
$h->script_query('head','nano.js','prop/js/nanoajax.js',1); 
$h->script_query('foot','function','prop/function.js'); 
if (file_exists(ROOT_PATH."function.js")) { 
	$h->add('foot',"<script type='text/javascript' id='js_dev' href='".ROOT_URL."function.js' /></script>"); 
}

$h->style_query('head','reset','prop/reset.css','all'); 
$h->style_query('head','font','prop/icmat/style.css', "all"); 
$h->style_query('head','style','prop/style.css','all'); 
if (file_exists(ROOT_PATH."style.css")) { 
	$h->add('head',"<link rel='stylesheet' type='text/css' id='css_dev' href='".ROOT_URL."style.css' />"); 
}
$h->add('head',"<title>$title</title>"); 
echo $h->build('head'); 
echo $h->build('foot'); 
```
