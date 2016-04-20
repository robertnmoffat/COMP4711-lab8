<?php

/* 
 * Timetable model for XML lab
 * 
 */

class Timetable extends CI_Model {
    
    protected $XMLClasses = null; 
    protected $classes = array();
    
    protected $XMLTimes = null;
    protected $times = array();
    
    protected $XMLDays = null;   
    protected $days = array(); 
    
    
    function __construct() {
        parent::__construct();
        
        $this->XMLDays = simplexml_load_file('weekDays.xml');
        $this->XMLTimes = simplexml_load_file('times.xml');
        $this->XMLClasses = simplexml_load_file('classes.xml');
        
        //loop through all the days
        foreach ($this->XMLDays->day as $day) {
            $type = (string)$day['type'];
            $bookings = array();
            foreach ($day->booking as $booking) {
                $r = new stdClass();
                $r->instructor = (string)$booking['instructor'];
                $r->room = (string)$booking['room'];
                $r->type = (string)$booking['type'];
                $r->time = (string)$booking['time'];
                $r->name = (string)$booking['name'];
                array_push($bookings, $r);
            }
            $this->days[$type] = $bookings;
        }
        
        //loop through all the times
        foreach ($this->XMLTimes->times as $time) {
            $type = (string)$time['time'];
            $bookings = array();
            foreach ($time->booking as $booking) {
                $r = new stdClass();
                $r->instructor = (string)$booking['instructor'];
                $r->room = (string)$booking['room'];
                $r->type = (string)$booking['type'];
                $r->day = (string)$booking['day'];
                $r->name = (string)$booking['name'];
                array_push($bookings, $r);
            }
            $this->times[$type] = $bookings;
        }
        
        //loop through all the classes
        foreach ($this->XMLClasses->classes as $class) {
            $booking = $class->booking;
            $type = (string)$class['code'];
            $r = new stdClass();
            $r->instructor = (string)$booking['instructor'];
            $r->room = (string)$booking['room'];
            $r->type = (string)$booking['type'];
            $r->day = (string)$booking['day'];
            $this->classes[$type] = $r;
        }
    }
    
    //get array of classes
    function getClasses(){
        return $this->classes;        
    }
    
    //get array of times
    function getTimes(){
        return $this->times;        
    }
    
    //get array of days
    function getDays() {
        return $this->days;
    }
    
    //get a singe day
    function getSingleDay($type) {
        if (isset($this->days[$type]))
            return $this->days[$type];
        else
            return null;
    }
}