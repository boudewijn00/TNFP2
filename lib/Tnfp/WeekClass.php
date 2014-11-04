<?php

namespace Tnfp;

class WeekClass
{
    
    public $calendar = null;
    
    public $startParent = null;
    public $endParent = null;
    
    public function __construct(CalendarClass $calendar) 
    { 
             
        $this->calendar = $calendar;
        
    }
    
    public function renderWeek()
    {
        
        
        
    }
    
}

