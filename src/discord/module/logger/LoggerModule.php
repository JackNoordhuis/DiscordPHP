<?php

/**
 * DiscordPHP – LoggerModule.php
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
 * Created on 25/9/17 at 12:52 AM
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

	/**
	 * Attempt to call the function for each log wrapper
	 *
	 * @param string $name
	 * @param mixed $arguments
	 */
	public function __call($name, $arguments) { // TODO: Remove this hack and create actual methods
		foreach($this->wrappers as $wrapper) {
			if(method_exists($wrapper, $name)) {
				$wrapper->$name(...$arguments);
			} else {
				throw new \BadMethodCallException("Method {$name} not found for " . (new \ReflectionObject($wrapper))->getShortName() . " logger wrapper!");
			}
		}
	}

}