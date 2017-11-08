<?php

/**
 * MonologWrapper.php – DiscordPHP
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

namespace discord\module\logger\wrappers;

use discord\module\logger\LoggerModule;
use Monolog\Logger;

/**
 * Simple wrapper for the monolog logger
 */
class MonologWrapper extends LoggerWrapper {

	/** @var Logger */
	private $logger;

	public function __construct(LoggerModule $module) {
		$this->logger = new Logger("DiscordPHP");
		parent::__construct($module);
	}

	public function getLogger() : Logger {
		return $this->logger;
	}

	public function debug(string $message, ...$args) {
		$this->logger->debug($message, ...$args);
	}

	public function info(string $message, ...$args) {
		$this->logger->info($message, ...$args);
	}

	public function notice(string $message, ...$args) {
		$this->logger->notice($message, ...$args);
	}

	public function warning(string $message, ...$args) {
		$this->logger->warning($message, ...$args);
	}

	public function error(string $message, ...$args) {
		$this->logger->error($message, ...$args);
	}

	public function critical(string $message, ...$args) {
		$this->logger->critical($message, ...$args);
	}

	public function alert(string $message, ...$args) {
		$this->logger->alert($message, ...$args);
	}

	public function emergency(string $message, ...$args) {
		$this->logger->emergency($message, ...$args);
	}

}