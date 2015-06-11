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

if (defined('OANDA_TICK_CACHE_INDEX') === FALSE) {
    define('OANDA_TICK_CACHE_INDEX', TRUE);
	
    //OandaWrap and setup
    require 'source/OandaWrap.php';

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
    if (OandaWrap::setup($accountType, $accountId, $accountKey, FALSE) === FALSE) {
        echo "\nOandaWrap failed to initialize, check your API Key in ./config/accounts.cfg\n";
        die(1);
    }

    function tick_writer($jsonObject) {
        if (isset($jsonObject->tick)) {
            $bid  = $jsonObject->tick->bid;
            $ask  = $jsonObject->tick->ask;
            $time = $jsonObject->tick->time;
            $name = $jsonObject->tick->instrument;
            unset($jsonObject->tick->instrument);

            file_put_contents('./data/' . $name . '_TICK.csv', json_encode($jsonObject->tick) . "\n", FILE_APPEND);
        }
    };

    echo "\nStreaming ticks...";
    OandaWrap::stream(tick_writer, str_getcsv(file_get_contents('./config/pairs.cfg'), "\n"), false);
}
