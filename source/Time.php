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

if (defined('OANDA_CACHE_TIME') == FALSE) {
  define('OANDA_CACHE_TIME', TRUE);
	
  include "Server.php";
	
  class OandaCache_Time {
    protected $lastUpdated;
    protected $granularity;
    protected $granSeconds;
    protected $candlesCount;
		
    public function __construct($gran) {
      $this->granularity = strtoupper($gran);
      $this->granSeconds = OandaWrap::gran_seconds($gran);
      $this->lastUpdated = 0;
      $this->candlesCount = 0;
    }
		
    protected function candles_cleanup($candles) {
      //If we recieved a valid candles object
      if (isset($candles->candles)) {
        foreach ($candles->candles as $key => $candle) {
          if ($candle->complete === FALSE || $candle->time <= $this->lastUpdated)
            { unset($candles->candles[$key]); continue; }
					
          $candle->low   = $candle->lowMid;
          $candle->high  = $candle->highMid;
          $candle->open  = $candle->openMid;
          $candle->close = $candle->closeMid;
					
          unset($candle->lowMid);
          unset($candle->highMid);
          unset($candle->openMid);
          unset($candle->closeMid);
          unset($candle->complete);
        }
      }
      return $candles;
    }
		
    protected function slice_file($outputFile) {
      if (file_exists($outputFile)) {
        //Read file into array of lines
        $contents = file($outputFile, FILE_IGNORE_NEW_LINES);
				
        //Calculate the new start position for sliced data
        $startPos = OandaCache::global_value('MAXIMUM_COUNT')*0.9;
				
        //Overwrite the file with the sliced data
        file_put_contents($outputFile, 	implode("\r\n", array_slice($contents, -$startPos)) . "\r\n");
				
        //Reset our counter
        $this->candlesCount = 0;
      }
    }
		
    protected function candles_output($pairRef, $candlesJson) {
      //If we recieved a valid candles object
      if (isset($candlesJson->candles)) {
        //Cleanup candles array
        $candles = $this->candles_cleanup($candlesJson)->candles;
        if (empty($candles))
          return;
				
        //Find where to save the data
        $outputFile   = OandaCache::global_value('DATA_LOCATION') . $pairRef . '_' . $this->granularity . '.csv';

        //Create the data to output
        $outputString = (file_exists($outputFile) ? "\r\n" : '') . implode("\r\n", array_map('json_encode', $candles));
				
        //Append the data to the file
        file_put_contents($outputFile, $outputString, FILE_APPEND);
				
        //Set our last update time
        $this->lastUpdated = end($candles)->time;
				
        //Check for overflow of maximum lines
        if ($this->candlesCount += count($candles) > OandaCache::global_value('MAXIMUM_COUNT'))
          $this->slice_file($outputFile);
					
        if (count($candles) > 0)
          return TRUE;
      }
			
      return FALSE;
    }
		
    public function update($pairRef) {
      //Calculate how many candles to retrieve
      $candlesToRetrieve = floor((time() - $this->lastUpdated) / $this->granSeconds);

      if ($candlesToRetrieve > 0) {
        if ($candlesToRetrieve > 4000)
          return $this->candles_output($pairRef, OandaWrap::candles_count($pairRef, $this->granularity, OandaCache::global_value('INITIATAL_COUNT')));
        else
          return $this->candles_output($pairRef, OandaWrap::candles_time($pairRef, $this->granularity, $this->lastUpdated, time()));
      }
      return FALSE;
    }
  }
}

?>