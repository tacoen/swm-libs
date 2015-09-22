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

Ok, now for the wiki
