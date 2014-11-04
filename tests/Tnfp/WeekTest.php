<?php

namespace Tnfp;

use Tnfp\WeekClass;
use Tnfp\CalendarClass;

class WeekTest extends \PHPUnit_Framework_TestCase 
{
    
    public $week = null;
    
    public function setUp()
    {
        
        $calendar = new CalendarClass(2014,1);
        $this->week = new WeekClass($calendar);
        
    }
    
    public function testWeekObject()
    {
        $this->assertObjectHasAttribute('calendar',$this->week);
    }
    
}

