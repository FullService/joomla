<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @package		JoomlaFramework
 */
                // ****************************************
                //Complusoft JoomlaTeam - Antonio Escobar
                //                      -Pedro Vidal
                //                      - Sergio Iglesias
                //                      - Jonathan Bar-Magen
                //
                //Support: JoomlaTeam@Complusoft.es
                //*****************************************
require_once JPATH_BASE.'/libraries/joomla/access/access.php';
/**
 * Test class for JAccess.
 * Generated by PHPUnit on 2009-10-08 at 11:50:03.
 * @package		JoomlaFramework
 */

class JAccessTest extends PHPUnit_Framework_TestCase {
	/**
	 * @var    JAccess
	 * @access protected
	 */
	protected $object;
        var $have_db = false;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp() {
           
	    $this->object = new JAccess;  

        }
	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown() {
	}

	/**
	 * @todo Implement testCheck().
	 */

	public function testCheck() {
            $access = new JAccess();
            $userId = 1;
            $action = 1;
            $asset  = 1;
            $this->assertTrue($access->check('42','core.admin',3));
           // var_dump($access->getUsersByGroup(7,True));
            //var_dump($access->getAuthorisedViewLevels('42'));
            //var_dump($access->getActions('com_banners','component'));
            //var_dump($access->getAssetRules(3, True));
        }


	/**
	 * @todo Implement testGetAssetRules().
	 */
	public function testGetAssetRules() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

        public function testGetUsersByGroup() {
		$access = new JAccess();
		$array1 = array(
			0	=> 42
		);
                $this->assertThat(
			$array1,
			$this->equalTo($access->getUsersByGroup(8, True))
		);
                $this->assertThat(
			$array1,
			$this->equalTo($access->getUsersByGroup(7, True))
		);

                $array2 = array();
                $this->assertThat(
			$array2,
			$this->equalTo($access->getUsersByGroup(7, False))
		);
	}

	/**
	 * @todo Implement testGetGroupsByUser().
	 */
	public function testGetGroupsByUser() {

                $access = new JAccess();
		$array1 = array(
			0	=> 1,
			1	=> 2,
			2	=> 6,
			3	=> 7,
                        4	=> 8
		);
                $this->assertThat(
			$array1,
			$this->equalTo($access->getGroupsByUser(42, True))
		);
                $array2 = array(
                  0     => 8
                );
                $this->assertThat(
			$array2,
			$this->equalTo($access->getGroupsByUser(42, False))
		);

	}

	/**
	 * @todo Implement testGetAuthorisedViewLevels().
	 */
	public function testGetAuthorisedViewLevels() {
		$access = new JAccess();
		$array1 = array(
			0	=> 1,
                        1       => 2,
                        2       => 3
		);
               
                
                $this->assertThat(
			$array1,
			$this->equalTo($access->getAuthorisedViewLevels(42))
		);
                
                $array2 = array(
                    0       => 1
                );
                $this->assertThat(
			$array2,
			$this->equalTo($access->getAuthorisedViewLevels(50))
		);
               


	}

	/**
	 * @todo Implement testGetActions().
	 */
	public function testGetActions() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
?>
