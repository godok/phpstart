<?php
defined('IS_RUN') or exit('/**error:404**/');
/**
 *  模板解析缓存
 */
final class template {
	

	/**
	 * 编译模版文件
	 *
	 * @param $tplfile	模板原文件路径
	 * @param $compiledtplfile	编译完成后，写入文件名
	 * @return $strlen 长度
	 */
	public function refresh($tplfile, $compiledtplfile) {
		$str = @file_get_contents ($tplfile);
		$str = $this->parse ($str);
		$strlen = file_put_contents ($compiledtplfile, $str );
		chmod ($compiledtplfile, 0777);
		return $strlen;
	}
	

	/**
	 * 解析模板
	 *
	 * @param $str	模板内容
	 * @return ture
	 */
	public function parse($str) {
	    if(!strpos($str,'<!--no-php//-->')){
    		$str = preg_replace ( "/\{template\s+(.+)\}/", "<?php include template(\\1); ?>", $str );
    		$str = preg_replace ( "/\{include\s+(.+)\}/", "<?php include \\1; ?>", $str );
    		$str = preg_replace ( "/\{php\s+(.+?)\}/", "<?php \\1?>", $str );
    		$str = preg_replace ( "/\{if\s+(.+?)\}/", "<?php if(\\1) { ?>", $str );
    		$str = preg_replace ( "/\{else\}/", "<?php } else { ?>", $str );
    		$str = preg_replace ( "/\{elseif\s+(.+?)\}/", "<?php } elseif (\\1) { ?>", $str );
    		$str = preg_replace ( "/\{\/if\}/", "<?php } ?>", $str );
    		//for 循环
    		$str = preg_replace("/\{for\s+(.+?)\}/","<?php for(\\1) { ?>",$str);
    		$str = preg_replace("/\{\/for\}/","<?php } ?>",$str);
    		//++ --
    		$str = preg_replace("/\{\+\+(.+?)\}/","<?php ++\\1; ?>",$str);
    		$str = preg_replace("/\{\-\-(.+?)\}/","<?php ++\\1; ?>",$str);
    		$str = preg_replace("/\{(.+?)\+\+\}/","<?php \\1++; ?>",$str);
    		$str = preg_replace("/\{(.+?)\-\-\}/","<?php \\1--; ?>",$str);
    		$str = preg_replace ( "/\{loop\s+(\S+)\s+(\S+)\}/", "<?php \$n=1;if(is_array(\\1)) foreach(\\1 AS \\2) { ?>", $str );
    		$str = preg_replace ( "/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}/", "<?php \$n=1; if(is_array(\\1)) foreach(\\1 AS \\2 => \\3) { ?>", $str );
    		$str = preg_replace ( "/\{\/loop\}/", "<?php \$n++;}unset(\$n); ?>", $str );
    		$str = preg_replace ( "/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(([^{}]*)\))\}/", "<?php echo \\1;?>", $str );
    		$str = preg_replace ( "/\{\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(([^{}]*)\))\}/", "<?php echo \\1;?>", $str );
    		$str = preg_replace ( "/\{(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/", "<?php echo \\1;?>", $str );
    		if(function_exists('preg_replace_callback')){
    		    $str = preg_replace_callback("/\{(\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\}/s", array($this, 'ifTag'),$str);
    		}else{
    		    $str = preg_replace("/\{(\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\}/es", "\$this->addquote('<?php echo \\1;?>')",$str);
    		}
    		$str = preg_replace ( "/\{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)\}/s", "<?php echo \\1;?>", $str );
	    }

		$str = "<?php defined('IS_RUN') or exit('No permission resources.'); ?>" . $str;
		return $str;
	}
	protected function ifTag($matches) {
	    return $this->addquote("<?php echo ".$matches[1]).";?>";
	}

	/**
	 * 转义 // 为 /
	 *
	 * @param $var	转义的字符
	 * @return 转义后的字符
	 */
	public function addquote($var) {
		return str_replace ( "\\\"", "\"", preg_replace ( "/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var ) );
	}
	

}
?>