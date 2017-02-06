<?php

namespace includes\classes;

/**
 * 模版类
 */
class Template
{
	public $templateDir;

	public $compileDir;

	public $directOutput = false;

	/**
	 * 获取显示内容
	 *
	 * @param string $filename
	 * @param array $var
	 *
	 * @return string
	 */
	public function fetch($filename, $var = [])
	{
		// 获取模板内容
		$tplFile = $this->getTemplatePath($filename);
		$content = $this->getContent($tplFile);
		// 解析include标签
		$content = $this->parseInclude($content);
		// 是否继承
		if (preg_match("~@extends\('((?>\w+))'\)~", $content, $matches) && strpos($content, $matches[0]) === 0) {
			// 获取继承模板内容
			$parentTplFile = $this->getTemplatePath($matches[1]);
			$parentContent = $this->getContent($parentTplFile);
			// 解析section区块
			if ($content = $this->parseSection($content)) {
				$content = preg_replace_callback("~@yield\('((?>\w+))'\)~", function($matches) use($content){
					return isset($content[$matches[1]])? $content[$matches[1]]:'';
				}, $parentContent);
			} else {
				trigger_error('Can\'t find @section tag in ('.$tplFile.')', E_USER_ERROR);
			}
		}
		// 解析模板变量输出
		$content = $this->parseEcho($content);
		// 导入变量
		extract($var);
		// 打开输出缓冲
		ob_start();
		// 是否生成编译文件
		if ($this->directOutput) {
			eval('?>'.$content);
		} else {
			$view = $this->compileDir.md5($tplFile).'.php';
			file_put_contents($view, $content);
			require $view;
		}
		// 返回输出内容
	    $out = ob_get_contents();
	    ob_end_clean();
	    return $out;
	}

	/**
	 * 解析模板变量输出
	 *
	 * @param $string $content
	 *
	 * @return $string
	 */
	private function parseEcho($content)
	{
		$content = preg_replace_callback('~{(\$(?>[^{}]+))}~', function($matches){
			if (strpos($matches[1], '.') === false) {
				$var = $matches[1];
			} else {
				$arr = explode('.', $matches[1]);
				$var = array_shift($arr);
				foreach ($arr as $v) {
					$var .= "['".$v."']";
				}
			}
			return '<?='.$var.'?>';
		}, $content);
		return $content;
	}

	/**
	 * 解析include标签
	 * 
	 * @param string $content
	 *
	 * @return string
	 */
	private function parseInclude($content)
	{
		return preg_replace_callback("~@include\('((?>\w+))'\)~", function($matches){
			$includeFilename = $this->getTemplatePath($matches[1]); 
			return $this->getContent($includeFilename);
		}, $content);
	}

	/**
	 * 解析section标签
	 *
	 * @param string $content
	 *
	 * @return array|boolean
	 */
	private function parseSection($content)
	{
		if (preg_match_all("~@section\('((?>\w+))'\)(.*?)@endsection~s", $content, $matches, PREG_SET_ORDER)) {
			$sections = [];
			foreach ($matches as $match) {
				$sections[$match[1]] = trim($match[2]);
			}
			return $sections;
		} else {
			return false;
		}
	}

	/**
	 * 获取模板内容
	 *
	 * @param string $file
	 *
	 * @return string
	 */
	private function getContent($file)
	{
		return trim(file_get_contents($file));
	}

	/**
	 * 获取模板路径
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	private function getTemplatePath($name)
	{
		$path = $this->templateDir.$name.'.php';
		if (is_file($path)) {
			return $path;
		} else {
			trigger_error('Template file ('.$path.') is not exist', E_USER_ERROR);
		}
	}
}