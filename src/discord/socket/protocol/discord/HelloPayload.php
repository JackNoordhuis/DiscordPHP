<?php

/**
 * HelloPayload.php – DiscordPHP
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

class HelloPayload extends PayloadData {

	const OPCODE_ID = OpcodeInfo::OP_HELLO;

	/** @var int */
	public $heartbeatInterval;

	/** @var string[] */
	public $trace;

	public function unpack() {
		$this->heartbeatInterval = $this->payload->heartbeat_interval;
		$this->trace = $this->payload->_trace;
	}

	public function pack() {
		$this->payload->heartbeat_interval = $this->heartbeatInterval;
		$this->payload->_trace = $this->trace;
	}

}