<?php 
	extract($data);
	
	echo $html->link($CpanelMenu['name'], unserialize($CpanelMenu['match_route'])->route);