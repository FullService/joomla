<?php
require_once 'PHPUnit/Framework.php';


require_once JPATH_BASE.'/libraries/joomla/filesystem/path.php';
require_once JPATH_BASE.'/libraries/joomla/html/html.php';
require_once JPATH_BASE.'/libraries/joomla/html/parameter/element.php';
require_once JPATH_BASE.'/libraries/joomla/html/parameter/element/calendar.php';

/**
 * Test class for JElementCalendar.
 * Generated by PHPUnit on 2009-10-27 at 16:20:34.
 */
class JElementCalendarTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var JElementCalendar
	 */
	protected $object;

	protected $mockValues;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->mockValues = array();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
	}

	/**
	 * @todo Implement testFetchElement().
	 */
	public function testFetchElement()
	{
		$mock = $this->getMock('MyMockClass', array('calendar', 'behavior_calendar', 'attributes'));
		$mock->expects($this->once())
			->method('behavior_calendar')
			->with();

		$mock->expects($this->once())
			->method('calendar')
			->with('value', 'Calendar[test_calendar]', 'Calendartest_calendar', '%m-%d-%Y', array('class' => 'test_calendar_class'));

		JHtml::register('calendar', array($mock, 'calendar'));
		JHtml::register('behavior.calendar', array($mock, 'behavior_calendar'));

		$mock->expects($this->any())
			->method('attributes')
			->will($this->returnCallback(array($this, 'mockCallback')));

		$this->mockValues['format'] = '%m-%d-%Y';
		$this->mockValues['class'] = 'test_calendar_class';

		JElementCalendar::fetchElement('test_calendar','value', $mock, 'Calendar');

	}

	public function mockCallback()
	{
		$args = func_get_args();
		return $this->mockValues[$args[0]];
	}
}
