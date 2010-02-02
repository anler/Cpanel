<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title><?php echo $title_for_layout . ' - Futbolfactory'?> - CPanel v1.0</title>
		
		<?php echo $html->css('/cpanel/css/cpanel_login.css') ?>
		
		<?php echo $javascript->link('/cpanel/js/mootools-1.2.4-core') ?>
		<?php echo $javascript->link('/cpanel/js/mootools-1.2.4.2-more') ?>
		
		<?php echo $scripts_for_layout ?>
	</head>
	
	<body id="cpanel_login">
		<div id="wrapper">
			<div id="loginPanel">
				<?php $session->flash() ?>
				<?php $session->flash('auth') ?>
				<?php echo $content_for_layout ?>
			</div>
		</div>
	</body>
</html>