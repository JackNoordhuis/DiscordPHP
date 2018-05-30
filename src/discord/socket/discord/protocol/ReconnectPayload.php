<?php

/**
 * ReconnectPayload.php – DiscordPHP
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

use discord\socket\discord\handler\DiscordSocketHandler;

class ReconnectPayload extends PayloadData {

	const OPCODE_ID = OpcodeInfo::OP_RECONNECT;

	public function unpack() {
		// No data is sent in a reconnect payload
	}

	public function pack() {
		// No data is sent in a reconnect payload
	}

	public function handle(DiscordSocketHandler $handler) : bool {
		return $handler->handleReconnect($this);
	}

}