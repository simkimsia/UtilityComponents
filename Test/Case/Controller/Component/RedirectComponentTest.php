<?php
/**
 * RedirectComponentTest file
 *
 * PHP 5
 *
 * CakePHP(tm) Tests <http://book.cakephp.org/2.0/en/development/testing.html>
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://book.cakephp.org/2.0/en/development/testing.html CakePHP(tm) Tests
 * @package       Cake.Test.Case.Controller.Component
 * @since         CakePHP(tm) v 1.2.0.5435
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');
App::uses('RedirectComponent', 'UtilityComponents.Controller/Component');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('Router', 'Routing');


class RedirectModel extends CakeTestModel {

/**
 * useDbConfig property
 *
 * @var string
 */
	public $useDbConfig = 'test';

}

/**
 * RedirectTestController class
 *
 * @package       Cake.Test.Case.Controller.Component
 */
class RedirectTestController extends Controller {

/**
 * name property
 *
 * @var string
 */
	public $name = 'RedirectModels';

/**
 * uses property
 *
 * @var array
 */
	public $uses = array('RedirectModel');

/**
 * components property
 *
 * @var array
 */
	public $components = array('Session', 'UtilityComponents.Redirect');

}

/**
 * RedirectComponentTest class
 *
 * @package       Cake.Test.Case.Controller.Component
 */
class RedirectComponentTest extends CakeTestCase {

/**
 * name property
 *
 * @var string
 */
	public $name = 'Redirect';

/**
 * fixtures property
 *
 * @var array
 */
	public $fixtures = array('plugin.utility_components.redirect_model');

/**
 * initialized property
 *
 * @var boolean
 */
	public $initialized = false;

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$request = new CakeRequest(null, false);

		$this->Controller = new RedirectTestController($request, $this->getMock('CakeResponse'));

		$this->Controller->Components->init($this->Controller);

		$this->initialized = true;
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();

		$this->Controller->Session->delete('Redirect');
		unset($this->Controller, $this->Redirect);
	}

/**
 * Test that the constructor sets the settings.
 *
 * @return void
 */
	public function testGetRefererBeforeEdit() {
		// GIVEN we set the referer as /posts/index
		$request = $this->getMock('CakeRequest');

		$request->expects($this->any())->method('referer')
			->with(false)
			->will($this->returnValue('/posts/index'));

		$this->Controller = new RedirectTestController($request, $this->getMock('CakeResponse'));

		$this->Controller->Components->init($this->Controller);

		// AND we initialize the component with the controller
		$this->Controller->Redirect->initialize($this->Controller);

		// AND the component is successfully started up
		$this->assertTrue($this->Controller->Redirect->startup($this->Controller));

		// WHEN we run the getRefererBeforeEdit without first setting any session values
		$referer = $this->Controller->Redirect->getRefererBeforeEdit();

		// THEN we expect the following values 
		$expected = '/posts/index';
		$this->assertEquals($expected, $referer);

		// @TODO dunno how to make the $this->Controller->Session correctly get influenced with the same value.
		// AND we expect the Session to still have that value under the key Redirect.RedirectModels.beforeEdit
	}
}