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

  // This file kept seperate for debugging purposes.
  // To use OandaCache on your system, see readme.txt

  //error_reporting(E_ALL);
  //ini_set('display_errors', 1);
set_time_limit(0);

if (defined('OANDA_CACHE_INDEX') === FALSE) {
  define('OANDA_CACHE_INDEX', TRUE);
	
  //OandaWrap and setup
  require '../OandaWrap/OandaWrap.php';

  $configDir = './config/';
  $config    = $configDir . 'account.cfg';

  $ACCOUNT_KEY = FALSE;
  $ACCOUNT_NUM = FALSE;
  $ACCOUNT_TYP = FALSE;
	
  //Check to see if our config file exists
  if (file_exists($config) === FALSE)
	  throw new Exception('Failed to open Config,
	Config requires:
		ACCOUNT_KEY=YOUR_API_KEY
		ACCOUNT_NUM=YOUR_ACCOUNT_NUM
		ACCOUNT_TYP=Demo //(Or "Live")
	');

  //Load the values from the config file
  foreach (str_getcsv(file_get_contents($config), "\n") as $line) {
	  
	  //If the line is not a comment and contains an '=' sign
	  if (strpos($line, '=') !== FALSE && strpos($line, '/') === FALSE) {
		  
		//Split the line and analyse
		$data = str_getcsv($line, '=');
		switch ($data[0]) {
			case 'ACCOUNT_KEY':
				$ACCOUNT_KEY = $data[1];
				break;
			case 'ACCOUNT_NUM':
				$ACCOUNT_NUM = $data[1];
				break;
			case 'ACCOUNT_TYP':
				$ACCOUNT_TYP = $data[1];
				break;
		}
	  }
  }
  
  //Set up OandaWrap with the values from the config
  if (OandaWrap::setup($ACCOUNT_TYP, $ACCOUNT_KEY, $ACCOUNT_NUM, FALSE) === FALSE)
    throw new Exception('OandaWrap failed to initialize, check your API Key');

  //Local source files
  require 'source/Server.php';

  //Initialize the OandaCache
  OandaCache::setup();

  //Add each of the pairs from the pairs.cfg
  echo "Loading pair list...\n";
  OandaCache::pairs_add(str_getcsv(file_get_contents('./config/pairs.cfg'), "\n"));
        
  //Add each of the times from the times.cfg
  echo "Loading time settings...\n";
  OandaCache::times_add(str_getcsv(file_get_contents('./config/times.cfg'), "\n"));
        
  //Load our general config
  echo "Loading general config file... \n\n";
  OandaCache::global_load('./config/config.cfg');

  echo "\nStarting stream... \n";
  //Start streaming, argument is time delay between refresh
  OandaCache::stream(5);
}
