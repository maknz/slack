<?php

return [

  /*
  |-------------------------------------------------------------
  | Incoming webhook endpoint
  |-------------------------------------------------------------
  |
  | The endpoint which Slack generates when creating a
  | new incoming webhook. It will look something like
  | https://hooks.slack.com/services/XXXXXXXX/XXXXXXXX/XXXXXXXXXXXXXX
  |
  */

  'endpoint' => '',

  /*
  |-------------------------------------------------------------
  | Default channel
  |-------------------------------------------------------------
  |
  | The default channel we should post to. The channel can either be a
  | channel like #general, a private #group, or a @username. Set to
  | null to use the default set on the Slack webhook
  |
  */

  'channel' => '#general',

  /*
  |-------------------------------------------------------------
  | Default username
  |-------------------------------------------------------------
  |
  | The default username we should post as. Set to null to use
  | the default set on the Slack webhook
  |
  */

  'username' => 'Robot',

  /*
  |-------------------------------------------------------------
  | Default icon
  |-------------------------------------------------------------
  |
  | The default icon to use. This can either be a URL to an image or Slack
  | emoji like :ghost: or :heart_eyes:. Set to null to use the default
  | set on the Slack webhook
  |
  */

  'icon' => null,

  /*
  |-------------------------------------------------------------
  | Link names
  |-------------------------------------------------------------
  |
  | Whether names like @regan should be converted into links
  | by Slack
  |
  */

  'link_names' => false,

  /*
  |-------------------------------------------------------------
  | Unfurl links
  |-------------------------------------------------------------
  |
  | By default, Slack will unfurl links from well known media
  | sources like YouTube and Twitter. If you want Slack to unfurl
  | other URLs, enable this option
  |
  */

  'unfurl_links' => false,

];
