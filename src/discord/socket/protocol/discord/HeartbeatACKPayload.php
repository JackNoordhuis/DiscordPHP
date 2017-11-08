<?php

/**
 * HeartbeatACKPayload.php – DiscordPHP
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

namespace discord\socket\protocol\discord;

class HeartbeatACKPayload extends PayloadData {

	const OPCODE_ID = OpcodeInfo::OP_HEARTBEAT_ACK;

	public function unpack() {
		// No data is sent in a heartbeat acknowledgement
	}

	public function pack() {
		// No data is sent in a heartbeat acknowledgement
	}

}