<?php

/*
 * This file is apart of the DiscordPHP project.
 *
 * Copyright (c) 2016 David Cole <david@team-reflex.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the LICENSE.md file.
 */

namespace discord\Exceptions\Rest;

use Discord\Exceptions\DiscordRequestFailedException;

/**
 * Thrown when a 404 Not Found response is received.
 */
class NotFoundException extends DiscordRequestFailedException
{
}
