<?php

/**
 * DiscordSocketInterface.php – DiscordPHP
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

use discord\socket\SocketInterfaceHandler;
use discord\socket\discord\protocol\HeartbeatPayload;
use discord\socket\discord\protocol\OpcodePool;
use discord\socket\discord\protocol\PayloadData;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\Message;
use React\EventLoop\Timer\Timer;
use React\EventLoop\TimerInterface;

/**
 * The class that manages a single socket connection
 */
class DiscordSocketInterface {

	/** @var int */
	static $socketInterfaceCount = 0;

	/** @var SocketInterfaceHandler */
	private $socketClient;

	/** @var WebSocket */
	private $socket;

	/** @var int */
	private $id;

	/** @var int */
	private $heartbeatInterval = -1;

	/** @var TimerInterface|Timer|null */
	private $heartbeatTimer = null;

	/** @var TimerInterface|Timer|null */
	private $heartbeatAckTimer = null;

	/** @var int|null */
	private $sequence = null;

	/** @var bool */
	private $connected = false;

	public function __construct(SocketInterfaceHandler $socketClient) {
		$this->id = static::$socketInterfaceCount++;
		$this->socketClient = $socketClient;
	}

	/**
	 * @return SocketInterfaceHandler
	 */
	public function getSocketClient() : SocketInterfaceHandler {
		return $this->socketClient;
	}

	/**
	 * @return WebSocket
	 */
	public function getSocket() : WebSocket {
		return $this->socket;
	}

	/**
	 * @return int
	 */
	public function getId() : int {
		return $this->id;
	}

	/**
	 * @return bool
	 */
	public function isConnected() {
		return $this->connected;
	}

	/**
	 * Start the socket interface and listen for incoming events
	 *
	 * @param WebSocket $socket
	 */
	public function start(WebSocket $socket) {
		$this->socket = $socket;

		$socket->on("message", function(Message $message) {
			$this->processPayload($message);
		});

		$socket->on("close", function(int $code, string $reason) {
			$this->disconnect();
		});

		$socket->on("error", function(\Throwable $error) {
			// Pawl pls
			if(strpos($error->getMessage(), "Tried to write to closed stream") !== false) {
				return;
			}
			$this->socketClient->getClient()->getLogger()->error("WebSocket error", ["e" => $error->getMessage()]);
		});
	}

	/**
	 * Push data to the socket
	 *
	 * @param PayloadData $payload
	 */
	public function putPayload(PayloadData $payload) {
		$payload->reset($this);
		$payload->pack();

		$this->getSocket()->send(json_encode($payload->getPayload()));
	}

	/**
	 * Process data from the socket
	 *
	 * @param Message $message
	 */
	public function processPayload(Message $message) {
		$data = $message->getPayload();
		if($message->isBinary()) {
			$data = zlib_decode($data);
		}

		$data = json_decode($data);

		$op = OpcodePool::getOpcode($data);

		if($op instanceof PayloadData) {
			$op->reset($this);
			$op->unpack();

			$this->socketClient->getClient()->getSocketSessionAdapter()->handlePayloadData($op);
		} else {
			$this->getSocketClient()->getClient()->getLogger()->notice("Received payload with unknown opcode: " . json_encode($data));
		}
	}

	/**
	 * Start the socket connection
	 */
	public function connect() {
		if(!$this->connected) {
			$this->getSocketClient()->getSocketFactory()->__invoke($this->getSocketClient()->getGateway())->then(
				function(WebSocket $socket) {
					$this->connected = true;
					$this->start($socket);

					$this->getSocketClient()->getClient()->getLogger()->debug("Connected to websocket on interface #{$this->id}");
				},
				function(\Throwable $e) {
					$this->getSocketClient()->getClient()->getLogger()->error("Websocket error", ["e" => $e->getMessage()]);
				}
			);
		} else {
			$this->getSocketClient()->getClient()->getLogger()->notice("Already connected to websocket!");
		}
	}

	/**
	 * Stop the socket connection
	 *
	 * @param int $code
	 * @param string $reason
	 */
	public function disconnect(int $code = 1000, string $reason = "Disconnecting..."){
		if($this->connected) {
			$this->connected = false;
			$this->socket->close($code, $reason);

			$this->getSocketClient()->getClient()->getLogger()->debug("Disconnected from websocket on interface #{$this->id}");
		} else {
			$this->getSocketClient()->getClient()->getLogger()->notice("Already disconnected to websocket!");
		}
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
			$this->socketClient->getClient()->getLoop()->cancelTimer($this->heartbeatTimer);
		}

		$interval /= 1000;
		$this->heartbeatTimer = $this->socketClient->getClient()->getLoop()->addPeriodicTimer($interval, function() use ($interval) {
			$op = new HeartbeatPayload();
			$op->data = $this->sequence;

			$this->putPayload($op);

			$this->heartbeatAckTimer = $this->socketClient->getClient()->getLoop()->addTimer($interval, function() {
				if(!$this->isConnected()) {
					return;
				}

				$this->socketClient->getClient()->getLogger()->warning("Didn't receive heartbeat ACK within heartbeat interval for interface #{$this->id}, closing connection...");
				$this->disconnect(1001, "Didn't receive heartbeat ACK within heartbeat interval");
			});
		});
	}

	/**
	 * Cancel the heartbeat acknowledgement timer
	 */
	public function clearHeartbeatAckTimer() {
		$this->socketClient->getClient()->getLoop()->cancelTimer($this->heartbeatAckTimer);
	}

}