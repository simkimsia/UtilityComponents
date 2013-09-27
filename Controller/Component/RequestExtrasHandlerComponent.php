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
App::uses('ArrayLib', 'UtilityLib.Lib');
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
 * a library of the most common useragents
 */
	public $userAgents = array(
		'facebook' => array('env' => 'HTTP_USER_AGENT', 'pattern' => '/facebook/i'),
		'iphone' => array('env' => 'HTTP_USER_AGENT', 'pattern' => '/iPhone/i')
	);

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
 * this will work even if there are named parameters and passed parameters because 
 * this->controller->request->here is the a string that automatically included the passed and named parameters.
 *
 * @param $parameters Array of parameters to be removed
 * @return string
 */
	public function removeQueryParameters($parameters, $here = '') {
		if (empty($here)) {
			$here		= $this->controller->request->here;
		}
		$query			= $this->controller->request->query;
		$validQueryParameters	= array();

		foreach($query as $param=>$value) {
			if (!in_array($param, $parameters)) {
				$validQueryParameters[$param] = $value;
			}
		}

		$queryString = $this->_reconstructQueryString($validQueryParameters);

		return $here . $queryString;
	}

	protected function _reconstructQueryString($queryParameters = array()) {
		$queryString = '';
		foreach($queryParameters as $param => $value) {
			$queryString .= $param . '=' . $value . '&';
		}

		if (strlen($queryString) > 0) {
			$queryString = substr($queryString, 0, strlen($queryString) - 1);
			$queryString = '?' . $queryString;
		}
		return $queryString;
	}

/**
 *
 * remove Named Parameters and return the relative url without the removed Named parameters
 * this will work because we simply use search and replace on 
 * this->controller->request->here which contains the named and passed parameters
 *
 * @param $parameters Array of parameters to be removed
 * @return string
 */
	public function removeNamedParameters($parameters, $here = '') {
		if (empty($here)) {
			$here	= $this->controller->request->here;
		}
		$query		= $this->controller->request->query;
		$named		= $this->controller->request->params['named'];

		$newHere	= $here;
		foreach($named as $param=>$value) {
			if (in_array($param, $parameters)) {
				$namedString = $param . ':' . $value;
				$newHere = str_replace($namedString, "", $newHere);
			}
		}

		$queryString = $this->_reconstructQueryString($query);

		return $newHere . $queryString;
	}

/**
 * retrieve a select list of User-Agent detectors
 *
 * @param $agentsWanted Array. A list of user-agent detectors to retrieve. If nothing is supplied, return the entire default list of user-agent detectors.
 * @return array
 */
	public function getUserAgentDetectors($agentsWanted = array()) {
		if (empty($agentsWanted)) {
			return $this->userAgents;
		}
		$retrievedAgents = ArrayLib::extractIfKeysExist($this->userAgents, $agentsWanted);
		return $retrievedAgents;
	}

/**
 * add 4 standard request method detectors for ajax calls
 * add 4 standard request method detectors for nonajax calls
 *
 * @param $request CakeRequest. This will be passed by reference
 * @return void
 */
	public function addAjaxMethodDetectors(CakeRequest $request) {
		// the 4 method detectors for ajax calls
		$request->addDetector('ajax_get', array('callback' => function ($request) {
			return ($request->is('ajax') && $request->is('get'));
		}));
		$request->addDetector('ajax_post', array('callback' => function ($request) {
			return ($request->is('ajax') && $request->is('post'));
		}));
		$request->addDetector('ajax_put', array('callback' => function ($request) {
			return ($request->is('ajax') && $request->is('put'));
		}));
		$request->addDetector('ajax_delete', array('callback' => function ($request) {
			return ($request->is('ajax') && $request->is('delete'));
		}));
		// the 4 method detectors for NON-ajax calls
		$request->addDetector('nonajax_get', array('callback' => function ($request) {
			return (!$request->is('ajax') && $request->is('get'));
		}));
		$request->addDetector('nonajax_post', array('callback' => function ($request) {
			return (!$request->is('ajax') && $request->is('post'));
		}));
		$request->addDetector('nonajax_put', array('callback' => function ($request) {
			return (!$request->is('ajax') && $request->is('put'));
		}));
		$request->addDetector('nonajax_delete', array('callback' => function ($request) {
			return (!$request->is('ajax') && $request->is('delete'));
		}));
	}

/**
 * add an array of detectors to the current CakeRequest
 *
 * @param $request CakeRequest
 * @param $detectors Array. Keys are the detector names. Values will be the detector value
 * @return void
 */
	public function addDetectorsToRequest(CakeRequest $request, $detectors = array()) {
		foreach($detectors as $detectorName => $detector) {
			$request->addDetector($detectorName, $detector);
		}
	}
}