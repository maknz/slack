# Change Log

## [1.10.1](https://github.com/alek13/slack/compare/1.10.0...1.10.1)
 - mark `Message::send` deprecated for #15
 - mark Laravel Provider as deprecated with link to new [separate package](https://github.com/alek13/slack-laravel)
 - add `Questions` section in readme
 - add `Quick Tour` section in readme

## [1.10.0](https://github.com/alek13/slack/compare/1.9.1...1.10.0)
 - Support of `url` field in `AttachmentAction` (by @rasmusdencker)

## [1.9.1](https://github.com/alek13/slack/compare/1.9.0...1.9.1)
 - improve & fix doc-block: right types + @throws added
 - fix Attachment::setIcon() return value

## [1.9.0](https://github.com/alek13/slack/compare/1.8.1...1.9.0)
 - Added optional footer attachments. Closes maknz/slack#87, closes #2 George* 6/15/16 12:08 AM
 - Php doc-blocks fixes. (Mesut Vatansever* 10/20/16 12:06 PM, Michal Vyšinský* 10/19/16 10:58 AM, Freek Van der Herten* 7/18/16 10:51 PM)

## [1.8.1](https://github.com/alek13/slack/compare/1.8.0...1.8.1)

 - Fix bug where message wouldn't get returned on send, closes maknz/slack#47 maknz* 6/26/16 8:06 AM
 - integrated Gemnasium; add dependency status badge Alexander Chibrikin 1/9/18 3:38 AM
 - integrated Scrutinizer-CI; change badge Alexander Chibrikin 1/9/18 3:36 AM
 - add slack welcome badge for community slack workspace Alexander Chibrikin 1/8/18 11:55 PM

## [1.8.0](https://github.com/alek13/slack/compare/1.7.0...1.8.0)
 - speed up builds: store composer cache Alexander Chibrikin 1/8/18 4:11 AM
 - add extra branch-alias for packagist Alexander Chibrikin 1/8/18 3:52 AM
 - bugfix: fail on build AttachmentAction without confirm (fixes #1, fixes maknz/slack#61) Alexander Chibrikin 1/7/18 5:33 PM
 - fix travis build; add builds for php 7.1, 7.2, nightly Alexander Chibrikin 1/6/18 8:48 PM
 - rename & publish new package on Packagist.org
 - add CHANGELOG.md Alexander Chibrikin 1/8/18 1:39 AM
 - Better Travis version testing maknz 6/25/16 5:43 AM
 - Drop PHP 5.4, throw an exception if JSON encoding fails maknz 5/28/16 8:29 AM
 - fixed code style Ion Bazan 6/22/16 12:04 PM
 - added Attachment Actions (buttons) with confirmations Ion Bazan 6/22/16 11:56 AM
 - StyleCI config, add badge to README maknz 5/28/16 7:36 AM
 - Code style fixes to abide by StyleCI laravel preset maknz 5/28/16 7:29 AM
 - Suggest nexylan/slack-bundle for Symfony support Regan McEntyre 3/8/16 10:45 PM
 - Update README with NexySlackBundle Regan McEntyre 3/8/16 10:41 PM
 - Fixed documentation color values Quentin McRee 1/20/16 4:37 AM
 - Removes unused Guzzle class reference from service provider Raj Abishek 12/21/15 8:01 AM
 - Fix Laravel 5 config publish instructions Regan McEntyre 6/15/15 10:59 AM
 - Add Scrutinizer badge Regan McEntyre 6/4/15 12:24 PM
