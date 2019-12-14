<html>
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<style type="text/css">
body{overflow:visible;}
body,ul,ol,dl,dt,dd,h1,h2,h3,h4,h5,h6,dd,p,input{margin:0px;padding:0px;}
li{list-style-type:none;}
img,input{border:0px;}

a{outline:none;}
area{outline:none;}
h1,h2,h3,h4,h5,h6{font-weight: 500;}
body {
  background: #fff;
  font-family: '微软雅黑';
  color: #333;
  font-size: 16px; }

.error {
  padding: 24px 48px; }
  .error .face {
    font-size: 70px;
    font-weight: normal; }
  .error .message {
    font-size: 32px;
    margin-bottom: 15px;
    margin-top: 12px; }
  .error .content .info {
    margin-bottom: 12px; }
    .error .content .info h3.title {
      color: #000;
      font-weight: 700;
      font-size: 16px; }

.copyright {
  padding: 12px 48px;
  color: #999; }
  .copyright a {
    color: #000;
    text-decoration: none; }
</style>
<title>系统发生错误</title>
</head>
<body>
<div class="error">
	<h1 class="face"><?php echo $error['function'] ?>!(^^)……WRONG</h1>
	<div class="message"><?php echo $error['message'] ?></div>
	<div class="content">
		<div class="info">
			<h3 class="title">错误位置</h3>
			<div class="text">
				FILE: <?php echo $error['file'] ;?> &#12288;LINE: <?php echo $error['line'];?>
			</div>
		</div>
	<?php if(isset($e['trace'])) {?>
		<div class="info">
			<h3 class="title">TRACE</h3>
			<div class="text">
				<?php echo nl2br($error['trace']);?>
			</div>
		</div>
	<?php }?>
	</div>
</div>
<div class="copyright">
	<a title="官方网站" href="https://github.com/PengYilong/Zero.git">ZERO for MICRO PHP FRAME </a>
</div>
</body>
</html>