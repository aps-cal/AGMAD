<?php

/* 
    The Small Group object
    The small group object returned by Tabula has the following properties:

    id              A unique identifier for the small group
    name            The name of the small group
    students        An array containing all of the students in the small group. 
                    Each element has two keys, userId and universityId
    maxGroupSize    The maximum size of the group (used when students sign up), if set
    events          An array of small group event objects for each event in the group
 */



namespace App\Tabula;

use \Illuminate\Support\Facades\DB;

class SmallGroup {
    
    var $id;
    var $name;
    var $students;
    var $maxGroupSize;
    var $events;
    
    
    public function Create() {
        
    }
    
    public function Update() {
        
    }
    
    public function Delete() {
        
    }
    
    public function Load() {
        
    }
    
    public function Save() {
        
    }
    
}