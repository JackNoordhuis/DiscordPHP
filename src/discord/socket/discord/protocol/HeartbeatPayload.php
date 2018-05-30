<?php

/**
 * HeartbeatPayload.php – DiscordPHP
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

namespace discord\socket\discord\protocol;

use discord\socket\discord\DiscordSocketSession;

class HeartbeatPayload extends PayloadData {

	const OPCODE_ID = OpcodeInfo::OP_HEARTBEAT;

	/** @var int */
	public $data;

	public function unpack() {
		$this->data = $this->payload->d;
	}

	public function pack() {
		$this->payload->d = $this->data;
	}

	public function handle(DiscordSocketSession $session) : bool {
		return $session->handleHeartbeat($this);
	}

}