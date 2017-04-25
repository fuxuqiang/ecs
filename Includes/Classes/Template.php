<?php

namespace Includes\Classes;

/**
 * 模版类
 */
class Template
{
	public $templateDir;

	public $compileDir;

	public $directOutput = false;

	private $content;

	private $view;

	/**
	 * 显示页面
	 *
	 * @param string $filename
	 * @param array $var
	 *
	 * @return string
	 */
	public function display($filename, $var = [])
	{
		$this->fetch($filename);
		// 导入变量
		extract($var);
		// 是否生成编译文件
		if ($this->directOutput) {
			eval('?>'.$this->content);
		} else {
			$this->view = $this->compileDir.md5($this->tplFile).'.php';
			file_put_contents($this->view, $this->content);
			require $this->view;
		}
		exit;
	}

	/**
	 * 获取显示内容
	 *
	 * @param string $filename
	 *
	 * @return void
	 */
	private function fetch($filename)
	{
		// 获取模板内容
		$this->tplFile = $this->getTemplatePath($filename);
		$content = $this->getContent($this->tplFile);
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
				trigger_error('Can\'t find @section tag in ('.$this->tplFile.')', E_USER_ERROR);
			}
		}
		// 解析php标签组
		$this->content = $this->parseTags($content);
	}

	/**
	 * 解析php标签组
	 *
	 * @param $string $content
	 *
	 * @return $string
	 */
	private function parseTags($content)
	{
		// 解析if、elseif、foreach标签
		$content = preg_replace('~@((if|elseif|foreach)(\(((?>[^()]+)|(?3))*\)))~', '<?php $1: ?>', $content);
		$content = preg_replace('~@end(if|foreach)~', '<?php end$1; ?>', $content);
		// 解析else标签
		$content = str_replace('@else', '<?php else: ?>', $content);
		// 解析模板变量输出，并返回
		return preg_replace_callback('~{(\$(?>[^{}]+))}~', function($matches){
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