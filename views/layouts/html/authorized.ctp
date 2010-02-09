<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title><?php echo $title_for_layout ?> - <!-- + AppName --> CPanel v1.0</title>
		
		<?php echo $html->css('/cpanel/css/cpanel.css') ?>
		
		<?php echo $javascript->link('/cpanel/js/mootools-1.2.4-core') ?>
		<?php echo $javascript->link('/cpanel/js/mootools-1.2.4.2-more') ?>
		
		<?php echo $scripts_for_layout ?>
	</head>
	
	<body id="cpanel">
		<div id="container">
			<div id="header">
				<?php echo $cpanel->appCredentials() ?>
				<div>
					<?php echo $cpanel->dashboard() ?>
					<?php echo $cpanel->account() ?>
					<?php echo $cpanel->logout() ?>
				</div>
			</div>
			
			<div id="menu">
				<?php echo $cpanel->navigationList() ?>
			</div>
			
			<div id="board">
				<div class="navigation-bar">
					<div class="breadcrumbs"><?php echo $cpanel->crumbs(' > ') ?><?php //echo $cpanel->levelUp() ?></div>
				</div>
				
				<div class="flashes">
					<?php $session->flash() ?>
				</div>
				
				<div id="section-tabs">
					<h2><?php echo $cpanel->sectionTitle() ?></h2><?php echo $cpanel->sectionTabs() ?>
				</div>
				
				<div class="content">
					<?php echo $cpanel->sectionSubTabs(array('id' => 'section-sub-tabs')) ?>
					
					<?php echo $cpanel->sectionActions(array('id' => 'section-actions')) ?>
					
					<?php echo $content_for_layout ?>
				</div>

			</div>

			<div id="" class="clearboth"></div>
		</div>
	</body>
</html>