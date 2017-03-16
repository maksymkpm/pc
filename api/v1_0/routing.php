<?php
// try load routing table from cache, if server is not in debug mode
if (\rest\router::loadFromCache()) {
	return;
}

// Set default routing settings. Controllers and actions can rewrite default values
// Every setting has default value in code, but it obvious to set them for current version of
// the server is better practice
\rest\router::setAllowedContentTypes(\rest\contentType::JSON); // default is JSON
\rest\router::setHttpsOnly('no'); // default is NO
\rest\router::setIpBlacklist(); // default is empty, can be single IP (192.168.1.1) or network mask (192.168.1.0/24) or a range (192.168.1.0-192.168.1.255)
\rest\router::setIpWhitelist(); // default is empty, can be single IP (192.168.1.1) or network mask (192.168.1.0/24) or a range (192.168.1.0-192.168.1.255)

// we can put every controller routing to separate files for easy managing and split API areas between developers

include __DIR__ . '/routing/Test.php';
include __DIR__ . '/routing/Issue.php';
include __DIR__ . '/routing/Comment.php';