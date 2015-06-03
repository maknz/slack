# Slack

[![Build Status](https://travis-ci.org/maknz/slack.svg?branch=master)](https://travis-ci.org/maknz/slack)

A simple PHP package for sending messages to [Slack](https://slack.com) with [incoming webhooks](https://my.slack.com/services/new/incoming-webhook), focussed on ease-of-use and elegant syntax. Includes Laravel 4 and 5 support out of the box.

## Requirements

* PHP 5.4 or greater

## Installation

You can install the package using the [Composer](https://getcomposer.org/) package manager. You can install it by running this command in your project root:

```sh
composer require maknz/slack
```

Then [create an incoming webhook](https://my.slack.com/services/new/incoming-webhook) on your Slack account for the package to use. You'll need the webhook URL to instantiate the client (or for the configuration file if using Laravel).

## Laravel

We include service providers and a facade for easy integration and a nice syntax for Laravel.

Firstly, add the `Maknz\Slack\SlackServiceProvider` provider to the providers array in `config/app.php` (or `app/config.php` for Laravel 4)

```php
'providers' => [
  ...
  'Maknz\Slack\SlackServiceProvider',
],
```

and then add the facade to your `aliases` array

```php
'aliases' => [
  ...
  'Slack' => 'Maknz\Slack\Facades\Slack',
],
```

### Configuration

Publish the configuration file with:

```sh
// Laravel 5, file will be at config/slack.php
php artisan vendor:publish

// Laravel 4, file will be at app/config/packages/maknz/slack/config.php
php artisan config:publish maknz/slack
```

Head into the file and configure the defaults you'd like the package to use. If `null` is set for any, the package will fall back on the default set on the webhook.

The configuration file is used to bypass the client instantiation process to make using the package easier. Therefore, you can skip the the *Instantiate the client* section below and dive right into using the package.

## Basic Usage

### Instantiate the client

```php
// Instantiate without defaults
$client = new Maknz\Slack\Client('http://your.slack.endpoint');

// Instantiate with defaults, so all messages created
// will be sent from 'Cyril' and to the #accounting channel
// by default. Any names like @regan or #channel will also be linked.
$settings = [
	'username' => 'Cyril',
	'channel' => '#accounting',
	'link_names' => true
];

$client = new Maknz\Slack\Client('http://your.slack.endpoint', $settings);
```

#### Settings

All settings are optional, but are a convenient way of specifying how the client should behave beyond the defaults.

* `channel`: the default channel that messages will be sent to
   * string
	* default: the setting on the webhook
* `username`: the default username that messages will be sent from
	* string
	* default: the setting on the webhook
* `icon`: the default icon messages will be sent with, either :emoji: or a URL to an image
   * string
   * default: the setting on the webhook
* `link_names`: whether names like @regan or #accounting should be linked
   * bool
   * default: `false`
* `unfurl_links`: whether Slack should unfurl text-based URLs
   * bool
   * default: `false`
* `unfurl_media`: whether Slack should unfurl media-based URLs
   * bool
   * default: `true`
* `allow_markdown`: whether Markdown should be parsed in messages
	* bool
	* default: `true`
* `markdown_in_attachments`: which attachment fields should have Markdown parsed
   * array
   * default: `[]`

### Sending messages

To send messages, you will call methods on your client instance, or use the `Slack` facade if you are using the package in Laravel.

#### Sending a basic message

```php
// With an instantiated client
$client->send('Hello world!');

// or the Laravel facade
Slack::send('Hello world!');
```

#### Sending a message to a non-default channel
```php
// With an instantiated client
$client->to('#accounting')->send('Are we rich yet?');

// or the Laravel facade
Slack::to('#accounting')->send('Are we rich yet?');
```

#### Sending a message to a user
```php
$client->to('@regan')->send('Yo!');
```

#### Sending a message to a channel as a different username
```php
$client->from('Jake the Dog')->to('@FinnTheHuman')->send('Adventure time!');
```

#### Sending a message with a different icon
```php
// Either with a Slack emoji
$client->to('@regan')->withIcon(':ghost:')->send('Boo!');

// or a URL
$client->to('#accounting')->withIcon('http://example.com/accounting.png')->send('Some accounting notification');
```

#### Send an attachment

```php
$client->to('@regan')->attach([
	'fallback' => 'It is all broken, man', // Fallback text for plaintext clients, like IRC
	'text' => 'It is all broken, man', // The text for inside the attachment
	'pretext' => 'From user: JimBob', // Optional text to appear above the attachment and below the actual message
	'color' => 'bad', // Change the color of the attachment, default is 'good'
])->send('New alert from the monitoring system');
```

#### Send an attachment with fields

```php
$client->to('#operations')->attach([
	'fallback' => 'It is all broken, man',
	'text' => 'It is all broken, man',
	'pretext' => 'From user: JimBob',
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

#### Send a message modifying Markdown parsing on the fly

```php
$client->to('#weird')->disableMarkdown()->send('Disable *markdown* just for this message');

$client->to('#general')->enableMarkdown()->send('Enable _markdown_ just for this message');
```

#### Send an attachment specifying Markdown parsing on the fly

```php
$client->to('#operations')->attach([
	'fallback' => 'It is all broken, man',
	'text' => 'It is _all_ broken, man',
	'pretext' => 'From user: *JimBob*',
	'color' => 'bad',
	'mrkdwn_in' => ['pretext', 'text']
])->send('New alert from the monitoring system');
```

#### Send an attachment with an author

```php
$client->to('@regan')->attach([
	'fallback' => 'Things are looking good',
	'text' => 'Things are looking good',
	'author_name' => 'Bobby Tables',
	'author_link' => 'http://flickr.com/bobby/',
	'author_url' => 'http://flickr.com/icons/bobby.jpg'
])->send('New alert from the monitoring system');
```

## Advanced usage

### Explicit message creation

For convenience, message objects are created implicitly by calling message methods on the client. We can however do this explicitly to avoid hitting the magic method.

```php
// Implicitly
$client->to('@regan')->send('I am sending this implicitly');

// Explicitly
$message = $client->createMessage();

$message->to('@regan')->setText('I am sending this explicitly');

$message->send();
```

### Attachments

When using attachments, the easiest way is to provide an array of data as shown in the examples, which is actually converted to an Attachment object under the hood. You can also attach an Attachment object to the message:

```php
$attachment = new Attachment([
	'fallback' => 'Some fallback text',
	'text' => 'The attachment text'
]);

// Explicitly create a message from the client
// rather than using the magic passthrough methods
$message = $client->createMessage();

$message->attach($attachment);

// Explicitly set the message text rather than
// implicitly through the send method
$message->setText('Hello world')->send();
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
// implicitly create a message and set the attachments
$client->setAttachments($bigArrayOfAttachments);

// or explicitly
$client->createMessage()->setAttachments($bigArrayOfAttachments);
```

```php
$attachment = new Attachment([]);

$attachment->setFields($bigArrayOfFields);
```

## Contributing

If you're having problems, spot a bug, or have a feature suggestion, please log and issue on Github. If you'd like to have a crack yourself, fork the package and make a pull request. Please include tests for any added or changed functionality. If it's a bug, include a regression test.
