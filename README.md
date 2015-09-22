# swm-libs

## php

```
$h=new htmlize(); 
$h->create_container('head','head'); 
$h->add('head',"<meta charset='UTF-8' />"); 
$h->script_query('head','picocss','prop/js/picocss.js'); 
echo $h->build('head'); 
```

## Output

```
<head>
<meta charset='UTF-8' />
<script type='text/javascript' id='js_pico' src='prop/js/picocss.js'></script>
</head>
```
