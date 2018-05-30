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
use discord\socket\SocketInterfaceHandler;
use discord\socket\discord\protocol\OpcodePool;
use discord\socket\discord\ClientSocketSession;
use React\EventLoop\Factory;

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

	/** @var SocketInterfaceHandler */
	private $clientSocket;

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
	 * @return SocketInterfaceHandler
	 */
	public function getClientSocket() : SocketInterfaceHandler {
		return $this->clientSocket;
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
		$this->clientSocket = new SocketInterfaceHandler($this);
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

}