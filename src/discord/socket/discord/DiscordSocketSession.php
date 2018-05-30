<?php

/**
 * DiscordSocketSession.php – DiscordPHP
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

use discord\socket\discord\protocol\DispatchPayload;
use discord\socket\discord\protocol\HeartbeatACKPayload;
use discord\socket\discord\protocol\HeartbeatPayload;
use discord\socket\discord\protocol\HelloPayload;
use discord\socket\discord\protocol\IdentifyPayload;
use discord\socket\discord\protocol\InvalidSessionPayload;
use discord\socket\discord\protocol\PayloadData;
use discord\socket\discord\protocol\ReconnectPayload;
use discord\socket\discord\protocol\RequestGuildMembersPayload;
use discord\socket\discord\protocol\ResumePayload;
use discord\socket\discord\protocol\StatusUpdatePayload;
use discord\socket\discord\protocol\VoiceStatusUpdatePayload;

abstract class DiscordSocketSession {

	abstract public function handlePayloadData(PayloadData $payload);

	public function handleDispatch(DispatchPayload $payload) : bool {
		return false;
	}

	public function handleHeartbeat(HeartbeatPayload $payload) : bool {
		return false;
	}

	public function handleIdentify(IdentifyPayload $payload) : bool {
		return false;
	}

	public function handleStatusUpdate(StatusUpdatePayload $payload) : bool {
		return false;
	}

	public function handleVoiceStatusUpdate(VoiceStatusUpdatePayload $payload) : bool {
		return false;
	}

	public function handleResume(ResumePayload $payload) : bool {
		return false;
	}

	public function handleReconnect(ReconnectPayload $payload) : bool {
		return false;
	}

	public function handleRequestGuildMembers(RequestGuildMembersPayload $payload) : bool {
		return false;
	}

	public function handleInvalidSession(InvalidSessionPayload $payload) : bool {
		return false;
	}

	public function handleHello(HelloPayload $payload) : bool {
		return false;
	}

	public function handleHeartbeatAck(HeartbeatACKPayload $payload) : bool {
		return false;
	}

}