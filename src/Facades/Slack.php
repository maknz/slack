<?php namespace Maknz\Slack\Facades;

use Illuminate\Support\Facades\Facade;

class Slack extends Facade {

  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor() { return 'maknz.slack'; }

}
