<?php

/**
 * WebSocketInterface.php – DiscordPHP
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

namespace discord\socket\protocol;

use discord\socket\DiscordSocketInterface;
use discord\socket\protocol\discord\OpcodePool;
use discord\socket\protocol\discord\PayloadData;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\Message;

/**
 * The class that manages a single socket connection
 */
class DiscordSocketConnection {

	/** @var int */
	static $socketInterfaceCount = 0;

	/** @var DiscordSocketInterface */
	private $socketClient;

	/** @var WebSocket */
	private $socket;

	/** @var int */
	private $id;

	/** @var bool */
	private $connected = false;

	public function __construct(DiscordSocketInterface $socketClient) {
		$this->id = static::$socketInterfaceCount++;
		$this->socketClient = $socketClient;
	}

	/**
	 * @return DiscordSocketInterface
	 */
	public function getSocketClient() : DiscordSocketInterface {
		return $this->socketClient;
	}

	/**
	 * @return WebSocket
	 */
	public function getSocket() : WebSocket {
		return $this->socket;
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
		$payload->reset();
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
	 */
	public function disconnect(){
		if($this->connected) {
			$this->connected = false;
			$this->socket->close(1000, "Disconnecting");

			$this->getSocketClient()->getClient()->getLogger()->debug("Disconnected from websocket on interface #{$this->id}");
		} else {
			$this->getSocketClient()->getClient()->getLogger()->notice("Already disconnected to websocket!");
		}
	}

}