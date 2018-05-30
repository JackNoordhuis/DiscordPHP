<?php

/**
 * OpcodePool.php – DiscordPHP
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

class OpcodePool {

	/** @var \SplFixedArray<PayloadData> */
	protected static $pool = null;

	public static function init() {
		static::$pool = new \SplFixedArray(16);

		static::registerOpcode(new DispatchPayload());
		static::registerOpcode(new HeartbeatPayload());
		static::registerOpcode(new IdentifyPayload());
		static::registerOpcode(new StatusUpdatePayload());
		static::registerOpcode(new VoiceStatusUpdatePayload());
		static::registerOpcode(new ResumePayload());
		static::registerOpcode(new ReconnectPayload());
		static::registerOpcode(new RequestGuildMembersPayload());
		static::registerOpcode(new InvalidSessionPayload());
		static::registerOpcode(new HelloPayload());
		static::registerOpcode(new HeartbeatACKPayload());
	}

	/**
	 * @param PayloadData $opcode
	 */
	public static function registerOpcode(PayloadData $opcode) {
		static::$pool[$opcode->pid()] = clone $opcode;
	}

	/**
	 * @param int $pid
	 *
	 * @return mixed|null
	 */
	public static function getOpcodeById(int $pid) {
		return isset(static::$pool[$pid]) ? clone static::$pool[$pid] : null;
	}

	public static function getOpcode(\stdClass $payload) {
		$op = static::getOpcodeById($payload->op);

		if($op instanceof PayloadData) {
			return $op->setPayload($payload);
		}

		return null;
	}
}