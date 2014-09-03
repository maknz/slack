<?php

return [
  
  /*
  |--------------------------------------------------------------------------
  | Incoming webhook endpoint
  |--------------------------------------------------------------------------
  |
  | The endpoint which Slack generates when creating a new incoming webhook.
  | It will look something like https://acc.slack.com/services/hooks/incoming
  | -webhook?token=abcdefghijklmnop.
  |
  */

  'endpoint' => '',

  /*
  |--------------------------------------------------------------------------
  | Default channel
  |--------------------------------------------------------------------------
  |
  | The default channel we should post to if none is specified in the send
  | call. The channel can either be a channel like #general, a private #group,
  | or a @username.
  |
  */

  'default_channel' => '#general',

  /*
  |--------------------------------------------------------------------------
  | Default username
  |--------------------------------------------------------------------------
  |
  | The default username we should post as if none is specified.
  |
  */
  
  'default_username' => 'PHP Bot',
  
  /*
  |--------------------------------------------------------------------------
  | Default icon url
  |--------------------------------------------------------------------------
  |
  | The default icon_url we should post as if none is specified.
  |
  */

  'default_icon_url' => false,

];
