<?php 
	extract($data);
	$route = unserialize($CpanelMenu['match_route'])->route;
	// Enforces not route through the plugin
	$route['plugin'] = null;
	
	// To keep track of this when generate crumbs
	$route['section'] = $CpanelMenu['id'];
	
	echo $html->link($CpanelMenu['name'], $route);