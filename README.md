#OandaCache

    # Requires:
        OANDAWRAP (Find it in my github)
           I use OandaWrap in the folder above,
           Source:/
                OandaWrap/
                OandaCache/

           However please see fit to modify OandaCache.php to change this location.

    # Setup
        Make sure to edit OandaCache.php to add your Oanda API key and Account ID

        Run with "php OandaCache.php"

    
    OandaCache was written for the purpose of Caching Oanda's REST candles offline.

    The application allows access to these candles in their original JSON format.
    One candle per line (newline denominated "\n")

# Future

    I would like to incorporate reloading the executable, or rescanning directory.
    In this way the user could specify which repositary to load on demand.

    Now its fire and forget.

   Hope you enjoy, let me know if you have issues.