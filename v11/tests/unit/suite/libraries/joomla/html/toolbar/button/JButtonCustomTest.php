<?php
require_once 'PHPUnit/Framework.php';

require_once JPATH_BASE.'/libraries/joomla/html/toolbar/button.php';
require_once JPATH_BASE. DS . 'libraries' . DS . 'joomla' . DS . 'html' . DS . 'toolbar' . DS . 'button' . DS . 'custom.php';

/**
 * Test class for JButtonCustom.
 * Generated by PHPUnit on 2009-10-27 at 17:01:00.
 */
class JButtonCustomTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var JButtonCustom
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new JButtonCustom;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @todo Implement testFetchButton().
     */
    public function testFetchButton()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testFetchId().
     */
    public function testFetchId()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
}
?>