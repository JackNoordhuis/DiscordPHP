<?php

/**
 * WebSocketInterface.php – DiscordPHP
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

namespace discord\socket\protocol;

use discord\socket\DiscordClientSocket;
use discord\socket\protocol\discord\PayloadData;
use Ratchet\RFC6455\Messaging\Message;

class WebSocketInterface implements SocketInterface {

	/** @var DiscordClientSocket */
	private $socketClient;

	public function __construct(DiscordClientSocket $socketClient) {
		$this->socketClient = $socketClient;
	}

	public function getSocketClient() : DiscordClientSocket {
		return $this->socketClient;
	}

	public function start() {
		$this->socketClient->getSocket()->on("data", function(Message $message) {
			$data = $message->getPayload();

			if($message->isBinary()) {
				$data = zlib_decode($data);
			}

			$this->processPayload(json_decode($data));
		});

		$this->socketClient->getSocket()->on("close", function(int $code, string $reason) {
			$this->shutdown();
		});

		$this->socketClient->getSocket()->on("error", function(\Throwable $error) {
			// Pawl pls
			if(strpos($error->getMessage(), "Tried to write to closed stream") !== false) {
				return;
			}
			$this->socketClient->getClient()->getLogger()->error("WebSocket error", ["e" => $error->getMessage()]);
		});
	}

	public function putPayload(PayloadData $payload) {
		$this->socketClient->getSocket()->send(json_encode($payload->getPayload()));
	}

	public function processPayload(\stdClass $data) {

	}

	public function getName() : string {
		return "Discord Web Socket Interface";
	}

	public function shutdown() {

	}

}