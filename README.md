# Slack

[![Build Status](https://travis-ci.org/maknz/slack.svg?branch=master)](https://travis-ci.org/maknz/slack)

A simple PHP package for sending messages to [Slack](https://slack.com) with [incoming webhooks](https://my.slack.com/services/new/incoming-webhook), focussed on ease-of-use and elegant syntax. Includes Laravel 4 support out of the box.

## Requirements

* PHP 5.4 or greater

## Contributors

I will happily look at any pull requests or suggestions to improve the package and provide attribution for your contributions. Help share the improvements with everyone!

* [@maknz](https://github.com/maknz)
* [@willwashburn](https://github.com/willwashburn)

## Installation

You can install the package using the [Composer](https://getcomposer.org/) package manager. Assuming you have Composer installed globally:

```sh
composer require maknz/slack:0.2.*
```

## Laravel 4 

We include a Laravel 4 facade which provides a nicer syntax for using the client and allows for automatic configuration of username, channel and icon.

Firstly, add the `Maknz\Slack\SlackServiceProvider` service provider to the `providers` array in your `app/config.php` file.

```php
'providers' => array(
  ...
  'Maknz\Slack\SlackServiceProvider',
),
```

and then add the facade to your `aliases` array in your `app/config.php` file.

```php
'aliases' => array(
  ...
  'Slack' => 'Maknz\Slack\Facades\Slack',
),
```

### Configuration

Publish the configuration with

```php
php artisan config:publish maknz/slack
```

This will add the boilerplate configuration to `app/config/packages/maknz/slack/config.php`. You need to add the URL to the webhook the package should use. If you haven't already created an incoming webhook for the package to use, [create one in your Slack backend](https://my.slack.com/services/new/incoming-webhook). The URL will be available under the "Instructions for creating Incoming WebHooks" panel. You can also configure the default channel, username and icon in the config file.

You can change the default icon to be used in the Slack backend, or it can be changed to a URL or emoji client-side.

## Usage

These examples are showing the package when used with Laravel, but the methods are all the same regardless. See the section below on using the package outside of Laravel.

#### Sending a message using the defaults in the config

```php
Slack::send('Hello world!');
```

#### Sending a message to a different channel
```php
Slack::to('#accounting')->send('Are we rich yet?');
```

#### Sending a message to a user
```php
Slack::to('@regan')->send('Yo!');
```

#### Sending a message to a channel as a different username
```php
Slack::from('Jake the Dog')->to('@FinnTheHuman')->send('Adventure time!');
```

#### Sending a message with a different icon
```php
// Either with a Slack emoji
Slack::to('@regan')->withIcon(':ghost:')->send('Boo!');

// or a URL
Slack::to('#accounting')->withIcon('http://example.com/accounting.png')->send('Some accounting notification');
```

#### Send an attachment

```php
Slack::to('@regan')->attach([
	'fallback' => 'It is all broken, man', // Fallback text for plaintext clients, like IRC
	'text' => 'It is all broken, man', // The text for inside the attachment
	'pretext' => 'From user: JimBob' // Optional text to appear above the attachment and below the actual message
	'color' => 'bad', // Change the color of the attachment, default is 'good'
])->send('New alert from the monitoring system');
```

#### Send an attachment with fields

```php
Slack::to('#operations')->attach([
	'fallback' => 'It is all broken, man',
	'text' => 'It is all broken, man',
	'pretext' => 'From user: JimBob'
	'color' => 'bad',
	'fields' => [
		[
			'title' => 'Metric 1',
			'value' => 'Some value'
		],
		[
			'title' => 'Metric 2',
			'value' => 'Some value',
			'short' => true // whether the field is short enough to sit side-by-side other fields, defaults to false
		]
	]
])->send('New alert from the monitoring system');
```

### Chaining

All setter-like methods are chainable, so rather than having to type:

```php
$client->from('Username');

$client->to('@regan');

$client->send('A message');
```

The method calls can be chained together:

```php
$client->from('Username')->to('@regan')->send('A message');
```

### Usage outside of Laravel

All the same methods from the Laravel examples apply, the only difference is needing to instantiate a client manually.

You will need to `use` the Client at the top of your class:

```php
use Maknz\Slack\Client;
```

#### Send using the class defaults

This example sends 'Yo!' to #general as the user 'Robot', with the default webhook icon that Slack provide.

```php
$client = new Client('http://the.slack.endpoint');

$client->send('Yo!'); 
```

#### Instantiate the client with config/defaults

This example changes the default username, channel and icon from the class defaults. This is how the Laravel service provider works to set the defaults from the configuration file.

```php
$config = [
	'username' => 'The Website Bot',
	'channel' => '#operations',
	'icon' => ':heart_eyes:'
];

$client = new Client('http://the.slack.endpoint', $config);

$client->send('Test message');
```

### Changing the config on the fly

As with the Laravel examples, the config can be changed on the fly.

```php
$client = new Client('http://the.slack.endpoint', ...);

$client->from('A username')->to('#channel')->send('Hey');
```

## Advanced usage

When using attachments, the easiest way is to provide an array of data as shown in the examples, which is actually converted to an Attachment object under the hood. You can also attach an Attachment object to the client:

```php
$attachment = new Attachment([
	'fallback' => 'Some fallback text',
	'text' => 'The attachment text'
]);

$client->attach($attachment);
```

Each attachment field is also an object, an AttachmentField. They can be used as well instead of their data in array form:

```php
$attachment = new Attachment([
	'fallback' => 'Some fallback text',
	'text' => 'The attachment text',
	'fields' => [
		new AttachmentField([
			'title' => 'A title',
			'value' => 'A value',
			'short' => true
		])
	]
]);
```

You can also set the attachments and fields directly if you have a whole lot of them:

```php
$client = new Client(...);

$client->setAttachments($bigArrayOfAttachments);
```

```php
$attachment = new Attachment([]);

$attachment->setFields($bigArrayOfFields);
```
