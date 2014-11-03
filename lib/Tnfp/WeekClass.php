<?php

namespace Tnfp;

class WeekClass
{
    
    public $startDate = "";
    public $endDate = "";
    
    public function __construct($year,$week) 
    { 
             
        date_default_timezone_set('CET');
        
        $this->startDate = (string) date("Y-m-d H:i", strtotime($year."W".(sprintf("%02s", $week))." + 8 hour"));
        $this->endDate = (string) date("Y-m-d H:i", strtotime($year."W".(sprintf("%02s", $week+1))." + 8 hour"));
        
    }
    
    /**
     * iterate through event items and make event blocks out of it
     */
    public function getEventBlocks($eventItems)
    {
        
        $blocks = array();
        
        if($eventItems){
        
            $totalEventItems = count($eventItems);
            
            // the duration of beginning of the week till the first event
            $minutes = $this->getTimeBetweenEventItems($this->startDate, $eventItems[0]->start->dateTime);
            $blocks[] = array("type" => "care", "duration" => $minutes);
            
            for($i=0; $i<$totalEventItems; $i++){
                
                // the duration of the event itself
                $minutes = $this->getTimeBetweenEventItems($eventItems[$i]->start->dateTime, $eventItems[$i]->end->dateTime);
                $blocks[] = array("type" => "event", "duration" => $minutes);
                
                // the duration between this event and the next
                if(key_exists($i,$eventItems) && key_exists($i+1,$eventItems)){
                    $minutes = $this->getTimeBetweenEventItems($eventItems[$i]->end->dateTime, $eventItems[$i+1]->start->dateTime);
                    $blocks[] = array("type" => "care", "duration" => $minutes);
                }
                
            }
            
            // the duration of the last event till the end of the week
            $minutes = $this->getTimeBetweenEventItems($eventItems[$totalEventItems-1]->end->dateTime,$this->endDate);
            $blocks[] = array("type" => "care", "duration" => $minutes);
                
        }
        
        \Zend\Debug\Debug::dump($blocks);
        
        return $blocks;
        
    }
    
    public function getTimeBetweenEventItems($dateTime1, $dateTime2)
    {
        
        //\Zend\Debug\Debug::dump($dateTime1);
        //\Zend\Debug\Debug::dump($dateTime2);
        
        $difference =  strtotime($dateTime2) - strtotime($dateTime1);
        
        $days = round($difference / (60*60*24));    

        //\Zend\Debug\Debug::dump($days);
        
        // total minutes is day and night
        $totalMinutes = round(abs($difference) / 60,2);

        // day minutes is total minutes minus night
        $dayMinutes = $totalMinutes - ($days * 720);
        
        return $dayMinutes;
        
    }
    
    public function cutOffNightTime($dateTime)
    {
        
        $dateHour = date("H",strtotime($dateTime));
        
        return $dateHour;
        
    }
    
}

