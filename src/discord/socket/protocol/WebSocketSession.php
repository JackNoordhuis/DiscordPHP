<?php

/**
 * WebSocketSession.php – DiscordPHP
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

use discord\socket\protocol\discord\DispatchPayload;
use discord\socket\protocol\discord\HeartbeatACKPayload;
use discord\socket\protocol\discord\HeartbeatPayload;
use discord\socket\protocol\discord\HelloPayload;
use discord\socket\protocol\discord\IdentifyPayload;
use discord\socket\protocol\discord\InvalidSessionPayload;
use discord\socket\protocol\discord\PayloadData;
use discord\socket\protocol\discord\ReconnectPayload;
use discord\socket\protocol\discord\RequestGuildMembersPayload;
use discord\socket\protocol\discord\ResumePayload;
use discord\socket\protocol\discord\StatusUpdatePayload;
use discord\socket\protocol\discord\VoiceStatusUpdatePayload;

abstract class WebSocketSession {

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