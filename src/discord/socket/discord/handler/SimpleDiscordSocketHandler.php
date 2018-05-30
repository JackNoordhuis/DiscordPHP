<?php

/**
 * SimpleDiscordSocketHandler.php – DiscordPHP
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
use discord\socket\discord\protocol\DispatchPayload;
use discord\socket\discord\protocol\HeartbeatACKPayload;
use discord\socket\discord\protocol\HeartbeatPayload;
use discord\socket\discord\protocol\InvalidSessionPayload;
use discord\socket\discord\protocol\ReconnectPayload;

class SimpleDiscordSocketHandler extends DiscordSocketHandler {

	/** @var ClientSocketSession */
	private $session;

	public function __construct(ClientSocketSession $session) {
		$this->session = $session;
	}
	public function handleDispatch(DispatchPayload $payload) : bool {
		return false;
	}

	public function handleHeartbeat(HeartbeatPayload $payload) : bool {
		return false;
	}

	public function handleReconnect(ReconnectPayload $payload) : bool {
		return false;
	}

	public function handleInvalidSession(InvalidSessionPayload $payload) : bool {
		return false;
	}

	public function handleHeartbeatAck(HeartbeatACKPayload $payload) : bool {
		$this->session->clearHeartbeatAckTimer();
		return true;
	}

}