<?php<html>
	<head><title>测试模板文件1</title></head>
	<body>
		123
		456
		123
		<?php for($a=1;$a<3;$a++){
				echo "
		asdas
		";}?>
		<?php  foreach(json_decode("{\"1\":1,\"2\":2,\"3\":3}") as $k=>$v){echo "
		abc $k abc $v abc
		";}?>
		
		
		<?php if(123==12) {echo "
		if
		";}elseif(123==123){echo "
		elseif
		" ;}else{echo "
		else
		";}?>
		wenyizheng
		<?php  
		echo 'abc';
		;?>
	</body>
</html>