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
.1 h1{
	color:#fff;
	background:#4cae4c;
}
.0 h1{
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
	<div id="container" class="error">
		<h1>Error code:404!</h1>
		<p><?php echo $data;?></p>
        
    </div>
</body>
</html>