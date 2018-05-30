<?php

/**
 * DiscordClientSocket.php – DiscordPHP
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

namespace discord\socket;

use discord\DiscordClient;
use discord\socket\discord\DiscordSocketConnection;
use discord\util\Utils;
use GuzzleHttp\Client as HttpClient;
use Ratchet\Client\Connector;

/**
 * The class that manages a group of socket connections (useful for sharding)
 */
class DiscordSocketInterface {

	/**
	 * The discord gateway version to use
	 */
	const GATEWAY_VERSION = 6;

	/**
	 * The discord gateway encoding to use
	 */
	const GATEWAY_ENCODING = "json";

	/**
	 * The default discord web socket url
	 */
	const GATEWAY_URL = "wss://gateway.discord.gg";

	/** @var DiscordClient */
	private $client;

	/** @var HttpClient */
	protected $http;

	/** @var string */
	protected $gateway;

	/** @var Connector */
	private $socketFactory;

	/** @var DiscordSocketConnection[] */
	private $interfaces = [];

	/**
	 * DiscordClientSocket constructor.
	 *
	 * @param DiscordClient $client
	 */
	public function __construct(DiscordClient $client) {
		$this->client = $client;
		$this->http = new HttpClient();
		$this->socketFactory = new Connector($client->getLoop());
		$this->interfaces[] = new DiscordSocketConnection($this);

		$this->setGateway();
	}

	/**
	 * @return DiscordClient
	 */
	public function getClient() : DiscordClient {
		return $this->client;
	}

	/**
	 * @return Connector
	 */
	public function getSocketFactory() {
		return $this->socketFactory;
	}

	/**
	 * @return string
	 */
	public function getGateway() : string {
		return $this->gateway;
	}

	/**
	 * @param int $id
	 *
	 * @return DiscordSocketConnection
	 */
	public function getInterface(int $id) {
		return $this->interfaces[$id];
	}

	/**
	 * Attempt to resolve the web socket url for the specified version and encoding
	 */
	protected function setGateway() {
		try {
			$params = [
				"v" => self::GATEWAY_VERSION,
				"encoding" => self::GATEWAY_ENCODING,
			];

			$result = $this->http->request("GET", "https://discordapp.com/api/gateway", $params);

			$data = json_decode($result->getBody(), true);

			$this->gateway = Utils::buildParams($data["url"], $params);
		} catch(\Throwable $e) {
			$this->gateway = self::GATEWAY_URL;
			$this->getClient()->getLogger()->error("Error obtaining gateway", ["e" => $e->getMessage()]);
		}
	}

	/**
	 * Attempt to connect to the websockets
	 */
	public function connect() {
		$this->getClient()->getLogger()->info("Starting websocket connections...");

		foreach($this->interfaces as $interface) {
			$interface->connect();
		}
	}

	/**
	 * Disconnect from the websockets
	 *
	 * @param int $code
	 * @param string $reason
	 */
	public function disconnect(int $code = 1000, string $reason = "Disconnecting...") {
		$this->getClient()->getLogger()->info("Stopping websocket connections...");

		foreach($this->interfaces as $interface) {
			$interface->disconnect($code, $reason);
		}
	}

}