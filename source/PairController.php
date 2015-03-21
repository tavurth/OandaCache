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

if (defined('OANDA_CACHE_PAIR_CONTROLLER') === FALSE) {
  define('OANDA_CACHE_PAIR_CONTROLLER', TRUE);
	
  include 'Time.php';
	
  class OandaCache_PairController extends OandaCache_Controller {
    protected $pairName;
    protected $timeController;
		
    public function __construct($name) {
      parent::__construct();
			
      $this->pairName = $name;
      $this->timeController 	= new OandaCache_Controller();
    }
		
    public function time_add($timeGran) {
      return $this->timeController->subject_add($timeGran, new OandaCache_Time($timeGran));
    }
		
    public function time_remove($timeGran) {
      return $this->timeController->subject_remove($timeGran);
    }
		
    public function update() {
      foreach ($this->timeController->subjects() as $timeFractal)
        if ($timeFractal->update($this->pairName) === FALSE) 
          break;
    }
  }
}
