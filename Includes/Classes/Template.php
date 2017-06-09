<?php

namespace Includes\Classes;

/**
 * 模版类
 */
class Template
{
	/**
	 * @var string $tplDir 模板目录
	 * @var string $compileDir 编译目录
	 * @var bool $directOutput 是否直接输出
	 */
	public $tplDir, $compileDir, $directOutput = false;

	/**
	 * @var array $var 模板变量
	 */
	private $var;

	/**
	 * 显示页面
	 *
	 * @param string $filename
	 * @param array $var
	 *
	 * @return string
	 */
	public function display($filename, array $var = [])
	{
		// 获取模板路径
		$tplFile = $this->getTplPath($filename);
		// 编译文件是否可用
		$view = $this->compileDir.md5($tplFile).'.php';
		if (is_file($view) && filemtime($view) > filemtime($tplFile)) {
			$this->assign($var);
			require $view;
		} else {
			// 获取模板内容
			$content = $this->getContent($tplFile);
			// 解析include标签
			$content = $this->parseInclude($content);
			// 是否继承
			if (preg_match("~@extends\('(\w+)'\)~", $content, $matches) && strpos($content, $matches[0]) === 0) {
				// 获取继承模板内容
				$parentTplFile = $this->getTplPath($matches[1]);
				$parentContent = $this->getContent($parentTplFile);
				// 解析section区块
				if ($content = $this->parseSection($content)) {
					$content = preg_replace_callback("~@yield\('(\w+)'\)~", function($matches) use($content){
						return isset($content[$matches[1]])? $content[$matches[1]]:'';
					}, $parentContent);
				} else {
					trigger_error('Can\'t find @section tag in ('.$tplFile.')', E_USER_ERROR);
				}
			}
			// 解析php标签
			$content = $this->parseControlStructures($content);
			$content = $this->parseEcho($content);
			// 注册变量
			$this->assign($var);
			// 是否生成编译文件
			if ($this->directOutput) {
				eval('?>'.$content);
			} else {
				file_put_contents($view, $content);
				require $view;
			}
		}
	}

	/**
	 * 注册变量
	 *
	 * @param array $var
	 *
	 * @return void
	 */
	private function assign(array $var = [])
	{
		foreach ($var as $key => $value) {
			$this->var[$key] = $value;
		}
	}

	/**
	 * 解析解析if、else、elseif、foreach标签
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	private function parseControlStructures($content)
	{
		$content = preg_replace_callback('~@((if|elseif|foreach)(\(((?>[^()]+)|(?3))*\)))~', function($matches){
			return $this->parseVar("<?php $matches[1]: ?>");
		}, $content);
		$content = str_replace('@else', '<?php else: ?>', $content);
		$content = preg_replace('~@end(if|foreach)~', '<?php end$1; ?>', $content);
		return $content;
	}

	/**
	 * 解析Echo
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	private function parseEcho($content)
	{
		return preg_replace_callback('~{{(.*?)}}~', function($matches){
			return $this->parseVar("<?= $matches[1] ?>");
		}, $content);
	}

	/**
	 * 解析变量
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	private function parseVar($content)
	{
		return preg_replace('~\$(\w+)~', "\$this->var['$1']", $content);
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
		return preg_replace_callback("~@include\('(\w+)'\)~", function($matches){
			$includeFilename = $this->getTplPath($matches[1]); 
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
		if (preg_match_all("~@section\('(\w+)'\)(.*?)@endsection~s", $content, $matches, PREG_SET_ORDER)) {
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
	private function getTplPath($name)
	{
		$path = $this->tplDir.$name.'.php';
		if (is_file($path)) {
			return $path;
		} else {
			trigger_error('Template file ('.$path.') is not exist', E_USER_ERROR);
		}
	}
}