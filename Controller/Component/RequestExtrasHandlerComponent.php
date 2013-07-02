<?php
/**
 * Adds extra utility methods I want to use on $this->request object at Controller level 
 *
 * Cake2.x compatible Component
 *
 * Copyright 2013, Kim Stacks
 * Singapore
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2013, Kim Stacks.
 * @link http://stacktogether.com
 * @author Kim Stacks <kim@stacktogether.com>
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */
App::uses('Component', 'Controller');
class RequestExtrasHandlerComponent extends Component {

/**
 * Behavior settings
 *
 * @access public
 * @var array
 */
	public $settings = array();

/**
 * Cake Controller
 *
 * @access public
 * @var array
 */
	public $controller = null;

/**
 * Initialize function
 *
 *
 * @param controller object $controller
 * @param array $settings
 */
	public function initialize(Controller $controller) {
		$this->controller = $controller;
	}

/**
 *
 * @param controller object $controller 
 */
	public function startup(Controller $controller) {
		$this->controller = $controller;
	}

/**
 *
 * check if request is for this $subdomain
 *
 * @param $subdomain Subdomain in string form
 * @param $options Optional. Currently not used.
 * @return boolean
 */
	public function matchSubdomain($subdomain, $options = array()) {
		$subdomains = $this->controller->request->subdomains();
		$matches = preg_grep( "/$subdomain/i" , $subdomains );
		$matched = (count($matches) > 0);
		return $matched;
	}
}