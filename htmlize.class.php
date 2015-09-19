<?php

/* example for call_user_func */

function dummy_func() { return file_get_contents('dummy.txt'); }

/*
 * htmlize
 *
 * (c) tacoen, dibuat untuk mendukung pemalasan diri.
 */

class htmlize {

	public function __construct() {
		$this->q=''; //query
	}

	public function start($func=NULL,$array_passed=array()) { 
		if ((isset($func)) && (function_exists($func."_func"))) { call_user_func($func."_func",$array_passed); } 
		echo "<html>\n"; 
	}

	public function end($func=NULL,$array_passed=array()) { 
		if ((isset($func)) && (function_exists($func."_func"))) { call_user_func($func."_func",$array_passed); } 
		echo "</html>"; 
	}
	
	public function create($w,$id=NULL,$attr=NULL) { 
		if (isset($id)) { $this->q[$w]['id']=$id; }
		if (isset($attr)) { $this->q[$w]['attr']=$attr; }
		$this->q[$w]['html']='';
	}
	
	public function query($w,$func=NULL,$array_passed=array()) { 
		if (isset($func)) { 
			if ( function_exists($func."_func")) { $html=call_user_func($func."_func",$array_passed); } else { $html=$func; }
		}
		$this->q[$w]['html'] .=$html; 
	}
	
	public function append($w,$s){ $this->q[$w]['html'] .=$s; }

	public function build($w,$func=NULL,$array_passed=array()) {
		
		if ( isset($this->q[$w])) { $el=$this->q[$w]; } else { $el=array(); }
		
		$html="<$w";
		if (isset($el['id'])) { $html .=" id='".$el['id']."'"; } 
		if (isset($el['attr'])) { $html .=" ".$el['attr']; }
		$html .=">\n";
		$html .=$el['html'];
		if (isset($func)) { 
			if ( function_exists($func."_func")) { $html .=call_user_func($func."_func",$array_passed); }
		}
		$html .="</$w>\n";
			
		echo $html;
	}
	
}

class formize {
	
	public function __construct($action,$method) { 
		$this->field=array();
		$this->index=0;
		$this->action=$action;
		$this->method=$method;
	}
	
	public function input($name,$val,$type) {
		switch($type) {
			case 'submit':
				$html='';
				break;
			case 'checkbox':
				$html="<input type='$type' name='$name' value='$val' /> $val";
				break;
			case 'radio':
				$html="<input type='$type' name='$name' value='$val' /> $val";
				break;
			default:
				$html="<input type='$type' name='$name' value='$val' />";
				break;
		}
		return $html;
	}
	
	public function select($name,$val,$option) {
		$html="<select name='$name'>";
		foreach($option as $o) { 
			if ($o==$val) { $s=" selected"; } else { $s=''; }
			$html .="<option$s value='$o'>$o</option>";
		}
		$html .="</select>";	
		return $html;
	}
	
	public function add_attribute($set,$name,$attr) {
		$this->field[$set][$name]['attr']=$attr;
	}
	
	public function query($set,$name,$el,$val,$type='text',$label=NULL,$option=array(),$order=NULL) {
		
		$i=$this->index; $html='';
		
		if ($order) { $this->field[$set][$name]['order']=$order; } else { $this->field[$set][$name]['order']=$i; }
		if ($label) { $this->field[$set][$name]['label']=$label; }
		
		switch($el) {
			case 'select':
				$html .=$this->select($name,$val,$option);
				break;
			case 'textarea':
				$html .="<textarea name='$name'>$val</textarea>";
				break;
			default:
				$html .=$this->input($name,$val,$type);
				break;
		}
		
		$this->field[$set][$name]['element']=$html;
		$this->index++;
	}

	public function fieldset($w='') {
		$html="<fieldset name='$w'>\n";
		$html .="<legend>$w</legend>\n";
		foreach ( $this->field[$w] as $n) { 
			$html .="<p>";
			if ($n['label']) { $html .="<label>". $n['label'] ."</label>"; }
			if (isset($n['attr'])) { $n['element']=preg_replace ('/(^<\w+.*)(\/>)/',"$1".$n['attr'] ." $2", $n['element'] ); }
			$html .="<span class='value'>".$n['element']."</span>";
			$html .="</p>\n";
		}
		$html .="</fieldset>\n"; 
		return $html;
	}
	
	public function submitter($with_cancel=NULL) {
		$html="<p class='submit'>";
		$html .="<input type='submit' value='submit' name='submit' />";
		if ($with_cancel) { $html .="<input type='cancel' value='cancel' name='submit' />"; }
		$html .="</p>\n";
		return $html;
	}
	
	public function form($set=array(),$id) {
		$html="<form id='".$id."' action='". $this->action . "' method='". $this->method ."'>\n";
		foreach ($set as $s) { $html .=$this->fieldset($s); }
		$html .=$this->submitter();
		$html .="</form>\n";
		return $html;
	}
	
}

class stylize {

	public function __construct() { 
		$this->css=array(); 
		$this->order=0;
	}

	public function query($obj,$url,$media,$n=NULL) {
		if (!$n) { $order=$this->order; } else { $order=$n; }
		$this->css=array_merge_recursive ( $this->css, array ( $obj=> array( 'order'=> $order, 'name'=> $obj, 'media'=> $media, 'url'=> $url ) ) );
		$this->order++;
	}

	public function html() {
		$html='';
		$css_toprint=$this->css; sort($css_toprint);
		foreach ($css_toprint as $k=> $v) { $html .="<link rel='stylesheet' type='text/css' media='".$v['media']."' id='css_".htmlize_sfst($v['name'])."' href='".$v['url']."' />\n"; }
		if (file_exists(ROOT_PATH."style.css")) { $html .="<link rel='stylesheet' type='text/css' id='css_dev' href='".ROOT_URL."style.css?".time()."' />\n"; }
		return $html;
	}
	
}	

class scriptize {

	public function __construct() { 
		$this->js=array(); 
		$this->order=0;
	}

	public function query($obj,$url,$n=NULL, $after=false) {
		if (!$n) { $order=$this->order; } else { $order=$n; }
		$this->js=array_merge_recursive ( $this->js, array ( $obj=> array( 'order'=> $order, 'name'=> $obj, 'url'=> $url, 'after'=> $after ) ) );
		$this->order++;
	}

	public function html($after=false,$temp='') {
		$html='';
		$js_toprint=$this->js; sort($js_toprint);
		if ($temp!='') { $t="?".time(); } else { $t=''; }
		foreach ($js_toprint as $k=> $v) {
			if ($after==$v['after']) {
				$html .="<script type='text/javascript' id='js_".htmlize_sfst($v['name'])."' src='".$v['url'].$t."' /></script>\n"; 
			}
		}

		if (($after==true) && (file_exists(ROOT_PATH."function.js"))) { 
			$html .="<script type='text/javascript' id='js_dev' href='".ROOT_URL."function.js".$t."' /></script>\n"; 
		}
	
		return $html;
	}
	
}

/* utility -------------------------------------------- */

function htmlize_sfst($s){ return preg_replace('/\W|css|js/','',$s); }
function htmlize_compress($s){ $s=preg_replace('/\n|\r|\t/i','',$s); $s=preg_replace('/\s+/',' ',$s); return $s; }

?>
