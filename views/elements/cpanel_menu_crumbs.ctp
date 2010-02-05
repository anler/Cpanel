<?php 
	extract($data);
	$route = unserialize($CpanelMenu['match_route'])->route;
	// To keep track of crumbs
	$cpanel->setSection($route, $CpanelMenu['url']);
	
	$CpanelMenu['parent_id'] ? $html->addCrumb($CpanelMenu['name'], $route) : $html->addCrumb($CpanelMenu['name']);