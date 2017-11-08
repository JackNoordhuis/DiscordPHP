<?php

/**
 * simple-logger.php – DiscordPHP
 *
 * Copyright (C) 2015-2017 Jack Noordhuis
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author Jack Noordhuiss
 *
 */

include __DIR__ . "/../vendor/autoload.php";

use discord\DiscordClient;

$client = new DiscordClient();

$client->getLogger()->info("This is a message!");

$client->start();