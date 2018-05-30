<?php

/**
 * DispatchPayload.php – DiscordPHP
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

use discord\socket\discord\WebSocketSession;

class DispatchPayload extends PayloadData {

	const OPCODE_ID = OpcodeInfo::OP_DISPATCH;


	const EVENT_READY = "READY";
	const EVENT_RESUMED = "RESUMED";

	const EVENT_CHANNEL_CREATE = "CHANNEL_CREATE";
	const EVENT_CHANNEL_UPDATE = "CHANNEL_UPDATE";
	const EVENT_CHANNEL_DELETE = "CHANNEL_DELETE";
	const EVENT_CHANNEL_PINS_UPDATE = "CHANNEL_PINS_UPDATE";

	const EVENT_GUILD_CREATE = "GUILD_CREATE";
	const EVENT_GUILD_UPDATE = "GUILD_UPDATE";
	const EVENT_GUILD_DELETE = "GUILD_DELETE";
	const EVENT_GUILD_BAN_ADD = "GUILD_BAN_ADD";
	const EVENT_GUILD_BAN_REMOVE = "GUILD_BAN_REMOVE";
	const EVENT_GUILD_EMOJIS_UPDATE = "GUILD_EMOJIS_UPDATE";
	const EVENT_GUILD_INTEGRATIONS_UPDATE = "GUILD_INTEGRATIONS_UPDATE";
	const EVENT_GUILD_MEMBER_ADD = "GUILD_MEMBER_ADD";
	const EVENT_GUILD_MEMBER_REMOVE = "GUILD_MEMBER_REMOVE";
	const EVENT_GUILD_MEMBER_UPDATE = "GUILD_MEMBER_UPDATE";
	const EVENT_GUILD_MEMBER_MEMBERS_CHUNK = "GUILD_MEMBER_MEMBERS_CHUNK";
	const EVENT_GUILD_ROLE_CREATE = "GUILD_ROLE_CREATE";
	const EVENT_GUILD_ROLE_UPDATE = "GUILD_ROLE_UPDATE";
	const EVENT_GUILD_ROLE_DELETE = "GUILD_ROLE_DELETE";

	const EVENT_MESSAGE_CREATE = "MESSAGE_CREATE";
	const EVENT_MESSAGE_UPDATE = "MESSAGE_CREATE";
	const EVENT_MESSAGE_DELETE = "MESSAGE_CREATE";
	const EVENT_MESSAGE_DELETE_BULK = "MESSAGE_CREATE";
	const EVENT_MESSAGE_REACTION_ADD = "MESSAGE_CREATE";
	const EVENT_MESSAGE_REACTION_REMOVE = "MESSAGE_CREATE";
	const EVENT_MESSAGE_REACTION_REMOVE_ALL = "MESSAGE_CREATE";

	const EVENT_PRESENCE_UPDATE = "PRESENCE_UPDATE";
	const EVENT_TYPING_START = "TYPING_START";
	const EVENT_USER_UPDATE = "USER_UPDATE";
	const EVENT_VOICE_STATE_UPDATE = "VOICE_STATE_UPDATE";
	const EVENT_VOICE_SERVER_UPDATE = "VOICE_SERVER_UPDATE";
	const EVENT_WEBHOOKS_UPDATE = "WEBHOOKS_UPDATE";


	/** @var object|integer|bool */
	public $data;

	/** @var int */
	public $sequenceNumber;

	/** @var string */
	public $eventName;

	public function unpack() {
		$this->data = $this->payload->d;
		$this->sequenceNumber = $this->payload->s;
		$this->eventName = $this->payload->t;
	}

	public function pack() {
		$this->payload->d = $this->data;
		$this->payload->s = $this->sequenceNumber;
		$this->payload->t = $this->eventName;
	}

	public function handle(WebSocketSession $session) : bool {
		return $session->handleDispatch($this);
	}

}