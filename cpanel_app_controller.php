<?php
	/**
	* 
	*/
	class CpanelAppController extends AppController
	{
		var $components = array('Session');
		
		var $helpers = array('Cpanel.Cpanel', 'Html', 'Form', 'Javascript', 'Tree');
		
		var $failure = 'flash/failure';
		var $notice = 'flash/notice';
		var $success = 'flash/success';
	}
?>