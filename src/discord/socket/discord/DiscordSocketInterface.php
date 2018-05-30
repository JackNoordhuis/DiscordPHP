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

use discord\DiscordClient;
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
	private $socketInterface;

	/** @var WebSocket */
	private $socket;

	/** @var int */
	private $id;

	/** @var ClientSocketSession */
	private $socketSession;

	/** @var bool */
	private $connected = false;

	public function __construct(SocketInterfaceHandler $socketClient) {
		$this->id = static::$socketInterfaceCount++;
		$this->socketInterface = $socketClient;

		$this->socketSession = new ClientSocketSession($socketClient->getClient(), $this);
	}

	/**
	 * @return DiscordClient
	 */
	public function getClient() : DiscordClient {
		return $this->socketInterface->getClient();
	}

	/**
	 * @return SocketInterfaceHandler
	 */
	public function getSocketInterface() : SocketInterfaceHandler {
		return $this->socketInterface;
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
	 * @return ClientSocketSession
	 */
	public function getSocketSession() : ClientSocketSession {
		return $this->socketSession;
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
			$this->socketInterface->getClient()->getLogger()->error("WebSocket error", ["e" => $error->getMessage()]);
		});
	}

	/**
	 * Push data to the socket
	 *
	 * @param PayloadData $payload
	 */
	public function putPayload(PayloadData $payload) {
		$payload->reset($this, false);
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
			$op->reset($this, false);
			$op->unpack();

			$this->socketSession->handlePayloadData($op);
		} else {
			$this->getSocketInterface()->getClient()->getLogger()->notice("Received payload with unknown opcode: " . json_encode($data));
		}
	}

	/**
	 * Start the socket connection
	 */
	public function connect() {
		if(!$this->connected) {
			$this->getSocketInterface()->getSocketFactory()->__invoke($this->getSocketInterface()->getGateway())->then(
				function(WebSocket $socket) {
					$this->connected = true;
					$this->start($socket);

					$this->getSocketInterface()->getClient()->getLogger()->debug("Connected to websocket on interface #{$this->id}");
				},
				function(\Throwable $e) {
					$this->getSocketInterface()->getClient()->getLogger()->error("Websocket error", ["e" => $e->getMessage()]);
				}
			);
		} else {
			$this->getSocketInterface()->getClient()->getLogger()->notice("Already connected to websocket!");
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

			$this->getSocketInterface()->getClient()->getLogger()->debug("Disconnected from websocket on interface #{$this->id}");
		} else {
			$this->getSocketInterface()->getClient()->getLogger()->notice("Already disconnected to websocket!");
		}
	}

}