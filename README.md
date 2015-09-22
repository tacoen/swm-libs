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

[The rest is on wiki](https://github.com/tacoen/swm-libs/wiki)

## htmlize.demo.php

See htmlize.demo.php, and this is there output:
```
<html><head>
<meta charset='UTF-8' />
<meta name='viewport' content='user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1' />
<script type='text/javascript' id='js_nano' src='prop/js/nanoajax.js'></script>
<script type='text/javascript' id='js_pico' src='prop/js/picocss.js'></script>
<script type='text/javascript' id='js_swipe' src='prop/js/swipeme.js'></script>
<script type='text/javascript' id='js_swipe_mouse' src='prop/js/swipeme.mouse.min.js'></script>
<link rel='stylesheet' type='text/css' media='all' id='css_reset' href='prop/reset.css'/>
<link rel='stylesheet' type='text/css' media='all' id='css_font' href='prop/icmat/style.css'/>
<link rel='stylesheet' type='text/css' media='all' id='css_style' href='prop/style.css'/>
<link rel='stylesheet' type='text/css' id='css_dev' href='http://mydomain.com/hotspot/09/style.css' />
<title>demo</title>
</head>
<body><div>
<h1>Some Demo</h1>
<p>header and footer are friendly element</p>
<div style='border=1px solid red'>This element got some style attribute and red</div>
<p>Lorem Ipsum you say it.</p>
<p>and what ever</p>
<div class='clear'></div>
</div>
<form action='index.php' method='post'>
<p><label>Full Name</label><span class='value'><input type='text' autocompelete='off' name='nama' value='Smith' /></span></p>
<p><label>Sex</label><span class='value'><select name='sex'><option selected value='male'>male</option><option value='female'>female</option></select></span></p>
<p><label>Address</label><span class='value'><textarea name='address'>input and output are friendly element</textarea></span></p>
<p class='submit'><input type='submit' value='Submit' name='submit' /></p>
</form>
<div class='foot__'>
<script type='text/javascript' id='js_function' src='prop/function.js'></script>
<script type='text/javascript' id='js_dev' href='http://mydomain.com/hotspot/09/function.js' /></script>
</div>
</body></html>
```

