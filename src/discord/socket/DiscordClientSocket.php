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
use discord\socket\protocol\WebSocketInterface;
use GuzzleHttp\Client as HttpClient;
use Ratchet\Client\Connector;
use Ratchet\Client\WebSocket;
use React\EventLoop\Factory;

class DiscordClientSocket {

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

	/** @var \React\EventLoop\ExtEventLoop|\React\EventLoop\LibEventLoop|\React\EventLoop\LibEvLoop|\React\EventLoop\StreamSelectLoop */
	private $loop;

	/** @var Connector */
	private $socketFactory;

	/** @var WebSocket */
	private $socket;

	/** @var WebSocketInterface */
	private $interface = [];

	public function __construct(DiscordClient $client) {
		$this->client = $client;
		$this->http = new HttpClient();
		$this->loop = Factory::create();
		$this->socketFactory = new Connector($this->loop);
		$this->interface = new WebSocketInterface($this);

		$this->setGateway();

		$this->connect();
	}

	public function getClient() : DiscordClient {
		return $this->client;
	}

	public function getSocket() : WebSocket {
		return $this->socket;
	}

	/**
	 * Attempt to resolve the web socket url for the specified version and encoding
	 */
	protected function setGateway() {
		try {
			$result = $this->http->request("GET", "https://discordapp.com/api/gateway", [
				"v" => self::GATEWAY_VERSION,
				"encoding" => self::GATEWAY_ENCODING,
			]);

			$data = json_decode($result->getBody(), true);

			$this->gateway = $data["url"];
		} catch(\Throwable $e) {
			$this->gateway = self::GATEWAY_URL;
			$this->getClient()->getLogger()->notice("Could not resolve gateway url, using default gateway.");
			$this->getClient()->getLogger()->error("WebSocket error", ["e" => $e->getMessage()]);
		}
	}

	/**
	 * Attempt to connect to the web socket
	 */
	protected function connect() {
		$this->socketFactory->__invoke($this->gateway)->then(
			function(WebSocket $socket) {
				$this->socket = $socket;

				$this->interface->start();

				$this->getClient()->getLogger()->info("WebSocket connection created successfully!");
			},
			function() {

			}
		);
	}

}