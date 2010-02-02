<?php 
	extract($data);
	$route = unserialize($CpanelMenu['match_route'])->route;
	// Enforces not route through the plugin
	$route['plugin'] = '';
	
	echo $html->link($CpanelMenu['name'], $route);