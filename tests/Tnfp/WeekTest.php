<?php

namespace Tnfp;

use Tnfp\Week;

class WeekTest extends \PHPUnit_Framework_TestCase 
{
    
    public $weekObject = null;
    public $eventItems = null;
    
    public function setUp()
    {
        
        $this->weekObject = new WeekClass(2014,1);
        
        $this->assertEquals($this->weekObject->startDate,"2013-12-30 08:00");
        $this->assertEquals($this->weekObject->endDate,"2014-01-06 08:00");
        
        $json = file_get_contents("/Users/boudewijnovervliet/Sites/TNFP2/data/events.json");
        
        $events = (array) json_decode($json);
        $this->eventItems = (array) $events["items"];
        
    }
    
    public function testEventBlocks()
    {
        
        $this->assertCount(5,$this->weekObject->getEventBlocks($this->eventItems));
        
    }
    
    public function testTimeBetweenEventItems()
    {
        
       $this->assertEquals(
               $this->weekObject->getTimeBetweenEventItems(
                       $this->eventItems[0]->end->dateTime, $this->eventItems[1]->start->dateTime
                ),
        360);
        
    }
    
    public function testCutOffNightTime()
    {
        
        $this->assertEquals(
               $this->weekObject->cutOffNightTime(
                       $this->eventItems[0]->start->dateTime
                ),
        19);
        
    }
    
}

