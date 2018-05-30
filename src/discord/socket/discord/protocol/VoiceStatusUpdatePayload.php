<?php

/**
 * VoiceStatusUpdatePayload.php – DiscordPHP
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

use discord\socket\discord\handler\DiscordSocketHandler;

class VoiceStatusUpdatePayload extends PayloadData {

	const OPCODE_ID = OpcodeInfo::OP_VOICE_STATUS_UPDATE;

	/** @var string */
	public $guildId;

	/** @var string|null */
	public $channelId = null;

	/** @var bool */
	public $muted;

	/** @var bool */
	public $deafened;

	public function unpack() {
		$this->guildId = $this->payload->guild_id;
		$this->channelId = $this->payload->channel_id;
		$this->muted = $this->payload->self_mute;
		$this->deafened = $this->payload->self_deaf;
	}

	public function pack() {
		$this->payload->guild_id = $this->guildId;
		$this->payload->channel_id = $this->channelId;
		$this->payload->self_mute = $this->muted;
		$this->payload->self_deaf = $this->deafened;
	}

	public function handle(DiscordSocketHandler $handler) : bool {
		return $handler->handleVoiceStatusUpdate($this);
	}

}