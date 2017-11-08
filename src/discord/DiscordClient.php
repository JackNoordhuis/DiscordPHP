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
use discord\socket\DiscordClientSocket;
use discord\socket\protocol\discord\OpcodePool;

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

	/** @var DiscordClientSocket */
	private $clientSocket;

	public function __construct(array $options = []) {
		if(php_sapi_name() !== "cli") {
			trigger_error('DiscordPHP will not run on a webserver. Please use PHP CLI to run a DiscordPHP bot.', E_USER_ERROR);
		}

		OpcodePool::init();

		$this->logger = new LoggerModule($this);
		$this->logger->addWrapper(new MonologWrapper($this->logger));
		$this->logger->info("Logger enabled!");

		$this->clientSocket = new DiscordClientSocket($this);
	}

	public function getLogger() : LoggerModule {
		return $this->logger;
	}

	public function getClientSocket() : DiscordClientSocket {
		return $this->clientSocket;
	}

	public function handleError() {

	}

}