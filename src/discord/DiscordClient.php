<?php

/**
 * DiscordPHP – DiscordClient.php
 *
 * Copyright (C) 2017 Jack Noordhuis
 *
 * This is private software, you cannot redistribute and/or modify it in any way
 * unless given explicit permission to do so. If you have not been given explicit
 * permission to view or modify this software you should take the appropriate actions
 * to remove this software from your device immediately.
 *
 * @author Jack Noordhuis
 *
 * Created on 25/9/17 at 12:49 AM
 *
 */

namespace discord;

use discord\module\logger\LoggerModule;
use discord\module\logger\wrappers\MonologWrapper;

/**
 * The discord client class that manages everything
 */
class DiscordClient {

	/**
	 * The discord gateway version to use
	 */
	const GATEWAY_VERSION = 6;

	/**
	 * The discord gateway encoding to use
	 */
	const GATEWAY_ENCODING = "json";

	/** @var LoggerModule */
	private $logger;

	public function __construct(array $options = []) {
		$this->logger = new LoggerModule($this);
		$this->logger->addWrapper(new MonologWrapper($this->logger));
		$this->logger->info("Logger enabled!");
	}

	public function getLogger() : LoggerModule {
		return $this->logger;
	}

}