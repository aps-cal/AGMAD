<?php
/* 
 * The Small Group Event object
The small group event object returned by Tabula has the following properties:

id          A unique identifier for the small group event
title       The title of the small group event
weeks       An array of objects representing the minimum and maximum weeks that 
            the event can take place. Each element has two keys, minWeek and maxWeek
day         The day of the week that the event takes place on, e.g. Monday
startTime   The start time of the event, in the format HH:mm
endTime     The end time of the event, in the format HH:mm
location    An object representing the location of the event, or null if not defined. 
            The object has one required property, name, and an optional property locationId 
            which relates to the room's unique identifier on the Warwick campus map
tutors      An array containing all of the tutors for this event. Each element has two keys, userId and universityId
 */

namespace App\Tabula;

use \Illuminate\Support\Facades\DB;

class SmallGroupEvent {
    
    public function CreateGroupEvent($title, $day, $startTime, $endTime, $weeks, $tutors, $location) {
        /*
        {
            "title": "Event with attendance",
            "day": "1",
            "startTime": ""09:30",
            "endTime": "11:30",
            "weeks": [8,9,14],
            "tutors": ["esunxx","esudfs"],
            "location": "CS1.04"
        }
        */
    }
    
    public function UpdateGroupEvent() {
        
    }
    
    public function DeleteGroupEvent() {
        
    }
    
}