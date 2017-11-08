<?php

/**
 * PayloadData.php – DiscordPHP
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

abstract class PayloadData {

	const OPCODE_ID = -1;

	/** @var \stdClass */
	protected $payload;

	public function __construct() {
		$this->payload = new \stdClass();
		$this->payload->d = new \stdClass();
	}

	public function setPayload(\stdClass $payload) {
		$this->payload = $payload;
		return $this;
	}

	public function getPayload() : \stdClass {
		return $this->payload;
	}

	public function pid() : int {
		return $this::OPCODE_ID;
	}

	public function getName() : string {
		return (new \ReflectionClass($this))->getShortName();
	}

	public function reset() {
		$this->payload->op = $this->pid();
	}

	/**
	 * Where all the incoming data will be read
	 */
	abstract public function unpack();

	/**
	 * Where all the outgoing data will be written
	 */
	abstract public function pack();

	/**
	 * @param WebSocketSession $session
	 *
	 * @return bool
	 */
	abstract public function handle(WebSocketSession $session) : bool;

}