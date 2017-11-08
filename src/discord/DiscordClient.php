<?php

/**
 * DiscordClient.php – DiscordPHP
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

namespace discord;

use discord\module\logger\LoggerModule;
use discord\module\logger\wrappers\MonologWrapper;
use discord\socket\DiscordSocketInterface;
use discord\socket\protocol\discord\HeartbeatPayload;
use discord\socket\protocol\discord\OpcodePool;
use discord\socket\protocol\ClientWebSocketSessionAdapter;
use React\EventLoop\Factory;
use React\EventLoop\Timer\Timer;
use React\EventLoop\Timer\TimerInterface;

/**
 * The discord client class that manages everything
 */
class DiscordClient {

	/**
	 * The client library version
	 *
	 * @var string
	 */
	const CLIENT_VERSION = "0.0.1_ALPHA-dev#2";

	/**
	 * The client library name
	 *
	 * @var string
	 */
	const CLIENT_NAME = "Discord PHP";

	/** @var LoggerModule */
	private $logger;

	/** @var \React\EventLoop\ExtEventLoop|\React\EventLoop\LibEventLoop|\React\EventLoop\LibEvLoop|\React\EventLoop\StreamSelectLoop */
	private $loop;

	/** @var DiscordSocketInterface */
	private $clientSocket;

	/** @var ClientWebSocketSessionAdapter */
	private $socketSessionAdapter;

	/** @var int */
	private $heartbeatInterval = -1;

	/** @var TimerInterface|Timer|null */
	private $heartbeatTimer = null;

	/** @var TimerInterface|Timer|null */
	private $heartbeatAckTimer = null;

	/** @var int|null */
	private $sequence = null;

	/**
	 * @return LoggerModule
	 */
	public function getLogger() : LoggerModule {
		return $this->logger;
	}

	/**
	 * @return \React\EventLoop\ExtEventLoop|\React\EventLoop\LibEventLoop|\React\EventLoop\LibEvLoop|\React\EventLoop\StreamSelectLoop
	 */
	public function getLoop() {
		return $this->loop;
	}

	/**
	 * @return DiscordSocketInterface
	 */
	public function getClientSocket() : DiscordSocketInterface {
		return $this->clientSocket;
	}

	/**
	 * @return ClientWebSocketSessionAdapter
	 */
	public function getSocketSessionAdapter() : ClientWebSocketSessionAdapter {
		return $this->socketSessionAdapter;
	}

	/**
	 * DiscordClient constructor.
	 *
	 * @param array $options
	 */
	public function __construct(array $options = []) {
		if(php_sapi_name() !== "cli") {
			trigger_error("DiscordPHP will not run on a web server. Please run PHP in CLI mode to use DiscordPHP.", E_USER_ERROR);
		}

		OpcodePool::init();

		$this->logger = new LoggerModule($this);
		$this->logger->addWrapper(new MonologWrapper($this->logger));
		$this->logger->info("Logger enabled!");

		$this->loop = Factory::create();
		$this->clientSocket = new DiscordSocketInterface($this);
		$this->socketSessionAdapter = new ClientWebSocketSessionAdapter($this);
	}

	/**
	 * Start all the sockets after everything is loaded
	 */
	public function start() {
		$this->clientSocket->connect();

		$this->loop->run(); // start the loop to allow the sockets to tick/cycle
	}

	/**
	 * Stop all the socket connections safely
	 */
	public function stop() {
		$this->loop->stop();
		$this->clientSocket->disconnect();
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
			$this->heartbeatTimer->cancel();
		}

		$interval /= 1000;
		$this->heartbeatTimer = $this->loop->addPeriodicTimer($interval, function() {
			$op = new HeartbeatPayload();
			$op->data = $this->sequence;

			$this->getClientSocket()->getInterface()->putPayload($op);

			$this->heartbeatAckTimer = $this->loop->addTimer($this->heartbeatInterval / 1000, function() {
				if(!$this->clientSocket->getInterface()->isConnected()) {
					return;
				}

				$this->logger->warning("Didn't receive heartbeat ACK within heartbeat interval, closing connection...");
				$this->clientSocket->getInterface()->disconnect();
			});
		});
	}

	/**
	 * Cancel the heartbeat acknowledgement timer
	 */
	public function clearHeartbeatAckTimer() {
		$this->heartbeatAckTimer->cancel();
	}

}