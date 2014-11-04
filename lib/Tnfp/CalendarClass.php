<?php

namespace Tnfp;

class CalendarClass
{
    
    public $year = null;
    public $week = null;
    
    public $startDate = "";
    public $endDate = "";
    
    public $startParent = "";
    public $endParent = "";
    
    /**
     * determine for which week we want calendar events
     * @param type $year
     * @param type $week
     */
    public function __construct($year,$week)
    {
        
        $this->year = $year;
        $this->week = $week;
        
        date_default_timezone_set('CET');
        
        $this->startDate = (string) date("Y-m-d H:i", strtotime($year."W".(sprintf("%02s", $week))." + 8 hour"));
        $this->endDate = (string) date("Y-m-d H:i", strtotime($year."W".(sprintf("%02s", $week+1))." + 8 hour"));
        
    }
    
    public function getEvents()
    {
        
        $json = file_get_contents("/Users/boudewijnovervliet/Sites/TNFP2/data/events-$this->week.json");
        
        $events = (array) json_decode($json);
        $events = (array) $events["items"];
        
        return $events;
        
    }
    
    /**
     * iterate through event items and make event blocks out of it
     */
    public function getEventBlocks($events)
    {
        
        $blocks = array();
        $parent = "";
        
        if($events){
        
            $totalEvents = count($events);
            
            // the duration of beginning of the week till the first event
            $minutes = $this->getTimeBetweenEvents($this->startDate, $events[0]->start->dateTime);
            $blocks[] = array("type" => "care", "duration" => $minutes, "parent" => $this->startParent);
            
            for($i=0; $i<$totalEvents; $i++){
                
                // the duration of the event itself
                $minutes = $this->getTimeBetweenEvents($events[$i]->start->dateTime, $events[$i]->end->dateTime);
                $blocks[] = array("type" => "event", "duration" => $minutes);
                
                $parent = $events[$i]->summary;
                
                // the duration between this event and the next
                if(key_exists($i,$events) && key_exists($i+1,$events)){
                    $minutes = $this->getTimeBetweenEvents($events[$i]->end->dateTime, $events[$i+1]->start->dateTime);
                    $blocks[] = array("type" => "care", "duration" => $minutes, "parent" => $parent);
                }
                
            }
            
            // the duration of the last event till the end of the week
            $minutes = $this->getTimeBetweenEvents($events[$totalEvents-1]->end->dateTime,$this->endDate);
            $blocks[] = array("type" => "care", "duration" => $minutes, "parent" => $parent);
            
            // store the last parent as end parent of this calendar week
            $this->setParent("end", $parent);
                
        }
        
        //\Zend\Debug\Debug::dump($blocks);
        
        return $blocks;
        
    }
    
    public function getTimeBetweenEvents($dateTime1, $dateTime2)
    {
        
        $dateTime1 = $this->cutOffNightTime($dateTime1);
        $dateTime2 = $this->cutOffNightTime($dateTime2);
        
        $difference =  strtotime($dateTime2) - strtotime($dateTime1);
        
        $days = round($difference / (60*60*24));    
        
        // total minutes is day and night
        $totalMinutes = round(abs($difference) / 60,2);

        // day minutes is total minutes minus night
        $dayMinutes = $totalMinutes - ($days * 720);
        
        return $dayMinutes;
        
    }
    
    public function cutOffNightTime($dateTime)
    {
        
        $dateHour = date("H",strtotime($dateTime));
        
        if($dateHour > "20"){
            $dateTime = date("Y-m-d", strtotime($dateTime));
            $dateTime = date("Y-m-d H:i", strtotime($dateTime." + 20 hour"));
        } elseif($dateHour < "8"){
            $dateTime = date("Y-m-d", strtotime($dateTime));
            $dateTime = date("Y-m-d H:i", strtotime($dateTime." + 8 hour"));
        }
        
        return $dateTime;
        
    }
    
    public function setParent($type,$parent)
    {
        
        if($type == "end"){
            $this->endParent = $parent;
        } elseif($type == "start"){
            $this->startParent = $parent;
        }
        
    }
    
}

