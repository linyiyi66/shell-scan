<form enctype="multipart/form-data" method="post">
<input type='file' name="file">
<input type='submit' value='提交'>
</form>
<?php
echo "可能会显示丢失当前文件刷新页面即可";
echo "<br />";
$lynn = substr(md5(rand(10000,99999)),20,6).'.php';

@$b = move_uploaded_file($_FILES['file']['tmp_name'], "./$lynn");
$black = 'whoami|dir|systeminfo|phpinfo|system|assert|exec|passthru|shell_exec|popen|curl_multi_exec|parse_ini_file|show_source|fopen|fwrite|preg_replace|file_get_contents|mbereg_replace|spl_autoload_register|ob_start|\$_SERVER|\$_COOKIE|\$GLOBALS|$_FILES|\$\{.*\}|invokeArgs|spl_autoload_register';
$a = 1;

$dir = pathinfo(getcwd())["basename"];

@$file = @$_FILES["file"]["name"];

$hanshu1 = preg_replace("/<\?php/",'<?php print_r(',file_get_contents($lynn));

$hanshu2 = preg_replace("/;/",");",$hanshu1);

$file = fopen("lynn.php","w+");

fwrite($file,$hanshu2);

$ch= curl_init();
$timeout= 5;
curl_setopt ($ch, CURLOPT_URL, 'http://localhost/lynn.php/');
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$file_contents= curl_exec($ch);
curl_close($ch);

$g = '(\$_.*){2}';

// foreach()
	//黑名单检测
	if(preg_match("/$black/im",@file_get_contents($lynn))){

		echo("还想执行命令代码？");
		@unlink("$lynn"); 

	//传参方式检测 | 还在修复
	}else if(preg_match("/$g/i",@file_get_contents($lynn))){
	echo @file_get_contents($lynn);	
	echo "双传参没有用";
	@unlink("$lynn"); 

	//检测是否有关键字传入
	}else if(preg_match("/system\(.*\)|eval\(.*\)|assert\(.*\)|exec\(.*\)|passthru\(.*\)|shell_exec\(.*\)|system\(.*\)|proc_open\(.*\)|file_put_contents\(.*\)/im",@file_get_contents($lynn))){

		echo("不变形一下？");
		@unlink("$lynn"); 

	//检测解析后关键字
	}else if(preg_match("/eval\(\)|assert\(\)|exec\(\)|passthru\(\)|shell_exec\(\)|system\(\)|proc_open\(\)|file_put_contents\(\)/im",exec("curl http://localhost/$lynn"))){               
		echo 'RCE的shell？';
		@unlink($lynn);

	// 检测目录
	}else if(preg_match("/system|assert|exec|passthru|shell_exec/im",$dir)){
                       
		echo '想通过目录文件RCE？';
		@unlink($lynn);
    }
    // 检测文件内容
    else 
    	if(preg_match("/system/im",$file_contents)){
                       
		echo '想通过函数内容RCE？';
		@unlink("lynn.php");

    }else if($a == $b){
      echo "文件名为:".$lynn;
     }
?>
