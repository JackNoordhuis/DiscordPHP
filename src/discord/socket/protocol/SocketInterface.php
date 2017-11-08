<?php

/**
 * SocketInterface.php – DiscordPHP
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

namespace discord\socket\protocol;

use discord\socket\protocol\discord\PayloadData;

interface SocketInterface {

	/**
	 * Performs actions needed to start the interface after it is registered.
	 */
	public function start();

	/**
	 * @param PayloadData $payload
	 *
	 * @return mixed
	 */
	public function putPayload(PayloadData $payload);

	/**
	 * @param \stdClass $data
	 */
	public function processPayload(\stdClass $data);

	/**
	 * @return string
	 */
	public function getName() : string;

	public function shutdown();

}