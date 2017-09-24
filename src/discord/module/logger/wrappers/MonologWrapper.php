<?php

/**
 * DiscordPHP – MonologWrapper.php
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
 * Created on 25/9/17 at 12:56 AM
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