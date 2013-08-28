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

/**
 *
 * remove Query Parameters and return the relative url without the removed Query parameters
 * does not yet work with passed params, named params, and shebang #
 *
 * @param $parameters Array of parameters to be removed
 * @return string
 */
	public function removeQueryParameters($parameters) {
		$here = $this->controller->request->here;
		$named = $this->controller->request->params['named'];
		$query = $this->controller->request->query;
		$passed = $this->controller->request->params['passed'];
		$queryString = '';
		foreach($query as $param=>$value) {
			if (!in_array($param, $parameters)) {
				$queryString .= $param . '=' . $value . '&';
			}
		}

		if (strlen($queryString) > 0) {
			$queryString = substr($queryString, 0, strlen($queryString) - 1);
		}
		if (strlen($queryString) > 0) {
			$queryString = '?' . $queryString;
		}
		return $here . $queryString;
	}
}