<?php

/**
 * ISocketInterfaceHandler.php – DiscordPHP
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

namespace discord\socket;

use discord\DiscordClient;
use Ratchet\Client\Connector;

interface ISocketInterfaceHandler {

	/**
	 * @return DiscordClient
	 */
	public function getClient() : DiscordClient;

	/**
	 * @return Connector
	 */
	public function getSocketFactory() : Connector;

	/**
	 * @return string
	 */
	public function getGateway() : string;

}