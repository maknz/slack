<?php

use Maknz\Slack\Client;
use Maknz\Slack\Attachment;
use Illuminate\Queue\Capsule\Manager as Queue;

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
      'image_url' => 'http://fake.host/image.png',
      'thumb_url' => 'http://fake.host/image.png',
      'fields' => [],
      'title' => null,
      'title_link' => null,
      'author_name' => 'Joe Bloggs',
      'author_link' => 'http://fake.host/',
      'author_icon' => 'http://fake.host/image.png'
  ];

    $attachmentResult = [
      'fallback' => 'Some fallback text',
      'text' => 'Some text to appear in the attachment',
      'pretext' => null,
      'color' => 'bad',
      'mrkdwnIn' => ['pretext', 'text'],
      'imageUrl' => 'http://fake.host/image.png',
      'thumbUrl' => 'http://fake.host/image.png',
      'fields' => [],
      'title' => null,
      'titleLink' => null,
      'authorName' => 'Joe Bloggs',
      'authorLink' => 'http://fake.host/',
      'authorIcon' => 'http://fake.host/image.png'
    ];

    $expectedHttpData = [
      'username' => 'Test',
      'channel' => '#general',
      'text' => 'Message',
      'link_names' => 0,
      'unfurl_links' => false,
      'unfurl_media' => true,
      'mrkdwn' => true,
      'attachments' => [$attachmentResult]
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
      'image_url' => 'http://fake.host/image.png',
      'thumb_url' => 'http://fake.host/image.png',
      'title' => 'A title',
      'title_link' => 'http://fake.host/',
      'author_name' => 'Joe Bloggs',
      'author_link' => 'http://fake.host/',
      'author_icon' => 'http://fake.host/image.png',
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

    $attachmentResult = [
      'fallback' => 'Some fallback text',
      'text' => 'Some text to appear in the attachment',
      'pretext' => null,
      'color' => 'bad',
      'mrkdwnIn' => [],
      'imageUrl' => 'http://fake.host/image.png',
      'thumbUrl' => 'http://fake.host/image.png',
      'title' => 'A title',
      'titleLink' => 'http://fake.host/',
      'authorName' => 'Joe Bloggs',
      'authorLink' => 'http://fake.host/',
      'authorIcon' => 'http://fake.host/image.png',
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
      'link_names' => 0,
      'unfurl_links' => false,
      'unfurl_media' => true,
      'mrkdwn' => true,
      'attachments' => [$attachmentResult]
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
