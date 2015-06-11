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

set_time_limit(0);

if (defined('OANDA_CACHE_INDEX') === FALSE) {
    define('OANDA_CACHE_INDEX', TRUE);
	
    //OandaWrap and setup
    require 'OandaWrap.php';

    //Local source files
    require 'source/Server.php';

    //Initialize the OandaCache
    OandaCache::setup();

    echo "Loading account config:\n\n";
    OandaCache::global_load('./config/account.cfg');
    
    $accountId   = OandaCache::global_value('ACCOUNT_KEY');
    $accountKey  = OandaCache::global_value('ACCOUNT_NUM');
    $accountType = OandaCache::global_value('ACCOUNT_TYP');
    
    //Set up OandaWrap with the values from the config
    if (OandaWrap::setup($accountType, $accountId, $accountKey, FALSE) === FALSE)
	{ echo "\nOandaWrap failed to initialize, check your API Key in ./config/accounts.cfg\n"; die(1); }

    //Add each of the pairs from the pairs.cfg
    echo "\nLoading pair list...\n";
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