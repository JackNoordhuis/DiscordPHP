<?php

/**
 * LoggerWrapper.php – DiscordPHP
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

abstract class LoggerWrapper {

	/** @var LoggerModule */
	private $module;

	public function __construct(LoggerModule $module) {
		$this->module = $module;
	}

	public function getModule() : LoggerModule {
		return $this->module;
	}

	/**
	 * Logs a message at debug level
	 *
	 * @param string $message
	 * @param mixed ...$args
	 */
	abstract public function debug(string $message, ...$args);

	/**
	 * Logs a message at info level
	 *
	 * @param string $message
	 * @param mixed ...$args
	 */
	abstract public function info(string $message, ...$args);

	/**
	 * Logs a message at notice level
	 *
	 * @param string $message
	 * @param mixed ...$args
	 */
	abstract public function notice(string $message, ...$args);

	/**
	 * Logs a message at warning level
	 *
	 * @param string $message
	 * @param mixed ...$args
	 */
	abstract public function warning(string $message, ...$args);

	/**
	 * Logs a message at error level
	 *
	 * @param string $message
	 * @param mixed ...$args
	 */
	abstract public function error(string $message, ...$args);

	/**
	 * Logs a message at critical level
	 *
	 * @param string $message
	 * @param mixed ...$args
	 */
	abstract public function critical(string $message, ...$args);

	/**
	 * Logs a message at alert level
	 *
	 * @param string $message
	 * @param mixed ...$args
	 */
	abstract public function alert(string $message, ...$args);

	/**
	 * Logs a message at emergency level
	 *
	 * @param string $message
	 * @param mixed ...$args
	 */
	abstract public function emergency(string $message, ...$args);

}