<?php defined('IS_RUN') or exit('No permission resources.'); ?><!DOCTYPE html>
<html lang="en">
<head>
<title>message</title>
<style>
body {
	background-color: #fff;
	margin: 30px;
	font: 13px/20px ;
	color: #333;
}
a {
	color: #039;
	background-color: transparent;
	font-weight: normal;
}

h1 {
	color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px;
}
hr{
border:none;
border-bottom: 1px solid #D0D0D0;
}
code {
	font-size: 12px;
	background-color: #f9f9f9;
	border: 1px solid #D0D0D0;
	color: #002166;
	margin: 14px 0 ;
	padding: 12px ;
}

#container {
	margin: 10px;
	border: 1px solid #D0D0D0;
	-webkit-box-shadow: 0 0 8px #D0D0D0;
}

p {
	margin: 12px 15px 12px 15px;
}
.success h1{
	color:#fff;
	background:#4cae4c;
}
.error h1{
	color:#fff;
	background:#d9534f;
}
.c0 h1{
	color:#fff;
	background:#4cae4c;
}
.c1 h1{
	color:#fff;
	background:#d9534f;
}
a{
	color:#428bca;
	text-decoration:none;
}

</style>
</head>
<body>
	<div id="container" class="c<?php echo $errNum;?>">
		<h1><?php if($errNum > 0) { ?>错误！<?php } else { ?>消息：<?php } ?></h1>
		<p><?php echo $retMsg;?></p>
        <p>
	
		<?php if(is_array($url_forward)) { ?>
			<?php $n=1; if(is_array($url_forward)) foreach($url_forward AS $k => $v) { ?>
			[<a href="<?php echo $v;?>"><?php echo $k;?></a>]
			<?php $n++;}unset($n); ?>
			<?php if($ms>0) { ?>
        	<script>
				setTimeout(function(){
					location.href='<?php echo array_shift($url_forward)?>';
					},
					<?php echo $ms;?>
					)
			</script>
			<?php } ?>
        <?php } elseif (!empty($url_forward)) { ?>
        [<a href="<?php echo $url_forward;?>">跳转</a>]
			<?php if($ms>0) { ?>
        	<script>
				setTimeout(function(){
					location.href='<?php echo $url_forward;?>';
					},
					<?php echo $ms;?>
					)
			</script>
			<?php } ?>
        <?php } else { ?>
        [<a href="javascript:history.go(-1)">返回</a>]
        <?php } ?>
        </p>
    </div>
</body>
</html>