<?php

/**
 * ClientSocketSession.php – DiscordPHP
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

namespace discord\socket\discord;

use discord\DiscordClient;
use discord\socket\discord\handler\DiscordSocketHandler;
use discord\socket\discord\handler\IdentifyDiscordSocketHandler;
use discord\socket\discord\protocol\HeartbeatPayload;
use discord\socket\discord\protocol\PayloadData;
use React\EventLoop\Timer\Timer;
use React\EventLoop\TimerInterface;

class ClientSocketSession {

	/** @var DiscordClient */
	private $client;

	/** @var DiscordSocketInterface */
	private $interface;

	/** @var int */
	private $heartbeatInterval = -1;

	/** @var TimerInterface|Timer|null */
	private $heartbeatTimer = null;

	/** @var TimerInterface|Timer|null */
	private $heartbeatAckTimer = null;

	/** @var int|null */
	private $sequence = null;

	/** @var DiscordSocketHandler */
	protected $handler;

	public function __construct(DiscordClient $client, DiscordSocketInterface $interface) {
		$this->client = $client;
		$this->interface = $interface;

		$this->handler = new IdentifyDiscordSocketHandler($this);
	}
	/**
	 * @return DiscordSocketHandler
	 */
	public function getHandler() : DiscordSocketHandler {
		return $this->handler;
	}

	/**
	 * @param DiscordSocketHandler $handler
	 */
	public function setHandler(DiscordSocketHandler $handler) : void {
		$this->handler = $handler;
	}

	public function handlePayloadData(PayloadData $payload) : void {
		if(!$payload->handle($this->handler)) {
			$this->client->getLogger()->debug("Unhandled " . $payload->getName() . " received: " . json_encode($payload->getPayload()));
		}
	}

	public function sendPayloadData(PayloadData $payload) : bool {
		$this->interface->putPayload($payload);
		return true;
	}

	/**
	 * @param int|null $sequence
	 */
	public function updateSequence(int $sequence) {
		$this->sequence = $sequence;
	}

	/**
	 * Update the heartbeat interval
	 *
	 * @param int $interval
	 */
	public function setHeartbeat(int $interval) {
		$this->heartbeatInterval = $interval;

		if($this->heartbeatTimer !== null) {
			$this->interface->getClient()->getLoop()->cancelTimer($this->heartbeatTimer);
		}

		$interval /= 1000;
		$this->heartbeatTimer = $this->interface->getClient()->getLoop()->addPeriodicTimer($interval, function() use ($interval) {
			$op = new HeartbeatPayload();
			$op->data = $this->sequence;

			$this->putPayload($op);

			$this->heartbeatAckTimer = $this->interface->getClient()->getLoop()->addTimer($interval, function() {
				if(!$this->interface->isConnected()) {
					return;
				}

				$this->interface->getClient()->getLogger()->warning("Didn't receive heartbeat ACK within heartbeat interval for interface #{$this->id}, closing connection...");
				$this->interface->disconnect(1001, "Didn't receive heartbeat ACK within heartbeat interval");
			});
		});
	}

	/**
	 * Cancel the heartbeat acknowledgement timer
	 */
	public function clearHeartbeatAckTimer() {
		$this->interface->getClient()->getLoop()->cancelTimer($this->heartbeatAckTimer);
	}

}