<?php defined('IS_RUN') or exit('No permission resources.'); ?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
<title>轻松开始php项目--PHPstart-成都骡子网络科技有限公司</title>
<meta name="keywords" content="微信小程序,公众号,企业号,OA系统,WEB开发,webapp,安卓开发,ios开发,网站建设,设计">
<meta name="description" content="信息系统集成服务；信息技术咨询服务（不含信息技术培训服务）；数据库里和储存服务；广告设计、制作、代理发布、企业营销策划；组织策划文化交流活动；会议及展现服务；多媒设计服务；美术团设计服务；销售:计算机软件及辅助设备">
</head>
<style>
html,body{
	margin:0;
	padding:0;
	font-size:12px;
}
body{
	width:100%;
	height:100%;
	overflow:hidden;
	
}
.changecolor{
	background:#1b1e28;
animation: myanimate 6s infinite;
-moz-animation: myanimate 6s infinite;	/* Firefox */
-webkit-animation: myanimate 6s infinite;	/* Safari 和 Chrome */
-o-animation: myanimate 6s infinite;	/* Opera */
}
/*framswt*/
@keyframes myanimate
{
62%{background-color: #2e364e; }
}

@-moz-keyframes myanimate /* Firefox */
{
62%{background-color: #2e364e; }
}

@-webkit-keyframes myanimate /* Safari 和 Chrome */
{
62%{background-color: #2e364e; }
}

@-o-keyframes myanimate /* Opera */
{
62%{background-color: #2e364e; }
}



body {
	font-size: 50%;
	margin: 1em;
	background: #232425;
}
ul {
	list-style: none;
	margin: 0;
	padding: 0;
}
#watch {
	font-size: .3em;
	position: absolute;
	left:1%;
	top:1%;
	transition: all 2s;
	-moz-transition: all 2s; /* Firefox 4 */
	-webkit-transition: all 2s; /* Safari 和 Chrome */
	-o-transition: all 2s; /* Opera */
	
}
#watch .frame-face {
	position: relative;
	width: 30em;
	height: 30em;
	margin:  auto;
	border-radius: 15em;
	background: -webkit-linear-gradient(top, #f9f9f9, #666);
	background: -moz-linear-gradient(top, #f9f9f9, #666);
	background: -webkit-linear-gradient(to bottom, #f9f9f9, #666);
	background: linear-gradient(to bottom, #f9f9f9, #666);
	box-shadow: rgba(0, 0, 0, .8) .5em .5em 4em;

}

#watch .frame-face:before {
	content: '';
	width: 29.4em;
	height: 29.4em;
	border-radius: 14.7em;
	position: absolute;
	top: .3em;
	left: .3em;
	background: -webkit-linear-gradient(135deg, rgba(246, 248, 249, 0) 0%, rgba(229, 235, 238, 1) 50%, rgba(205, 212, 217, 1) 51%, rgba(245, 247, 249, 0) 100%), -webkit-radial-gradient(center, ellipse cover, rgba(246, 248, 249, 1) 0%, rgba(229, 235, 238, 1) 65%, rgba(205, 212, 217, 1) 66%, rgba(245, 247, 249, 1) 100%);
	background: -moz-linear-gradient(135deg, rgba(246, 248, 249, 0) 0%, rgba(229, 235, 238, 1) 50%, rgba(205, 212, 217, 1) 51%, rgba(245, 247, 249, 0) 100%), -moz-radial-gradient(center, ellipse cover, rgba(246, 248, 249, 1) 0%, rgba(229, 235, 238, 1) 65%, rgba(205, 212, 217, 1) 66%, rgba(245, 247, 249, 1) 100%);
	background: -webkit-linear-gradient(-45deg, rgba(246, 248, 249, 0) 0%, rgba(229, 235, 238, 1) 50%, rgba(205, 212, 217, 1) 51%, rgba(245, 247, 249, 0) 100%), -webkit-radial-gradient(ellipse at center, rgba(246, 248, 249, 1) 0%, rgba(229, 235, 238, 1) 65%, rgba(205, 212, 217, 1) 66%, rgba(245, 247, 249, 1) 100%);
	background: linear-gradient(135deg, rgba(246, 248, 249, 0) 0%, rgba(229, 235, 238, 1) 50%, rgba(205, 212, 217, 1) 51%, rgba(245, 247, 249, 0) 100%), radial-gradient(ellipse at center, rgba(246, 248, 249, 1) 0%, rgba(229, 235, 238, 1) 65%, rgba(205, 212, 217, 1) 66%, rgba(245, 247, 249, 1) 100%);
}
#watch .frame-face:after {
	content: '';
	width: 28em;
	height: 28em;
	border-radius: 14.2em;
	position: absolute;
	top: .9em;
	left: .9em;
	box-shadow: inset rgba(0, 0, 0, .2) .2em .2em 1em;
	border: .1em solid rgba(0, 0, 0, .2);
	background: -webkit-linear-gradient(top, #fff, #ccc);
	background: -moz-linear-gradient(top, #fff, #ccc);
	background: -webkit-linear-gradient(to bottom, #fff, #ccc);
	background: linear-gradient(to bottom, #fff, #ccc);
}
#watch .digits {
	width: 30em;
	height: 30em;
	border-radius: 15em;
	position: absolute;
	top: 0;
	left: 50%;
	margin-left: -15em;
}
#watch .digits li {
	font-size: 1.6em;
	display: block;
	width: 1.6em;
	height: 1.6em;
	position: absolute;
	top: 50%;
	left: 50%;
	line-height: 1.6em;
	text-align: center;
	margin: -.8em 0 0 -.8em;
	font-weight: bold;
}
#watch .digits li:nth-child(1) {
	-webkit-transform: translate(3.9em, -6.9em);
	-moz-transform: translate(3.9em, -6.9em);
	transform: translate(3.9em, -6.9em);
}
#watch .digits li:nth-child(2) {
	-webkit-transform: translate(6.9em, -4em);
	-moz-transform: translate(6.9em, -4em);
	transform: translate(6.9em, -4em);
}
#watch .digits li:nth-child(3) {
	-webkit-transform: translate(8em, 0);
	-moz-transform: translate(8em, 0);
	transform: translate(8em, 0);
}
#watch .digits li:nth-child(4) {
	-webkit-transform: translate(6.8em, 4em);
	-moz-transform: translate(6.8em, 4em);
	transform: translate(6.8em, 4em);
}
#watch .digits li:nth-child(5) {
	-webkit-transform: translate(3.9em, 6.9em);
	-moz-transform: translate(3.9em, 6.9em);
	transform: translate(3.9em, 6.9em);
}
#watch .digits li:nth-child(6) {
	-webkit-transform: translate(0, 8em);
	-moz-transform: translate(0, 8em);
	transform: translate(0, 8em);
}
#watch .digits li:nth-child(7) {
	-webkit-transform: translate(-3.9em, 6.9em);
	-moz-transform: translate(-3.9em, 6.9em);
	transform: translate(-3.9em, 6.9em);
}
#watch .digits li:nth-child(8) {
	-webkit-transform: translate(-6.8em, 4em);
	-moz-transform: translate(-6.8em, 4em);
	transform: translate(-6.8em, 4em);
}
#watch .digits li:nth-child(9) {
	-webkit-transform: translate(-8em, 0);
	-moz-transform: translate(-8em, 0);
	transform: translate(-8em, 0);
}
#watch .digits li:nth-child(10) {
	-webkit-transform: translate(-6.9em, -4em);
	-moz-transform: translate(-6.9em, -4em);
	transform: translate(-6.9em, -4em);
}
#watch .digits li:nth-child(11) {
	-webkit-transform: translate(-3.9em, -6.9em);
	-moz-transform: translate(-3.9em, -6.9em);
	transform: translate(-3.9em, -6.9em);
}
#watch .digits li:nth-child(12) {
	-webkit-transform: translate(0, -8em);
	-moz-transform: translate(0, -8em);
	transform: translate(0, -8em);
}
#watch .digits:before {
	content: '';
	width: 1.6em;
	height: 1.6em;
	border-radius: .8em;
	position: absolute;
	top: 50%;
	left: 50%;
	margin: -.8em 0 0 -.8em;
	background: #121314;
}
#watch .digits:after {
	content: '';
	width: 4em;
	height: 4em;
	border-radius: 2.2em;
	position: absolute;
	top: 50%;
	left: 50%;
	margin: -2.1em 0 0 -2.1em;
	border: .1em solid #c6c6c6;
	background: -webkit-radial-gradient(center, ellipse cover, rgba(200, 200, 200, 0), rgba(190, 190, 190, 1) 90%, rgba(130, 130, 130, 1) 100%);
	background: -moz-radial-gradient(center, ellipse cover, rgba(200, 200, 200, 0), rgba(190, 190, 190, 1) 90%, rgba(130, 130, 130, 1) 100%);
	background: -webkit-radial-gradient(ellipse at center, rgba(200, 200, 200, 0), rgba(190, 190, 190, 1) 90%, rgba(130, 130, 130, 1) 100%);
	background: radial-gradient(ellipse at center, rgba(200, 200, 200, 0), rgba(190, 190, 190, 1) 90%, rgba(130, 130, 130, 1) 100%);
}
@-webkit-keyframes hours {
to {
-webkit-transform:rotate(<?php echo $dh*30+360;?>deg)

}
}
@-moz-keyframes hours {
to {
-moz-transform:rotate(<?php echo $dh*30+360;?>deg)
}
}
@keyframes hours {
to {
transform:rotate(<?php echo $dh*30+360;?>deg)
}
}
#watch .hours-hand {
	width: .8em;
	height: 7em;
	border-radius: 0 0 .9em .9em;
	background: #232425;
	position: absolute;
	bottom: 50%;
	left: 50%;
	margin: 0 0 -.8em -.4em;
	box-shadow: #232425 0 0 2px;
	-webkit-transform-origin: 0.4em 6.2em;
	-webkit-transform: rotate(<?php echo $dh*30;?>deg);
	-webkit-animation: hours 43200s linear <?php echo $ych;?>s infinite;
	-moz-transform-origin: 0.4em 6.2em;
	-moz-transform: rotate(<?php echo $dh*30;?>deg);
	-moz-animation: hours 43200s linear <?php echo $ych;?>s infinite;
	transform-origin: 0.4em 6.2em;
	transform: rotate(<?php echo $dh*30;?>deg);
	animation: hours 43200s linear <?php echo $ych;?>s infinite;
}
#watch .hours-hand:before {
	content: '';
	background: inherit;
	width: 1.8em;
	height: .8em;
	border-radius: 0 0 .8em .8em;
	box-shadow: #232425 0 0 1px;
	position: absolute;
	top: -.7em;
	left: -.5em;
}
#watch .hours-hand:after {
	content: '';
	width: 0;
	height: 0;
	border: .9em solid #232425;
	border-width: 0 .9em 2.4em .9em;
	border-left-color: transparent;
	border-right-color: transparent;
	position: absolute;
	top: -3.1em;
	left: -.5em;
}
@-webkit-keyframes minutes {
to {
-webkit-transform:rotate(<?php echo (int)$di*6+360;?>deg)
}
}
@-moz-keyframes minutes {
to {
-moz-transform:rotate(<?php echo (int)$di*6+360;?>deg)
}
}
@keyframes minutes {
to {
transform:rotate(<?php echo (int)$di*6+360;?>deg)
}
}
#watch .minutes-hand {
	width: .8em;
	height: 12.5em;
	border-radius: .5em;
	background: #343536;
	position: absolute;
	bottom: 50%;
	left: 50%;
	margin: 0 0 -1.5em -.4em;
	box-shadow: #343536 0 0 2px;
	-webkit-transform-origin: 0.4em 11em;
	-webkit-transform: rotate(<?php echo $di*6;?>deg);
	-webkit-animation: minutes 3600s linear <?php echo $yci;?>s infinite;
	-moz-transform-origin: 0.4em 11em;
	-moz-transform: rotate(<?php echo $di*6;?>deg);
	-moz-animation: minutes 3600s linear <?php echo $yci;?>s infinite;
	transform-origin: 0.4em 11em;
	transform: rotate(<?php echo $di*6;?>deg);
	animation: minutes 3600s linear <?php echo $yci;?>s infinite;
}
@-webkit-keyframes seconds {
to {
-webkit-transform:rotate(<?php echo (int)$ds*6+360;?>deg)
}
}
@-moz-keyframes seconds {
to {
-moz-transform:rotate(<?php echo (int)$ds*6+360;?>deg)
}
}
@keyframes seconds {
to {
transform:rotate(<?php echo (int)$ds*6+360;?>deg)
}
}
#watch .seconds-hand {
	width: .2em;
	height: 14em;
	border-radius: .1em .1em 0 0/10em 10em 0 0;
	background: #c00;
	position: absolute;
	bottom: 50%;
	left: 50%;
	margin: 0 0 -2em -.1em;
	box-shadow: rgba(0, 0, 0, .8) 0 0 .2em;
	-webkit-transform-origin: 0.1em 12em;
	-webkit-transform: rotate(<?php echo $ds*6;?>deg);
	-webkit-animation: seconds 60s steps(60, end) 0s infinite;
	-moz-transform-origin: 0.1em 12em;
	-moz-transform: rotate(<?php echo $ds*6;?>deg);
	-moz-animation: seconds 60s steps(60, end) 0s infinite;
	transform-origin: 0.1em 12em;
	transform: rotate(<?php echo $ds*6;?>deg);
	animation: seconds 60s steps(60, end) 0s infinite;
}
#watch .seconds-hand:after {
	content: '';
	width: 1.4em;
	height: 1.4em;
	border-radius: .7em;
	background: inherit;
	position: absolute;
	left: -.65em;
	bottom: 1.35em;
}
#watch .seconds-hand:before {
	content: '';
	width: .8em;
	height: 3em;
	border-radius: .2em .2em .4em .4em/.2em .2em 2em 2em;
	box-shadow: rgba(0, 0, 0, .8) 0 0 .2em;
	background: inherit;
	position: absolute;
	left: -.35em;
	bottom: -3em;
}

.footer {
	text-align: center;
	font: 12px "Open Sans Light", "Open Sans", "Segoe UI", Helvetica, Arial;
}
.footer a {
	color: #999;
	text-decoration: none;
}
.qq{
	color:#fff;
	text-align:center;
	font-size:1.5em;
	line-height:2em;
}

/****/
#words{
	position:absolute;
	left:0;
	top:0;
	right:0;
	bottom:0;
	overflow:hidden;
}
span{
	position:absolute;
	font-size:80px;
	transition: all 10s;
	-moz-transition: all 10s; /* Firefox 4 */
	-webkit-transition: all 10s; /* Safari 和 Chrome */
	-o-transition: all 10s; /* Opera */
	color: #FFF;
	padding:5px;
	word-break:break-all;
	white-space:nowrap;
}
span>div{white-space: normal; word-break:break-strict;font-size:13px; max-width:20em; display:none; border-top:solid 1px #eee; padding:5px 0;}

span.small{
	font-size:12px;
	filter:alpha(opacity=0.2); 
	-moz-opacity:0.2; 
	opacity:0.2;
}
span.small:hover{
	font-size:40px;
	filter:alpha(opacity=1); 
	-moz-opacity:1; 
	opacity:1;
	z-index:99;
	transition: none;
	background:#fff;
	color:#888;
	-moz-border-radius: 6px;      /* Gecko browsers */
    -webkit-border-radius: 6px;   /* Webkit browsers */
    border-radius:6px;            /* W3C syntax */
	box-shadow:0 0 5px #000;
}
span.small:hover div{
	display:block;
	color:#333;
}

#watch:hover{
	font-size: .7em;
	position: absolute;
}
</style>
<script>
var words = new Array(
	<?php $n=1;if(is_array($words)) foreach($words AS $w) { ?>
	'phpstart.xyz',
	'<?php echo $w;?>',
	<?php $n++;}unset($n); ?>
	'PHPSTART'
)
var glbspan = false;
	setInterval("addword()",1000);
	function addword(){
		var x = document.getElementById("words").getElementsByTagName("span");
		if(x.length>50) document.getElementById("words").removeChild(x[0]);
		var span = document.createElement("span");
		span.innerHTML = getWord();
		span.style.left = Math.random()*90 + '%';
		span.style.top = Math.random()*95 + '%';
        document.getElementById("words").appendChild(span);
		glbspan = span;
		setTimeout(function(){glbspan.className='small'},300);
	}
	function getWord(){
		var i = parseInt(Math.random()*words.length);
		return words[i];
	}
	setTimeout("init()",300);
	function init(){
		for(var i=0;i<20;i++){
			var span = document.createElement("span");
			span.innerHTML = getWord();
			span.style.left = Math.random()*95 + '%';
			span.style.top = Math.random()*95 + '%';
			span.className='small'
			document.getElementById("words").appendChild(span);
		}
	}
</script>
<body class="changecolor">
<div id="words">
</div>
<div id="watch">
  <div class="frame-face"></div>
 
  
  <ul class="digits">
    <li>1</li><li>2</li><li>3</li><li>4</li><li>5</li><li>6</li><li>7</li><li>8</li><li>9</li><li>10</li><li>11</li><li>12</li>
  </ul>
  <div class="hours-hand"></div>
  <div class="minutes-hand"></div>
  <div class="seconds-hand"></div>
</div>

</body>
</html>
