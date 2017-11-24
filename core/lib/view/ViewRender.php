<?php
namespace core\lib\view;

use core\lib\Func;
/*
	视图渲染类

*/

class ViewRender
{
	//1695587776
	//文件路径
	protected $path='';

	//文件目录
	protected $pathdir='';

	//模板文件内容
	protected $contents='';

	//模板配置信息
	protected $config=[
		//源文件后缀
		'suffix'=>'',
		//缓存更新时间
		'cache_time'=>'',
		//模板变量
		'replace_var'=>'',

	];

	//替换的变量
	protected $replacevar=[

	];

	//empty判断变量
	private static $checkempty=false;
	//notempty判断变量
	private static $checknotempty=false;
	//if判断变量
	private static $checkif=false;
	//block存储量
	private $block='';

	//系统内置标签
	protected $labels=[
		
	];

	public function __construct($path)
	{
		//获取模板的配置
		foreach($this->config as $k=>$v){
			//模板变量
			if($k=='replace_var'){
				if(!empty(Func::config($k))){
					$this->setvar(Func::config($k));
					$this->config[$k]=Func::config($k);
				}
			}
			//配置
			if(!empty(Func::config($k))){
				$this->config[$k]=Func::config($k);
			}
		}


		$this->getcontents($path);
	}


	/*
		读取视图文件
		@param string $path 路径

	*/
	public function getcontents($path)
	{

		$htmldir=substr($path,0,strpos($path,Func::config('app'))+strlen(Func::config('app')));
		//文件目录
		$dirtest=substr($path,strpos($path,Func::config('app'))+strlen(Func::config('app')));
		$this->pathdir=$htmldir.DS.'view'.DS.(explode('/',$dirtest)['1']).DS;

		$htmldir.=DS.'view'.substr($path,strpos($path,Func::config('app'))+strlen(Func::config('app'))).'.html';

		


		//检测模板文件是否存在
		if(!file_exists($htmldir)){
			throw new \Exception('模板文件不存在');
		}
		

		$this->contents=file_get_contents($htmldir);

		//E:\wamp\www\project\core\..\test\cache\template\test\testview.php
		$beginpath=substr($path,0,strpos($path,Func::config('app'))+strlen(Func::config('app'))).DS.'cache'.DS.'template'.DS;
		
		$endpath=substr($path,strpos($path,Func::config('app'))+strlen(Func::config('app')));
		
		$path=$beginpath.$endpath;

		//获取操作文件名
		$arraypath=explode('/',$path);
		//编译后的模板文件名
		$tempname=array_pop($arraypath);
		//模板中的控制器路径
		$tempdir=substr($path,0,strpos($path,$tempname));
		//检测模板中的控制器是否存在，不存在则创建
		if(!is_dir($tempdir)){
			mkdir($tempdir);
		}
		
		$this->path=$path.'.php';
		

		//$this->path=PROJECT.Func::config('app').DS.'cache'.DS.'template'.DS.array_pop($path).'.php';
		
		if($this->contents===false){
			throw new \Exception('模板文件读取错误');
		}

	}


	/*
		渲染视图文件并输出
		
	*/
	public function render()
	{
		//判断文件是否存在及是否过缓存时间	
		if(is_file($this->path)&&$this->config['cache_time']>(time()-filemtime($this->path))){
			include($this->path);
			return '';
		}
		
		$this->r_extends();
		$this->r_block();
		$this->r_foreach();
		$this->r_for();
		$this->r_empty();
		$this->r_notempty();
		$this->r_if();
		$this->r_php();
		$this->r_var();
		
		$file=fopen($this->path,'w');
		fwrite($file, "<?php".$this->contents);
		fclose($file);

		//输出内容
		include($this->path);

		
	}


	/*
		设置替换变量
		@param array $var 替换数组的键值组合

	*/
	public function setvar(array $var)
	{
		if(!empty($var)){
			foreach ($var as $k => $v) {
				$this->replacevar[$k]=$v;
			}
		}
	}

	/*
		继承判断

		{extends name="模板文件相对路径"}

	*/
	public function r_extends()
	{
		//匹配继承开始部分
		$reg1="#\{extends[^}]*name=\"(?<name>[^}]*)\"\}#";

		$this->contents=preg_replace_callback($reg1,function($param){
			
			if(is_file($this->pathdir.$param['name'])){

				$extendscontent=file_get_contents($this->pathdir.$param['name']);

				/*
					查找其中的block部分
				*/
				//block开始部分
				/*$reg2="#\{block[^}]*name=\"(?<name>[^}]*)\"\}#";
				//block结束部分
				$reg3="#\{\/block\}#";

				$extendscontent=preg_replace_callback($reg2,function($param){
					return "\{exten\}";
				},$extendscontent);*/

				return $extendscontent;
			}else{
				throw new \Exception("继承模板文件错误".$param['name']);
			}
			
		},$this->contents);
	}

	/*
		模板区块继承判断
	*/
	public function r_block()
	{
		//匹配block开始位置
		$reg1="#\{extendblock[^}]*name=\"(?<name>[^}]*)\"\}(?<contents>[^{]*)\{\/extendblock\}#";
		
		$this->contents=preg_replace_callback($reg1,function($param){
			$this->block=$param;
		},$this->contents);

		//匹配父模板中block的位置
		$reg2="#\{block[^}]*name=\"{$this->block['name']}\"\}[^{]*\{\/block\}#";
		$this->contents=preg_replace_callback($reg2,function($param2){
			return $this->block['contents'];
		},$this->contents);

	}


	/*
		原生php
		{php}
		内容
		{/php}
	*/
	public function r_php()
	{
		//匹配php开始位置
		$reg1="#\{php\}(?<contents>[^{]*)#";
		//匹配php结束位置
		$reg2="#\{\/php\}#";

		$this->contents=preg_replace_callback($reg1,function($param){
			return "<?php  {$param['contents']}";
		},$this->contents);

		$this->contents=preg_replace_callback($reg2,function($param){
			return ";?>";
		},$this->contents);
	}

	/*
		变量赋值

		{$变量名}
	*/
	public function r_var()
	{
		$reg="#\{\\$(?<var>\w*)\}#";

		$this->contents=preg_replace_callback($reg,function($param){
			//遍历模板变量和匹配变量
			foreach ($this->replacevar as $k => $v) {
				if($k==$param['var']){
					return $v;
				}
			}
		}, $this->contents);
	}

	/*
		if else 判断

		{if (条件)}
		内容
		单条时{/if}
		{elseif (条件)}
		内容
		{else}
		内容
		{/else}

	*/
	public function r_if()
	{
		//匹配if开始位置
		$reg1="#\{if[^(]*\((?<judge1>[^)]*)\)\}(?<contents1>[^{]*)#";
		//匹配else if开始位置
		$reg2="#\{elseif[^)]*\((?<judge2>[^)]*)\)\}(?<contents2>[^{]*)#";
		//匹配else开始位置
		$reg3="#\{else\}(?<contents3>[^{]*)#";
		//匹配else结束位置
		$reg4="#\{\/else\}#";
		//匹配if结束位置
		$reg5="#\{\/if\}#";

		$this->contents=preg_replace_callback($reg1,function($param){
			self::$checkif=true;
			return "<?php if({$param['judge1']}) {echo \"{$param['contents1']}\"";
		},$this->contents);

		$this->contents=preg_replace_callback($reg2,function($param){
			return ";}elseif({$param['judge2']}){echo \"{$param['contents2']}\"";
		},$this->contents);

		$this->contents=preg_replace_callback($reg3,function($param){
			return " ;}else{echo \"{$param['contents3']}\"";
		},$this->contents);

		$this->contents=preg_replace_callback($reg4,function($param){
			return ";}?>";
		},$this->contents);
		$this->contents=preg_replace_callback($reg5,function($param){
			if(self::$checkif==true){
				self::$checkif=false;
				return ";}?>";
			}
		},$this->contents);

	}

	/*
		empty 判断

		{empty name="变量名"}
		内容
		{/empty}

	*/
	public function r_empty()
	{
		//匹配empty开始位置
		$reg1="#\{empty[^}]*name=\"(?<name>[^}]*)[\^}]*\"\}(?<content>[^{]*)#";
		
		//匹配empty结束位置
		$reg2="#\{\/empty\}#";

		$this->contents=preg_replace_callback($reg1,function($param){
			if(empty($this->replacevar[$param['name']])){
				self::$checkempty=true;
				return "<?php echo \"{$param['content']}\"";
				}
		},$this->contents);

		$this->contents=preg_replace_callback($reg2,function($param2){
			if(self::$checkempty==true){
				self::$checkempty=false;
				return ";?>";
			}
		},$this->contents);

	}

	/*
		notempty 判断

		{notempty name="变量名"}
		内容
		{/notempty}
	*/
	public function r_notempty()
	{
		//匹配notempty开始位置
		$reg1="#\{notempty[^}]*name=\"(?<name>[^}]*)\"[^}]*\}(?<contents>[^{]*)#";
		//匹配notempty结束为止
		$reg2="#\{\/notempty\}#";

		$this->contents=preg_replace_callback($reg1,function($param){
			if(!empty($this->replacevar[$param['name']])){
				self::$checknotempty=true;
				return "<?php echo \"{$param['contents']}\"";
			}
		},$this->contents);

		$this->contents=preg_replace_callback($reg2,function($param){
			if(self::$checknotempty){
				self::$checkempty=false;
				return ";?>";
			}
		},$this->contents);
	}


	/*
		for 循环

		{for name="循环变量名" start="起始数" end="结束数"}
		内容
		{/for}
	*/
	public function r_for()
	{
		//匹配for开始位置及内容
		$reg1="#\{for.*name=\"(?<name>\w*)\".*start=\"(?<start>\d*)\".*end=\"(?<end>\d*)\".*\}(?<contents>[^{]*)#";

		//匹配for结束位置
		$reg2="#\{\/for\}#";

		$this->contents=preg_replace_callback($reg1, function($param){
			return "<?php for(\${$param['name']}={$param['start']};\${$param['name']}<{$param['end']};\${$param['name']}++){
				echo \"{$param['contents']}\";";
		}, $this->contents);
		$this->contents=preg_replace_callback($reg2, function($param){
			return "}?>";
		}, $this->contents);

	}

	/*
		foreach 循环遍历

		{foreach name="被循环变量名" key="key值名称" value="value值名称"}
		内容
		{/foreach}
	*/
	public function r_foreach()
	{
		//匹配foreach开始位置及内容
		$reg1="#\{foreach.*name=\"(?<name>\w*)\".*key=\"(?<key>\w*)\".*value=\"(?<value>\w*)\"\}(?<contents>[^{]*)#";
		//匹配foreach结束位置
		$reg2="#\{\/foreach\}#";

		$this->contents=preg_replace_callback($reg1, function($param){
			//匹配替换key value
			$reg3="#\{\\$(?<key>{$param['key']})|\\$(?<value>{$param['value']})\}#";
			$this->contents=preg_replace_callback($reg3, function($param2){
				if(!empty($param2['key'])){
					return $param['key'];
				}else if(!empty($param2['value'])){
					return $param['value'];
				}
			}, $this->contents);
			//
			if(!empty($this->replacevar[$param['name']])){
				//json_encode将数组转成字符串  addslashes在预定的子符前加反斜杠  json_decode再将经转意后的字符串数组转会对象
				return "<?php  foreach(json_decode(\"".addslashes(json_encode($this->replacevar[$param['name']]))."\") as \${$param['key']}=>\${$param['value']}){echo \"{$param['contents']}\"";
			}else{
				return "<?php echo \"{$param['contents']}\"";
			}


		}, $this->contents);

		$this->contents=preg_replace_callback($reg2, function($param){
			return ";}?>";
		}, $this->contents);
	}



}