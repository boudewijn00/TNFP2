<?php

namespace Tnfp;

use Tnfp\CalendarClass;

class CalendarTest extends \PHPUnit_Framework_TestCase 
{
    
    public $calendarObject = null;
    
    public function setUp()
    {
        
        
    }
    
    public function testCalendarObject()
    {
        
        $calendar = new CalendarClass(2014,1);
        
        $this->assertEquals($calendar->startDate,"2013-12-30 08:00");
        $this->assertEquals($calendar->endDate,"2014-01-06 08:00");
        
    }
    
    public function testEventBlocks()
    {
        
        $calendar = new CalendarClass(2014,1);
        $events = $calendar->getEvents();
        
        $this->assertCount(5,$calendar->getEventBlocks($events));
        
    }
    
    public function testTimeBetweenEvents()
    {
        
       $calendar = new CalendarClass(2014,1);
       $events = $calendar->getEvents();
        
       $this->assertEquals(
               $calendar->getTimeBetweenEvents(
                       $events[0]->end->dateTime, $events[1]->start->dateTime
                ),
        420);
        
    }
    
    public function testCutOffNightTime()
    {
        
        $calendar = new CalendarClass(2014,1);
        
        $this->assertEquals(
               $calendar->cutOffNightTime(
                       "2014-01-01 21:00"
                ),
        "2014-01-01 20:00");
        
    }
    
    public function testStartParent()
    {
        
        // previous
        $pCalendar = new CalendarClass(2014,1);
        $pEvents = $pCalendar->getEvents();
        $pCalendar->getEventBlocks($pEvents);
        
        // current
        $cCalendar = new CalendarClass(2014,2);
        $cCalendar->setParent("start", $pCalendar->endParent);
        
        $this->assertEquals($cCalendar->startParent,"care taker 2");
        
        $cEvents = $cCalendar->getEvents();
        \Zend\Debug\Debug::dump($cCalendar->getEventBlocks($cEvents));
        
    }
    
}

