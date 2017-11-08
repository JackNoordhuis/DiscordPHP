<?php

/**
 * LoggerModule.php – DiscordPHP
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

namespace discord\module\logger;

use discord\DiscordClient;
use discord\module\logger\wrappers\LoggerWrapper;

/**
 * Provides a simple wrapper for the logger
 */
class LoggerModule {

	/** @var DiscordClient */
	private $client;

	/** @var LoggerWrapper[] */
	private $wrappers = [];

	public function __construct(DiscordClient $client) {
		$this->client = $client;
	}

	public function getClient() : DiscordClient {
		return $this->client;
	}

	/**
	 * Add a logger wrapper to the list
	 *
	 * @param LoggerWrapper $wrapper
	 *
	 * @return bool
	 */
	public function addWrapper(LoggerWrapper $wrapper) : bool {
		$shortName = (new \ReflectionObject($wrapper))->getShortName();
		if(!isset($this->wrappers[$shortName])) {
			$this->wrappers[$shortName] = $wrapper;
			return true;
		}

		return false;
	}

	public function debug(string $message, ...$args) {
		foreach($this->wrappers as $wrapper) {
			if(method_exists($wrapper, __FUNCTION__)) {
				$wrapper->debug($message, $args);
			} else {
				throw new \BadMethodCallException("Method '" . __FUNCTION__ . "' not found for " . (new \ReflectionObject($wrapper))->getShortName() . "!");
			}
		}
	}

	public function info(string $message, ...$args) {
		foreach($this->wrappers as $wrapper) {
			if(method_exists($wrapper, __FUNCTION__)) {
				$wrapper->info($message, $args);
			} else {
				throw new \BadMethodCallException("Method '" . __FUNCTION__ . "' not found for " . (new \ReflectionObject($wrapper))->getShortName() . "!");
			}
		}
	}

	public function notice(string $message, ...$args) {
		foreach($this->wrappers as $wrapper) {
			if(method_exists($wrapper, __FUNCTION__)) {
				$wrapper->notice($message, $args);
			} else {
				throw new \BadMethodCallException("Method '" . __FUNCTION__ . "' not found for " . (new \ReflectionObject($wrapper))->getShortName() . "!");
			}
		}
	}

	public function warning(string $message, ...$args) {
		foreach($this->wrappers as $wrapper) {
			if(method_exists($wrapper, __FUNCTION__)) {
				$wrapper->warning($message, $args);
			} else {
				throw new \BadMethodCallException("Method '" . __FUNCTION__ . "' not found for " . (new \ReflectionObject($wrapper))->getShortName() . "!");
			}
		}
	}

	public function error(string $message, ...$args) {
		foreach($this->wrappers as $wrapper) {
			if(method_exists($wrapper, __FUNCTION__)) {
				$wrapper->error($message, $args);
			} else {
				throw new \BadMethodCallException("Method '" . __FUNCTION__ . "' not found for " . (new \ReflectionObject($wrapper))->getShortName() . "!");
			}
		}
	}

	public function critical(string $message, ...$args) {
		foreach($this->wrappers as $wrapper) {
			if(method_exists($wrapper, __FUNCTION__)) {
				$wrapper->critical($message, $args);
			} else {
				throw new \BadMethodCallException("Method '" . __FUNCTION__ . "' not found for " . (new \ReflectionObject($wrapper))->getShortName() . "!");
			}
		}
	}

	public function alert(string $message, ...$args) {
		foreach($this->wrappers as $wrapper) {
			if(method_exists($wrapper, __FUNCTION__)) {
				$wrapper->alert($message, $args);
			} else {
				throw new \BadMethodCallException("Method '" . __FUNCTION__ . "' not found for " . (new \ReflectionObject($wrapper))->getShortName() . "!");
			}
		}
	}

	public function emergency(string $message, ...$args) {
		foreach($this->wrappers as $wrapper) {
			if(method_exists($wrapper, __FUNCTION__)) {
				$wrapper->emergency($message, $args);
			} else {
				throw new \BadMethodCallException("Method '" . __FUNCTION__ . "' not found for " . (new \ReflectionObject($wrapper))->getShortName() . "!");
			}
		}
	}

}