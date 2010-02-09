<?php
	// Extract variables
	extract($data);
	
	// Extract Route
	$route = MenuItemRoute::unserializeRoute($CpanelMenu['match_route'], 'asArray');
	
	// Set section to keep track of crumbs
	$cpanel->setSection($route, $CpanelMenu['url']);
	
	// Set links
	echo $html->link($CpanelMenu['name'], $route);