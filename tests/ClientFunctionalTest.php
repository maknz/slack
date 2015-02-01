<?php

use Maknz\Slack\Client;
use Maknz\Slack\Attachment;

class ClientFunctionalTest extends PHPUnit_Framework_TestCase {

  public function testPlainMessage()
  {
    $expectedHttpData = [
      'username' => 'Archer',
      'channel' => '@regan',
      'text' => 'Message',
      'link_names' => false,
      'unfurl_links' => false,
      'unfurl_media' => true,
      'mrkdwn' => true,
      'attachments' => []
    ];

    $client = new Client('http://fake.endpoint');

    $message = $client->to('@regan')->from('Archer')->setText('Message');

    $payload = $client->preparePayload($message);

    $this->assertEquals($expectedHttpData, $payload);
  }

  public function testMessageWithAttachments()
  {
    $attachmentArray = [
      'fallback' => 'Some fallback text',
      'text' => 'Some text to appear in the attachment',
      'pretext' => null,
      'color' => 'bad',
      'mrkdwn_in' => ['pretext', 'text'],
      'fields' => []
    ];

    $expectedHttpData = [
      'username' => 'Test',
      'channel' => '#general',
      'text' => 'Message',
      'link_names' => false,
      'unfurl_links' => false,
      'unfurl_media' => true,
      'mrkdwn' => true,
      'attachments' => [$attachmentArray]
    ];

    $client = new Client('http://fake.endpoint', [
      'username' => 'Test',
      'channel' => '#general'
    ]);

    $message = $client->createMessage()->setText('Message');

    $attachment = new Attachment($attachmentArray);

    $message->attach($attachment);

    $payload = $client->preparePayload($message);

    $this->assertEquals($expectedHttpData, $payload);
  }

  public function testMessageWithAttachmentsAndFields()
  {
    $attachmentArray = [
      'fallback' => 'Some fallback text',
      'text' => 'Some text to appear in the attachment',
      'pretext' => null,
      'color' => 'bad',
      'mrkdwn_in' => [],
      'fields' => [
        [
          'title' => 'Field 1',
          'value' => 'Value 1',
          'short' => false
        ],
        [
          'title' => 'Field 2',
          'value' => 'Value 2',
          'short' => false
        ]
      ]
    ];

    $expectedHttpData = [
      'username' => 'Test',
      'channel' => '#general',
      'text' => 'Message',
      'link_names' => false,
      'unfurl_links' => false,
      'unfurl_media' => true,
      'mrkdwn' => true,
      'attachments' => [$attachmentArray]
    ];

    $client = new Client('http://fake.endpoint', [
      'username' => 'Test',
      'channel' => '#general'
    ]);

    $message = $client->createMessage()->setText('Message');

    $attachment = new Attachment($attachmentArray);

    $message->attach($attachment);

    $payload = $client->preparePayload($message);

    $this->assertEquals($expectedHttpData, $payload);
  }

}