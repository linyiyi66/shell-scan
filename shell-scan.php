<form enctype="multipart/form-data" method="post">
<input type='file' name="file">
<input type='submit' value='提交'>
</form>
<?php
echo "可能会显示丢失当前文件刷新页面即可";
echo "<br />";
$lynn = substr(md5(rand(10000,99999)),20,6).'.php';

@$b = move_uploaded_file($_FILES['file']['tmp_name'], "./$lynn");
$black = 'whoami|dir|systeminfo|phpinfo|eval|assert|exec|passthru|shell_exec|system|proc_open|popen|curl_exec|curl_multi_exec|parse_ini_file|show_source|file_put_contents|fsockopen|fopen|fwrite|preg_replace|file_get_contents|mbereg_replace|spl_autoload_register|ob_start|\$_SERVER|\$_COOKIE|\$GLOBALS|$_FILES|\$\{.*\}|invokeArgs|spl_autoload_register';

$a = 1;

$dir = pathinfo(getcwd())["basename"];

$g = '(\$_.*){2}';
if(preg_match("/$black/im",@file_get_contents($lynn))){

		echo("还想执行命令代码？");
		@unlink("$lynn"); 

	}else if(preg_match("/$g/i",@file_get_contents($lynn))){
	echo @file_get_contents($lynn);	
	echo "双传参没有用";
	@unlink("$lynn"); 

	}else if(preg_match("/system\(.*\)|eval\(.*\)|assert\(.*\)|exec\(.*\)|passthru\(.*\)|shell_exec\(.*\)|system\(.*\)|proc_open\(.*\)|file_put_contents\(.*\)/im",@file_get_contents($lynn))){

		echo("不变形一下？");
		@unlink("$lynn"); 

	}else if(preg_match("/eval\(\)|assert\(\)|exec\(\)|passthru\(\)|shell_exec\(\)|system\(\)|proc_open\(\)|file_put_contents\(\)/im",exec("curl http://localhost/$lynn"))){               
		echo 'RCE的shell？';
		@unlink($lynn);

	}else if(preg_match("/system|assert|exec|passthru|shell_exec|eval/im",$dir)){
                       
		echo '想通过目录文件RCE？';
		@unlink($lynn);

    }else if($a == $b){
      echo "文件名为:".$lynn;
     }
?>