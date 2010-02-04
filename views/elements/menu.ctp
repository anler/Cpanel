<?php 
	extract($data);
	$route = unserialize($CpanelMenu['match_route'])->route;
	
	// To keep track of crumbs
	$cpanel->setSection($route, $CpanelMenu['id']);
	
	echo $html->link($CpanelMenu['name'], $route);