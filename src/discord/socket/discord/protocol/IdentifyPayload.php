<?php

/**
 * IdentifyPayload.php – DiscordPHP
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

class IdentifyPayload extends PayloadData {

	const OPCODE_ID = OpcodeInfo::OP_IDENTIFY;

	/** @var string */
	public $token;

	/** @var object */
	public $properties;

	/** @var bool */
	public $compress;

	/** @var int */
	public $threshold;

	/** @var int[2] */
	public $shard;

	/** @var object */
	public $presence;

	public function unpack() {
		$this->token = $this->payload->token;
		$this->properties = $this->payload->properties;
		$this->compress = $this->payload->compress;
		$this->threshold = $this->payload->large_threshold;
		$this->shard = $this->payload->shard;
		$this->presence = $this->payload->presence;
	}

	public function pack() {
		$this->payload->token = $this->token;
		$this->payload->properties = $this->properties;
		$this->payload->compress = $this->compress;
		$this->payload->large_threshold = $this->threshold;
		$this->payload->shard = $this->shard;
		$this->payload->presence = $this->presence;
	}

	public function handle(WebSocketSession $session) : bool {
		return $session->handleIdentify($this);
	}

}