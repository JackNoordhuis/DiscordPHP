<?php

/**
 * ClientSocketSession.php – DiscordPHP
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

namespace discord\socket\discord;

use discord\DiscordClient;
use discord\socket\discord\protocol\DispatchPayload;
use discord\socket\discord\protocol\HeartbeatACKPayload;
use discord\socket\discord\protocol\HeartbeatPayload;
use discord\socket\discord\protocol\HelloPayload;
use discord\socket\discord\protocol\InvalidSessionPayload;
use discord\socket\discord\protocol\PayloadData;
use discord\socket\discord\protocol\ReconnectPayload;

class ClientSocketSession extends DiscordSocketSession {

	/** @var DiscordClient */
	private $client;

	public function __construct(DiscordClient $client) {
		$this->client = $client;
	}

	public function handlePayloadData(PayloadData $payload) {
		if(!$payload->handle($this)) {
			$this->client->getLogger()->debug("Unhandled " . $payload->getName() . " received: " . json_encode($payload->getPayload()));
		}
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

	public function handleHello(HelloPayload $payload) : bool {
		$this->client->getClientSocket()->getInterface($payload->getConnectionId())->setHeartbeat($payload->heartbeatInterval);
		return true;
	}

	public function handleHeartbeatAck(HeartbeatACKPayload $payload) : bool {
		$this->client->getClientSocket()->getInterface($payload->getConnectionId())->clearHeartbeatAckTimer();
		return true;
	}

}