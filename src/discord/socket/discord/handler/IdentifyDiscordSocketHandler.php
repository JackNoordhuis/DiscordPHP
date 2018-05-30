<?php

/**
 * IdentifyDiscordSocketHandler.php – DiscordPHP
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

namespace discord\socket\discord\handler;

use discord\socket\discord\ClientSocketSession;
use discord\socket\discord\protocol\HelloPayload;

class IdentifyDiscordSocketHandler extends DiscordSocketHandler {

	/** @var ClientSocketSession */
	private $session;

	public function __construct(ClientSocketSession $session) {
		$this->session = $session;
	}

	public function handleHello(HelloPayload $payload) : bool {
		$this->session->setHeartbeat($payload->heartbeatInterval);
		return true;
	}

}