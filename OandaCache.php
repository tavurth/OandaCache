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

error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(0);

if (defined('OANDA_CACHE_INDEX') == FALSE) {
	define('OANDA_CACHE_INDEX', TRUE);
	
	//OandaWrap and setup
	require '../OandaWrap/OandaWrap.php';
	OandaWrap::setup('Demo', 'd16fa71cc1c37bb41666e7186d153d35-881926f971b6a91cd64640cc0e3fdf5a', '4948376', FALSE);
	
	//Local source files
	require 'source/Server.php';

    //Initialize the OandaCache
	OandaCache::setup();

    //Add each of the pairs from the pairs.cfg
    echo "Loading pair list...\n";
	OandaCache::pairs_add(str_getcsv(file_get_contents('pairs.cfg'), "\n"));
        
    //Add each of the times from the times.cfg
    echo "Loading time settings...\n";
	OandaCache::times_add(str_getcsv(file_get_contents('times.cfg'), "\n"));
        
    //Load our general config
    echo "Loading general config file... \n";
	OandaCache::global_load('config.cfg');

    echo "Starting stream... \n";
    //Start streaming, argument is time delay between refresh
	OandaCache::stream(5);
}

?>