<?php
	/**
	* 
	*/
	class CpanelAppController extends AppController {
		var $components = array('Session');
		var $uses = array('Cpanel.CpanelMenu');
		var $helpers = array('Cpanel.Cpanel', 'Html', 'Form', 'Javascript', 'Cpanel.Tree');
		
		var $failure = 'flash/failure';
		var $notice = 'flash/notice';
		var $success = 'flash/success';
	}
?>