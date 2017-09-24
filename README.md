DiscordPHP
====

An unofficial library to interact with the Discord gateway API (continuation of [DiscordPHP](https://github.com/teamreflex/DiscordPHP)).

## FAQ

1. Can I run DiscordPHP on a webserver (e.g. Apache, nginx)?
    - No, DiscordPHP will only run in CLI. If you want to have an interface for your bot you can integrate [react/http](https://github.com/ReactPHP/http) with your bot and run it through CLI.

## Getting Started

### Installing DiscordPHP

DiscordPHP is installed using [Composer](https://getcomposer.org). Make sure you have installed Composer and are used to how it operates. This project requires PHP 7.0.

This library has not been tested with HHVM.

1. Run `composer require jacknoordhuis/discord-php`. This will install the latest release.
2. Include the Composer autoload file at the top of your main file:
	- `include __DIR__ . "/vendor/autoload.php";`
3. Make a bot!

### Basic Example

```php
<?php

include __DIR__ . "/vendor/autoload.php";

use discord\DiscordClient;

$client = new DiscordClient([
	"token" => "bot-token",
]);

$client->on("ready", function ($client) {
	echo "Bot is ready!", PHP_EOL;

	// Listen for messages.
	$client->on("message", function ($message, $client) {
		echo "{$message->author->username}: {$message->content}",PHP_EOL;
	});
});

$client->run();
```

## Notes

- This library can use a lot of RAM and PHP may hit the memory limit. To increase the memory limit, use `ini_set('memory_limit', '200M')` to increase it to 200 mb. If you would like it to be unlimited, use `ini_set('memory_limit', '-1')`.

## Documentation

Raw documentation can be found in-line in the code and on the [DiscordPHP Class Reference](http://JackNoordhuis.github.io/DiscordPHP/).

## Contributing

We are open to contributions. However, please make sure you follow our coding standards (PSR-4 autoloading and custom styling).

## License

The content of this repo is licensed under the GNU Lesser General Public License (GPL) v3. A full copy of the license
is available [here](LICENSE).

>This program is free software: you can redistribute it and/or modify<br/>
>it under the terms of the GNU General Public License as published by<br/>
>the Free Software Foundation, either version 3 of the License, or<br/>
>(at your option) any later version.<br/>
>
>This program is distributed in the hope that it will be useful,<br/>
>but WITHOUT ANY WARRANTY; without even the implied warranty of<br/>
>MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the<br/>
>GNU General Public License for more details.<br/>
>
>You should have received a copy of the GNU General Public License<br/>
>along with this program.  If not, see http://www.gnu.org/licenses/