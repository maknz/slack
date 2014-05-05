# Slack

A simple Laravel 4 package for sending messages to [Slack](https://slack.com) with [incoming webhooks](https://my.slack.com/services/new/incoming-webhook).

## Installation

You can install the package using the [Composer](https://getcomposer.org/) package manager. Assuming you have Composer installed globally:

```sh
composer require maknz/slack:0.1.*
```

I might make BC breaks on unstable major releases (e.g. 0.2, 0.3) but not on the minor releases (e.g. 0.1.3). Once we're at 1.0, the API will stay consistent until a major release (e.g. 2.0).

### Service provider and alias

Next, add the `Maknz\Slack\SlackServiceProvider` service provider to the `providers` array in your `app/config.php` file.

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

This will add the boilerplate configuration to `app/config/packages/maknz/slack/config.php`. You need to add the URL to the webhook the package should use. If you haven't already created an incoming webhook for the package to use, [create one in your Slack backend](https://my.slack.com/services/new/incoming-webhook). The URL will be available under the "Instructions for creating Incoming WebHooks" panel. You can also configure the default channel and username in the config file. 

You can change the icon that will be used when editing the webhook in the Slack backend.

## Usage

```php
// Sending a message using the defaults in the configuration
Slack::send('Hello world!');

// Sending a message to a specific channel
Slack::send('Hello world!', '#accounting');

// Sending a message to a user
Slack::send('Hello world!', '@maknz');

// Sending a message to a channel, overriding the default username
Slack::send('Hello world!', '#general', 'Robot');

```
