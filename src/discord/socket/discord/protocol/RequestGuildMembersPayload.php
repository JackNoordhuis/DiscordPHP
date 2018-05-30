<?php

/**
 * RequestGuildMembersPayload.php – DiscordPHP
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

class RequestGuildMembersPayload extends PayloadData {

	const OPCODE_ID = OpcodeInfo::OP_REQUEST_GUILD_MEMBERS;

	/** @var string */
	public $guildId;

	/** @var string */
	public $query;

	/** @var int */
	public $limit;

	public function unpack() {
		$this->guildId = $this->payload->guild_id;
		$this->query = $this->payload->query;
		$this->limit = $this->payload->limit;
	}

	public function pack() {
		$this->payload->guild_id = $this->guildId;
		$this->payload->query = $this->query;
		$this->payload->limit = $this->limit;
	}

	public function handle(DiscordSocketSession $session) : bool {
		return $session->handleRequestGuildMembers($this);
	}

}