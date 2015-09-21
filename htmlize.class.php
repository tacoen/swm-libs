<?php
/*
 * htmlize
 *
 * (c) tacoen, dibuat untuk mendukung pemalasan diri.
 */


/* -- User function -------------------------------------------------------------------------------------- */

function test_func($w) {
	return "$w[0] and $w[1] are friendly element";
}

/* -- Class -------------------------------------------------------------------------------------- */

class htmlize {

	public function __construct() {
		$this->_script=array();
		$this->_style=array();
	}

	public function create_container($container,$tag="div",$attr=NULL) {
		$this->{$container}['index']=0;
		$this->{$container}['tag']=$tag;
		$this->{$container}['attr']=$attr;
		$this->{$container}['cache']=NULL;
	}

	public function set_cache($container,$mode=false,$expire=0) {
		if (!isset($this->{$container})) { throw new Exception(__METHOD__." '$container' not found"); }
		$this->{$container}['file']="_".get_class($this)."_".$container;
		if ($mode==false) {
			$this->{$container}['cache'] = 0;
		} else {
			if ($expire==0) { htmlize_delCache($this->{$container}['file']); }
			$this->{$container}['cache']=htmlize_valCache($this->{$container}['file'], $expire, $mode);
		}
	}

	public function query($container,$element,$func=NULL,$attr=NULL,$order=NULL,$array_passed=array(),$enclose=false ) {
		if (!isset($this->{$container})) { throw new Exception(__METHOD__." '$container' not found"); }
		if ($this->{$container}['cache']==1 ) { return; }
		$i=$this->{$container}['index']++; if (!$order) { $order=$i; }
		$this->{$container}['Q'][$i]['o']=$order;
		$html="<$element"; if($attr) { $html .=" $attr"; }
		if ($enclose==false) { $html .=">"; } else { $html .="/>"; }
		if (function_exists($func."_func")) { $html .=call_user_func($func."_func",$array_passed); } else { $html .=$func; }
		if ($enclose==false) { $html .="</$element>"; }
		$this->{$container}['Q'][$i]['h']=$html;
		sort($this->{$container}['Q']);
	}

	public function add($container,$func,$name=NULL,$array_passed=array()) {
		if (!isset($this->{$container})) { throw new Exception(__METHOD__." '$container' not found"); }
		if ($this->{$container}['cache']==1 ) { return; }
		$i=$this->{$container}['index']++;
		if (function_exists($func."_func")) { $html =call_user_func($func."_func",$array_passed); } else { $html =$func; }
		$this->{$container}['Q'][$i]['h']=$html;
		if ($name) { $this->{$container}['Q'][$i]['n']=$name; }
	}

	public function build($container,$close=true) {

		if (!isset($this->{$container})) { throw new Exception(__METHOD__." '$container' not found"); }
		if ($this->{$container}['cache']==1 ) {
			$this->{$container}['action']="read";
			return htmlize_getCache($this->{$container}['file']);
		}

		$html='';

		if ($this->{$container}['tag'] ) {
			$html .="<".$this->{$container}['tag'];
			if ($this->{$container}['attr']) { $html .=" ".$this->{$container}['attr']; }
			$html .=">".PHP_EOL; $has_tag=true;
		}

		foreach ($this->{$container}['Q'] as $q ) { $html .=$q['h'].PHP_EOL; }

		if ($has_tag) { $html .="</".$this->{$container}['tag'].">".PHP_EOL; }

		if ($this->{$container}['cache']==2 ) {
			$this->{$container}['action']=htmlize_putCache($this->{$container}['file'],$html);
		}

		return $html;
	}

/* -- more ------------------------------------------------------------------------------------------------- */

	public function style_query($container ,$name, $url, $media, $order=NULL) {
		$this->query($container,'link',NULL,"rel='stylesheet' type='text/css' media='$media' id='css_".htmlize_sfst($name)."' href='$url'",$order, NULL, true);
		$this->_style[] = $url;
	}

	public function script_query($container ,$name, $url ,$order=NULL) {
		$this->query($container,'script',NULL,"type='text/javascript' id='js_".htmlize_sfst($name)."' src='$url'",$order);
		$this->_script[] = $url; // collect with preg match ^http://.+?\w+?/
	}

/* -- forms ------------------------------------------------------------------------------------------------ */

	public function fq_input($name,$val,$type) {
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

	public function fq_select($name,$val,$option) {
		$html="<select name='$name'>";
		foreach($option as $o) {
			if ($o==$val) { $s=" selected"; } else { $s=''; }
			$html .="<option$s value='$o'>$o</option>";
		}
		$html .="</select>";
		return $html;
	}

	public function fq_submitter($container,$name=NULL,$with_cancel=NULL) {
		$html="<p class='submit'>";
		$html .="<input type='submit' value='Submit' name='submit' />";
		if ($with_cancel) { $html .="<input type='cancel' value='Cancel' name='submit' />"; }
		$html .="</p>";
		$this->add($container,$html,$name);
	}

	public function form_query($container,$el='input',$name,$type='text',$func,$label=NULL,$option=array(),$array_passed=array() ) {

		if (function_exists($func."_func")) { $val =call_user_func($func."_func",$array_passed); } else { $val = $func; }

		$html = "<p>"; if ($label) { $html .="<label>$label</label>"; }
		$html .="<span class='value'>";

		switch($el) {
			case 'select':
				$html .= $this->fq_select($name,$val,$option);
				break;
			case 'textarea':
				$html .="<textarea name='$name'>$val</textarea>";
				break;
			default:
				$html .=$this->fq_input($name,$val,$type);
				break;
		}

		$html .= "</span></p>";
		$this->add($container,$html,$name);

	}

	public function fq_addAttribute($container,$name,$attr) {
		$Q = $this->{$container}['Q']; $n=0; $target=NULL;
		foreach ($this->{$container}['Q'] as $Q) {
			if ($name == $Q['n']) { $target = $Q['h']; $i=$n; }
			$n++;
		}

		$new_h = preg_replace ('/(^<\w+.*)(name=)/i',"$1".$attr." $2", $target );
		$this->{$container}['Q'][$i]['h'] = $new_h;
	}
}

/* -- Utility ------------------------------------------------------------------------------------------------- */

function htmlize_sfst($s){ return preg_replace('/\W|css|js/','',$s); }
function htmlize_epra($s){ echo "<pre><xmp>"; print_r($s); echo "</xmp></pre>"; }
function htmlize_delCache($fn) { $fn=HTMLIZE_CACHE_PATH.$fn.".html"; if (file_exists($fn)) { unlink($fn); } }
function htmlize_getCache($fn) { return file_get_contents(HTMLIZE_CACHE_PATH.$fn.".html"); }
function htmlize_putCache($fn,$s) {
	//$s="<!--".time()."--->". PHP_EOL . $s;
	file_put_contents(HTMLIZE_CACHE_PATH.$fn.".html" ,$s);
	return 'write';
}

function htmlize_valCache($fn,$expire=0,$mode=0) {
		$fn=HTMLIZE_CACHE_PATH.$fn.".html";
		if (file_exists($fn)) {
			if ($expire > (time() - filemtime($fn))) { return 1; } else { return 0; }
		} else {
			if ($mode==true) { return 2; } else { return 0; }
		}
}

?>
