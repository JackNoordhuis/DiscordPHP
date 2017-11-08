<?php

/**
 * Utils.php – DiscordPHP
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

namespace discord\util;

class Utils {

	/**
	 * Build a http get request
	 *
	 * @param string $uri
	 * @param array $params
	 *
	 * @return string
	 */
	public static function buildParams(string $uri, array $params) : string {
		return trim($uri, "/") . "/?" . http_build_query($params);
	}

}