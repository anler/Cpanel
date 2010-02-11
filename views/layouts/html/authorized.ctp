<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title><?php echo $title_for_layout ?> - CPanel v1.0</title>
		
		<?php echo $cpanel->css() ?>
		
		<?php echo $javascript->link('/cpanel/js/mootools-1.2.4-core') ?>
		<?php echo $javascript->link('/cpanel/js/mootools-1.2.4.2-more') ?>
		
		<?php echo $scripts_for_layout ?>
	</head>
	
	<body id="cpanel">
		<div id="container">
			<div id="header">
				<?php echo $cpanel->appCredentials(array('id' => 'app-credentials')) ?>
				<?php echo $cpanel->userActions(array('id' => 'user-actions', 'class' => 'h tags')) ?>
			</div>
			
			<?php echo $cpanel->navigationList(array('id' => 'navigation-list', 'class' => 'tags')) ?>
			
			<div id="board">
				<?php echo $cpanel->crumbs(' > ', array('id' => 'breadcrumbs')) ?>
				
				<div id="flashes">
					<?php $session->flash() ?>
				</div>
				
				<div id="section-tabs">
					<?php echo $cpanel->sectionTitle(array('class' => 'title h')) ?>
					<?php echo $cpanel->sectionTabs(array('class' => 'h tags')) ?>
				</div>
				
				<div class="content">
					<?php echo $cpanel->sectionSubTabs(array('id' => 'section-sub-tabs', 'class' => 'h tags')) ?>
					
					<?php echo $cpanel->sectionActions(array('id' => 'section-actions', 'class' => 'h tags')) ?>
					
					<?php echo $content_for_layout ?>
				</div>

			</div>

			<div id="footer" class="clearboth">
				DESIGN AND DEVELOPMENT KM-0
			</div>
		</div>
	</body>
</html>