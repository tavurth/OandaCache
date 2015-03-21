<?php

  /*

    Copyright 2015 William Whitty
    will.whitty.arbeit@gmail.com

    Licensed under the Apache License, Version 2.0 (the 'License');
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an 'AS IS' BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.

  */

if (defined('OANDA_CACHE_CONTROLLER') == FALSE) {
  define('OANDA_CACHE_CONTROLLER', TRUE);
	
  class OandaCache_Controller {
    protected $subjectArray;
		
    public function __construct() {
      $this->subjectArray = array();
    }
		
    protected function check($subjectId) {
      //Make sure the pairname is valid
      return strtoupper($subjectId);			
    }
		
    public function subjects() {
      //Return an array of all the subjects
      return $this->subjectArray;
    }
		
    public function subject($subjectId) {
      //Make sure the pairname is valid
      $subjectArrayRef = $this->check($subjectId);
			
      //Return the pair controller if valid
      return (isset($this->subjectArray[$subjectArrayRef]) ? $this->subjectArray[$subjectArrayRef] : FALSE);
    }
		
    public function subject_add($subjectId, $subjectArrayVar, $overwrite=FALSE) {
      //Make sure the subjectId is valid
      $subjectRef = $this->check($subjectId);
			
      //If the subject has not already been added
      if ($this->subject($subjectRef) === FALSE || $overwrite)
        $this->subjectArray[$subjectRef] = $subjectArrayVar;
			
      //Return the created subject or false
      return $this->subject($subjectRef);
    }
		
    public function subject_remove($subjectName) {
      //Make sure the subjectName is valid
      $subjectRef = $this->check($subjectName);
			
      //If the subject exists
      if ($this->subject($subjectRef) !== FALSE) {
        unset($this->subjectArray[$subject]);
      }
    }
  }
}
