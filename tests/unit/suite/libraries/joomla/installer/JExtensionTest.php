<?php
require_once 'PHPUnit/Framework.php';

require_once JPATH_BASE . '/libraries/joomla/installer/extension.php';

/**
 * Test class for JExtension.
 * Generated by PHPUnit on 2009-10-27 at 15:19:58.
 */
class JExtensionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var JExtension
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new JExtension;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

	/**
	 * @todo Decide how to Implement.
	 */
	public function testDummy() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
?>