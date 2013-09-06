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
 * this->controller->request->here is the generated from Router::url which took care of the
 * passed and named parameters.
 *
 * @param $parameters Array of parameters to be removed
 * @return string
 */
	public function removeQueryParameters($parameters) {
		$here		= $this->controller->request->here;
		$query		= $this->controller->request->query;
		$queryString	= '';
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

/**
 *
 * remove Named Parameters and return the relative url without the removed Named parameters
 * this should work even if there are query parameters and passed parameters because 
 * we are going to use Router::url to reconstruct the url by passing in all the parameters 
 * except the removed Named Parameters
 *
 * @param $parameters Array of parameters to be removed
 * @return string
 */
	public function removeNamedParameters($parameters) {
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