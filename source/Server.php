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

if (defined('OANDA_CACHE_SERVER') == FALSE) {
	define('OANDA_CACHE_SERVER', TRUE);

	require 'Controller.php';
	require 'PairController.php';
	
	class OandaCache extends OandaCache_Controller {
		protected static $pairController;
		protected static $globalController;
		
		public static function remove_dir($dir) { 
			$files = array_diff(scandir($dir), array('.','..')); 
			foreach ($files as $file)
				(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
			return rmdir($dir); 
		}
		
		public static function setup() {
			self::$pairController   = new OandaCache_Controller();
			self::$globalController = new OandaCache_Controller();
			
			self::global_set('DATA_LOCATION',  'data/');
			if (is_dir(self::global_value('DATA_LOCATION')))
				self::remove_dir(self::global_value('DATA_LOCATION'));
			mkdir(self::global_value('DATA_LOCATION'));
			
			self::global_set('INITIATAL_COUNT',   500);
			self::global_set('MAXIMUM_COUNT',     2000);
		}
		
		public static function global_load($loc) {
			if (file_exists($loc))
				foreach (str_getcsv(file_get_contents($loc), "\n") as $line) {
					if (strpos($line, '=')) {
						$parts = str_getcsv($line, '=');
						self::global_set($parts[0], $parts[1]);
					}
				}

            //Set up the system ti
            if (strlen(self::global_value('TIMEZ')) !== 0)
              date_default_timezone_set(self::global_value('TIMEZ'));
		}
		
		public static function global_value($globalName) {
			return self::$globalController->subject($globalName);
		}
		
		public static function global_set($globalName, $globalValue) {
			return self::$globalController->subject_add($globalName, $globalValue, TRUE);
		}
		
		public static function pair($pairName) {
			return self::$pairController->subject($pairName);
		}
		
		public static function pairs_add(array $pairs) {
			foreach ($pairs as $pair)
				self::pair_add($pair);
		}
		
		public static function times_add(array $times) {
			foreach (self::$pairController->subjects() as $pair) 
				foreach ($times as $time)
					$pair->time_add($time);
		}
		
		public static function pair_add($pairName) {
			return self::$pairController->subject_add($pairName, new OandaCache_PairController($pairName));
		}
		
		public static function pair_remove($pairName) {
			return self::$pairController->subject_remove($pairName);
		}
		
		public static function stream() {
			$startTimer   = 0;
			$sleepTimer   = 5;
            echo "Streaming...\n";
			while (sleep($sleepTimer) == 0) {
				//Once per day
				if (time() > $startTimer) {
					//Reset the timer
					$startTimer = time() + 86400;
					//And set the appropriate time until next wake
					$sleepTimer = (date('N', time()) >= 6) ? 3600 : 5;
				}
				//Loop and update each pairController
				foreach (self::$pairController->subjects() as $pair)
					$pair->update();
			}
		}
	}
}

?>