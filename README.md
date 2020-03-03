# Slack for PHP

[![Build Status](https://travis-ci.org/php-slack/slack.svg?branch=master)](https://travis-ci.org/php-slack/slack)
[![Code Coverage](https://scrutinizer-ci.com/g/php-slack/slack/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/php-slack/slack/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/php-slack/slack/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/php-slack/slack/?branch=master)
[![StyleCI](https://styleci.io/repos/116497800/shield)](https://styleci.io/repos/116497800) |
[![Slack Welcome](https://img.shields.io/badge/slack-welcome-brightgreen.svg)](https://join.slack.com/t/php-slack/shared_invite/enQtMjk1OTExNDkzMjg1LTk5ODg3MGE1OThlYzZlM2U0N2I5Y2FhM2NiYmFlMjE1MDNiOWRjMjc1ZDIwNWZlNzBkZWQ2ZWM1NzdmMjM5YzQ)

A simple PHP package for sending messages to [Slack](https://slack.com)
with [incoming webhooks](https://my.slack.com/services/new/incoming-webhook),
focused on ease-of-use and elegant syntax.

**supports:** PHP `7.0`, `7.1`, `7.2`  
**require:** `guzzlehttp/guzzle` any of versions `~6.0|~5.0|~4.0`

> This is the fork of popular, great, but abandoned package [`maknz/slack`](https://github.com/maknz/slack)

# Quick Tour
 - `composer require alek13/slack`
 - [create an incoming webhook](https://my.slack.com/services/new/incoming-webhook) & copy `hook_url`
 - `$client = new Client('http://hook_url')`
 - `$client->to('#general')->send('Good morning');`

Done!

-------------------------------------------------
-------------------------------------------------
-------------------------------------------------

## Installation

You can install the package using the [Composer](https://getcomposer.org/) package manager by running in your project root:

```sh
composer require alek13/slack
```

## Incoming WebHook

Then [create an incoming webhook](https://my.slack.com/services/new/incoming-webhook) on your Slack account for the package to use.
You'll need the webhook URL to instantiate the client (or for the configuration file if using Laravel).

## Basic Usage

### Instantiate the client

```php
// Instantiate without defaults
$client = new Maknz\Slack\Client('https://hooks.slack.com/...');

// Instantiate with defaults, so all messages created
// will be sent from 'Cyril' and to the #accounting channel
// by default. Any names like @regan or #channel will also be linked.
// use response_type (in_channel | ephemeral) to denote whether the message will be visible
// to others in the channel.
$settings = [
	'username' => 'Cyril',
	'channel' => '#accounting',
	'reponse_type' => 'in_channel',
	'link_names' => true
];

$client = new Maknz\Slack\Client('https://hooks.slack.com/...', $settings);
```

#### Settings

The default settings are pretty good, but you may wish to set up default behaviour for your client to be used for all messages sent. **All settings are optional and you don't need to provide any**. Where not provided, we'll fallback to what is configured on the webhook integration, which are [managed at Slack](https://my.slack.com/apps/manage/custom-integrations), or our sensible defaults.

Field | Type | Description
----- | ---- | -----------
`channel` | string | The default channel that messages will be sent to
`username` | string | The default username for your bot
`icon` | string | The default icon that messages will be sent with, either `:emoji:` or a URL to an image
`response_type` | string | Whether to show the response in the channel to all members or privately ('ephemeral' | 'in_channel')
`link_names` | bool | Whether names like `@regan` or `#accounting` should be linked in the message (defaults to false)
`unfurl_links` | bool | Whether Slack should unfurl text-based URLs (defaults to false)
`unfurl_media` | bool | Whether Slack should unfurl media-based URLs, like tweets or Youtube videos (defaults to true)
`allow_markdown` | bool | Whether markdown should be parsed in messages, or left as plain text (defaults to true)
`markdown_in_attachments` | array | Which attachment fields should have markdown parsed (defaults to none)

### Sending messages

#### Sending a basic message ([preview](https://goo.gl/fY43nw))

```php
$client->send('Hello world!');
```

#### Sending a message to a non-default channel
```php
$client->to('#accounting')->send('Are we rich yet?');
```

#### Sending a message to a user
```php
$client->to('@regan')->send('Yo!');
```

#### Sending a message to a channel as a different bot name ([preview](https://goo.gl/xCeEfY))
```php
$client->from('Jake the Dog')->to('@FinnTheHuman')->send('Adventure time!');
```

#### Sending a message with a different icon ([preview](https://goo.gl/lff21l))
```php
// Either with a Slack emoji
$client->to('@regan')->withIcon(':ghost:')->send('Boo!');

// or a URL
$client->to('#accounting')->withIcon('http://example.com/accounting.png')->send('Some accounting notification');
```

#### Send an attachment ([preview](https://goo.gl/fp3iaY))

```php
$client->to('#operations')->attach([
	'fallback' => 'Server health: good',
	'text' => 'Server health: good',
	'color' => 'danger',
])->send('New alert from the monitoring system'); // no message, but can be provided if you'd like
```

#### Send an attachment with fields ([preview](https://goo.gl/264mhU))

```php
$client->to('#operations')->attach([
	'fallback' => 'Current server stats',
	'text' => 'Current server stats',
	'color' => 'danger',
	'fields' => [
		[
			'title' => 'CPU usage',
			'value' => '90%',
			'short' => true // whether the field is short enough to sit side-by-side other fields, defaults to false
		],
		[
			'title' => 'RAM usage',
			'value' => '2.5GB of 4GB',
			'short' => true
		]
	]
])->send('New alert from the monitoring system'); // no message, but can be provided if you'd like
```

#### Send an attachment with an author ([preview](https://goo.gl/CKd1zJ))

```php
$client->to('@regan')->attach([
	'fallback' => 'Keep up the great work! I really love how the app works.',
	'text' => 'Keep up the great work! I really love how the app works.',
	'author_name' => 'Jane Appleseed',
	'author_link' => 'https://yourapp.com/feedback/5874601',
	'author_icon' => 'https://static.pexels.com/photos/61120/pexels-photo-61120-large.jpeg'
])->send('New user feedback');
```

#### Using blocks ([Block Kit](https://api.slack.com/block-kit))

```php
$client->to('@regan')->withBlock([
    'type' => 'section',
    'text' => 'Do you love the app?'
])->withBlock([
    'type' => 'actions',
    'elements' => [[
        'type'      => 'button',
        'text'      => 'Love it',
        'style'     => 'primary',
        'action_id' => 'love',
    ], [
        'type'      => 'button',
        'text'      => 'Hate it',
        'style'     => 'danger',
        'action_id' => 'hate',
    ],
])->send('Notification fallback message');
```

## Advanced usage

### Markdown

By default, Markdown is enabled for message text, but disabled for attachment fields. This behaviour can be configured in settings, or on the fly:

#### Send a message enabling or disabling Markdown

```php
$client->to('#weird')->disableMarkdown()->send('Disable *markdown* just for this message');

$client->to('#general')->enableMarkdown()->send('Enable _markdown_ just for this message');
```

#### Send an attachment specifying which fields should have Markdown enabled

```php
$client->to('#operations')->attach([
	'fallback' => 'It is all broken, man',
	'text' => 'It is _all_ broken, man',
	'pretext' => 'From user: *JimBob*',
	'color' => 'danger',
	'mrkdwn_in' => ['pretext', 'text']
])->send('New alert from the monitoring system');
```

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

# Playground

There is the [`php-slack/playground`](https://github.com/php-slack/playground) simple console script to test messaging and to see how messages looks really.

# Questions

[![Slack Welcome](https://img.shields.io/badge/slack-welcome-brightgreen.svg)](https://join.slack.com/t/php-slack/shared_invite/enQtMjk1OTExNDkzMjg1LTk5ODg3MGE1OThlYzZlM2U0N2I5Y2FhM2NiYmFlMjE1MDNiOWRjMjc1ZDIwNWZlNzBkZWQ2ZWM1NzdmMjM5YzQ)

If you have any questions how to use or contribute,  
you are welcome in our [Slack Workspace](https://join.slack.com/t/php-slack/shared_invite/enQtMjk1OTExNDkzMjg1LTk5ODg3MGE1OThlYzZlM2U0N2I5Y2FhM2NiYmFlMjE1MDNiOWRjMjc1ZDIwNWZlNzBkZWQ2ZWM1NzdmMjM5YzQ). 

## Contributing

[![Slack Welcome](https://img.shields.io/badge/slack-welcome-brightgreen.svg)](https://join.slack.com/t/php-slack/shared_invite/enQtMjk1OTExNDkzMjg1LTk5ODg3MGE1OThlYzZlM2U0N2I5Y2FhM2NiYmFlMjE1MDNiOWRjMjc1ZDIwNWZlNzBkZWQ2ZWM1NzdmMjM5YzQ)

If you're having problems, spot a bug, or have a feature suggestion, please log and issue on Github.
If you'd like to have a crack yourself, fork the package and make a pull request.
Please include tests for any added or changed functionality.
If it's a bug, include a regression test.
