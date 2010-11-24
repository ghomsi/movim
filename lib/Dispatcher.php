<?php
/**
 * @file Dispatcher.php
 * This file is part of MOVIM.
 * 
 * @brief Handles incoming static pages requests.
 *
 * @author Etenil <etenil@etenilsrealm.nl>
 *
 * @version 1.0
 * @date 21 October 2010
 *
 * Copyright (C)2010 MOVIM Project
 * 
 * See COPYING for licensing deatils.
 */

class Dispatcher extends Controller
{
	protected $default_handler = 'mainPage';
	private $page;
	
	function __construct()
	{
		parent::__construct();
		
		$this->page = new PageBuilder();
		$this->page->addScript('movim.js');
		$this->page->addScript('jquery.js');
		$this->page->addScript('jquery.form.js');
		$this->page->addScript('jaxl.func.js');
		$this->page->addScript('jquery-ui-1.8.5.custom.min.js');
	}

	function mainPage()
	{
		$user = new User();

		if(!$user->isLogged()) {
			$this->login();
		} else {
			$this->page->setTitle(_('MOVIM - Test Client - Welcome to Movim'));
			$this->page->menuAddLink($this->page->theme_img('img/home_icon.png', 'home_icon')._('Home'), '?q=mainPage');
			$this->page->menuAddLink(_('Configuration'), '?q=config');
			$this->page->menuAddLink(_('Logout'), '?q=disconnect');
			$content = new PageBuilder($user);

			$this->page->setContent($content->build('main.tpl'));
			echo $this->page->build('page.tpl');
		}
	}
	
	function config()
	{
		$user = new User();

		if(!$user->isLogged()) {
			$this->login();
		} else {
			$this->page->setTitle(_('MOVIM - Test Client - Configuration'));
			$this->page->menuAddLink($this->page->theme_img('img/home_icon.png', 'home_icon')._('Home'), '?q=mainPage');
			$this->page->menuAddLink(_('Configuration'), '?q=config');
			$this->page->menuAddLink(_('Logout'), '?q=disconnect');

			$content = new PageBuilder($user);

			$this->page->setContent($content->build('config.tpl'));
			echo $this->page->build('page.tpl');
		}
	}

	/**
	 * Show login interface (hard-coded).
	 */
	function login()
	{
		$this->page->setTitle(_('MOVIM - Test Client - Login to Movim'));
		$this->page->menuAddLink('Movim | Human Network', 'http://www.movim.eu/');
		$this->page->setContent(
			'<div id="connect_form">'.
			'<form id="authForm" action="index.php" method="post">'.
			'<input type="text" name="login" id="login" value="'._("My address").'" class="write"/>'.
			'<input type="password" name="pass" id="pass" value="'._("Password").'" class="write"/><br />'.
			'<input class="submit" type="submit" name="submit" value="'._("Come in!").'"/>'.
			'</form>'.
			'</div>');
		echo $this->page->build('page.tpl');
	}

	function disconnect()
	{
		$user = new User();
		$user->desauth();
		$this->login();
	}
}
