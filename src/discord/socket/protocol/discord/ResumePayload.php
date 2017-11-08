<?php

/**
 * ResumePayload.php – DiscordPHP
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

use discord\socket\protocol\WebSocketSession;

class ResumePayload extends PayloadData {

	const OPCODE_ID = OpcodeInfo::OP_RESUME;

	/** @var string */
	public $token;

	/** @var string */
	public $sessionId;

	/** @var int */
	public $lastSequence;

	public function unpack() {
		$this->token = $this->payload->token;
		$this->sessionId = $this->payload->session_id;
		$this->lastSequence = $this->payload->seq;
	}

	public function pack() {
		$this->payload->token = $this->token;
		$this->payload->session_id = $this->sessionId;
		$this->payload->seq = $this->lastSequence;
	}

	public function handle(WebSocketSession $session) : bool {
		return $session->handleResume($this);
	}

}