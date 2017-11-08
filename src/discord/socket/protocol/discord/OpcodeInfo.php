<?php
/**
 * OpcodeInfo.php – DiscordPHP
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

interface OpcodeInfo {

	// Dispatches an event
	const OP_DISPATCH = 0;

	// Used for ping checking
	const OP_HEARTBEAT = 1;

	// Used for client handshake
	const OP_IDENTIFY = 2;

	// Used to update the client presence
	const OP_STATUS_UPDATE = 3;

	// Used to join/move/leave voice channels
	const OP_VOICE_STATUS_UPDATE = 4;

	// Used for voice ping checking
	const OP_VOICE_SERVER_PING = 5;

	// Used to resume a closed connection
	const OP_RESUME = 6;

	// Used to redirect clients to a new gateway
	const OP_RECONNECT = 7;

	// Used to request member chunks
	const OP_REQUEST_GUILD_MEMBERS = 8;

	// Used to notify clients when they have an invalid session
	const OP_INVALID_SESSION = 9;

	// Used to pass through the heartbeat interval
	const OP_HELLO = 10;

	// Used to acknowledge heartbeats
	const OP_HEARTBEAT_ACK = 11;

}