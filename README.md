[![Code Climate](https://codeclimate.com/github/tavurth/OandaCache/badges/gpa.svg)](https://codeclimate.com/github/tavurth/OandaCache)

#OandaCache

    # Requires:
        OANDAWRAP (Find it in my github)
           I use OandaWrap in the folder above,
           Source:/
                OandaWrap/
                OandaCache/

           However please see fit to modify OandaCache.php to change this location.

    # Setup

        Config files can be opened with a simple text editing program.
    
        You need to put your Oanda login information into the file:
        OandaCache/config/account.cfg

        Maximum number of candles, timezone etc can be set:
        OandaCache/config/config.cfg

        Different pairs can be loaded:
        OandaCache/config/pairs.cfg

        Different time frames can be saved:
        OandaCache/config/times.cfg

        Run with "php OandaCache.php"

    
    OandaCache was written for the purpose of Caching Oanda's REST candles offline.

    The application allows access to these candles in their original JSON format.
    One candle per line (newline denominated "\n")

    The candles come in the format:

    {"time":TIME_IN_SECONDS,"volume":VOLUME_AS_INTEGER,"low":0.96897,"high":0.96899,"open":0.96897,"close":0.968975}

# Future

    I would like to incorporate reloading the executable, or rescanning directory.
    In this way the user could specify which repositary to load on demand.

    Now its fire and forget.
    
   Hope you enjoy, let me know if you have issues.