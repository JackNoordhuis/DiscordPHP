<?php

include __DIR__ . "/../vendor/autoload.php";

use discord\DiscordClient;

$client = new DiscordClient([]);

$client->getLogger()->info("This is a message!");