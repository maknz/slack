# Slack

A simple Laravel 4 package for sending messages to [Slack](https://slack.com) with [incoming webhooks](https://my.slack.com/services/new/incoming-webhook).

## Installation

You can install the package using the [Composer](https://getcomposer.org/) package manager. Assuming you have Composer installed globally:

```sh
composer require maknz/slack:0.1.*
```

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

and add the URL to the webhook. If you haven't already created an incoming webhook for the package to use, [create one in your Slack backend](https://my.slack.com/services/new/incoming-webhook). The URL will be available under the "Instructions for creating Incoming WebHooks" panel.

## Usage

