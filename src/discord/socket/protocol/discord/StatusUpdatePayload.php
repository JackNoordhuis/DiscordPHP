<?php

/**
 * StatusUpdatePayload.php – DiscordPHP
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

class StatusUpdatePayload extends PayloadData {

	const OPCODE_ID = OpcodeInfo::OP_PRESENCE_UPDATE;


	const STATUS_TYPE_ONLINE = "online";
	const STATUS_TYPE_DO_NOT_DISTURB = "dnd";
	const STATUS_TYPE_IDLE = "idle";
	const STATUS_TYPE_INVISIBLE = "invisible";
	const STATUS_TYPE_OFFLINE = "offline";


	/** @var int|null */
	public $since = null;

	/** @var object|null */
	public $game = null;

	/** @var string */
	public $status;

	/** @var bool */
	public $afk;

	public function unpack() {
		$this->since = $this->payload->since;
		$this->game = $this->payload->game;
		$this->status = $this->payload->status;
		$this->afk = $this->payload->afk;
	}

	public function pack() {
		$this->payload->since = $this->since;
		$this->payload->game = $this->game;
		$this->payload->status = $this->status;
		$this->payload->afk = $this->afk;
	}

}