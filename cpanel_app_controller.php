<?php
	/**
	 * @todo Move this to other place
	 */
	defined('failure') || define('failure', 'flash/failure');
	defined('notice') || define('notice', 'flash/notice');
	defined('success') || define('success', 'flash/success');
	
	/**
	* 
	*/
	class CpanelAppController extends AppController
	{
		var $components = array('Session');
		
		var $uses = array('Cpanel.CpanelMenu');
		
		var $helpers = array('Cpanel.Cpanel', 'Html', 'Form', 'Javascript', 'Tree');
	}
	