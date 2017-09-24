<?php

/**
 * DiscordPHP – LoggerWrapperrapper.php
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
 * Created on 25/9/17 at 1:08 AM
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