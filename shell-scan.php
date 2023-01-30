<!DOCTYPE html> 
<html>
    <head>
        <meta charset="utf-8">

        <title>WebShell-scan</title>

        <script type="text/javascript" src="./js/jquery-3.6.3.min.js"></script>

        <script type="text/javascript" src="./js/bootstrap.min.js"></script>

        <link rel="stylesheet" type="text/css" href="./js/bootstrap.min.css" />

    <style type="text/css">
            .mybody{

            margin:0 auto;

            width:200px;

            height:50%;

            margin-top: 150PX;

            background-image: url("./js/lynn.png") ;

             background-size: 100%;

            }
            #ys{
                color: white;
            }
    </style>

</head>

<body class="mybody">

    <form enctype="multipart/form-data" method="post">

        <div class="form-group" id="ys">

            <label for="exampleInputFile">Webshell-Scan</label>

            <button type="submit" class="btn btn-default">Submit</button>

            <input type="file" id="exampleInputFile" name="file">

        </div>



    </form>
</body>
</html>
<?php

@$filename = $_FILES['file']['name'].".php";

@$a = move_uploaded_file($_FILES['file']['tmp_name'],"./$filename");

//解析黑名单
$black = 'system|assert|exec|passthru|shell_exec|file_put_contents|popen|curl_multi_exec|parse_ini_file|show_source|fopen|fwrite|preg_replace|file_get_contents|mbereg_replace|spl_autoload_register|ob_start|\$_SERVER|\$_COOKIE|\$GLOBALS|$_FILES|\$\{.*\}|invokeArgs|spl_autoload_register';

//传参黑名单
$request = '\(\$_GET\[.*\].*\)|\(\$_POST\[.*\].*\)|\(\$_REQUEST\[.*\].*\)|\(\$GLOBALS\[.*\].*\)|\(\$_GET\{.*\}.*\)|\(\$_POST\{.*\}.*\)|\(\$_REQUEST\{.*\}.*\)|\(\$GLOBALS\{.*\}.*\)';

$b = 1;

//请求解析内容
$hanshu =  @file_get_contents($filename);

$yinxiang = preg_replace("/^echo.*/im",'',$hanshu);

$hanshu1 = preg_replace("/^[\$]/im",'print_r($',$hanshu);

$hanshu2 = preg_replace("/;/im",");",$hanshu1);

$files = fopen("lynn.php","w+");

fwrite($files,$hanshu2);

$ch= curl_init();

$timeout= 5;

curl_setopt ($ch, CURLOPT_URL, 'http://localhost/lynn.php');

curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

$file_content= curl_exec($ch);

curl_close($ch);

//请求解析结果
$hc= curl_init();

$timeout= 5;

curl_setopt ($hc, CURLOPT_URL, "http://localhost/$filename");

curl_setopt ($hc, CURLOPT_RETURNTRANSFER, 1);

curl_setopt ($hc, CURLOPT_CONNECTTIMEOUT, $timeout);

$file_contents= curl_exec($hc);

curl_close($hc);

//目录scan
$dirs = preg_replace("/<\?php/im",'pathinfo(getcwd())["filename"];',$hanshu);

$files = fopen("dir.php","w+");

fwrite($files,$dirs);

$chh= curl_init();

$timeout= 5;

curl_setopt ($chh, CURLOPT_URL, 'http://localhost/dir.php');

curl_setopt ($chh, CURLOPT_RETURNTRANSFER, 1);

curl_setopt ($chh, CURLOPT_CONNECTTIMEOUT, $timeout);

$file_contentss= curl_exec($chh);

curl_close($chh);


	//检索文件内容
	if(preg_match("/system\(.*\)|eval\(.*\)|assert\(.*\)|exec\(.*\)|passthru\(.*\)|shell_exec\(.*\)|system\(.*\)|proc_open\(.*\)|file_put_contents\(.*\)/im",@file_get_contents($filename))){

        echo("不变形一下？");
        @unlink("$filename"); 

    //解析访问文件内容检测
    }else if(preg_match("/$black/im",$file_content)){               
        
        echo 'RCE的shell？';  
        @unlink("$filename");

     //解析访问文件结果检测
    }else if(preg_match("/$black/im",$file_contents)){
                       
        echo 'RCE？';
        @unlink("$filename");

    //目录检索解析    
    }else if(preg_match("/$black/im",$file_contentss)){
                       
        echo '想利用目录RCE？';
        @unlink("$filename");

    }else if(preg_match("/$request/im",$hanshu)){
                       
        echo '传参RCE？';
        @unlink("$filename");

    }else if($a == $b){

        echo "<p>white</p>";
        @unlink("$filename");
     }
@curl_close($ch);
@curl_close($hc);
@curl_close($chh);
?>
