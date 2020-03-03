<?php
namespace Slack\Tests;

use DateTime;
use Maknz\Slack\Attachment;
use Maknz\Slack\Block;
use Maknz\Slack\Client;
use Mockery;
use RuntimeException;

class ClientFunctionalTest extends TestCase
{
    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \RuntimeException
     */
    public function testPlainMessage()
    {
        $expectedHttpData = [
            'username' => 'Archer',
            'channel' => '@regan',
            'text' => 'Message',
            'response_type' => 'ephemeral',
            'link_names' => 0,
            'unfurl_links' => false,
            'unfurl_media' => true,
            'mrkdwn' => true,
            'attachments' => [],
        ];

        $client = new Client('http://fake.endpoint');

        $message = $client->to('@regan')->from('Archer')->setText('Message');

        $payload = $client->preparePayload($message);

        $this->assertEquals($expectedHttpData, $payload);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \RuntimeException
     */
    public function testMessageWithAttachments()
    {
        $now = new DateTime;

        $attachmentInput = [
            'fallback' => 'Some fallback text',
            'text' => 'Some text to appear in the attachment',
            'pretext' => null,
            'color' => 'bad',
            'footer' => 'Footer',
            'footer_icon' => 'https://platform.slack-edge.com/img/default_application_icon.png',
            'timestamp' => $now,
            'mrkdwn_in' => ['pretext', 'text'],
            'image_url' => 'http://fake.host/image.png',
            'thumb_url' => 'http://fake.host/image.png',
            'fields' => [],
            'title' => null,
            'title_link' => null,
            'author_name' => 'Joe Bloggs',
            'author_link' => 'http://fake.host/',
            'author_icon' => 'http://fake.host/image.png',
            'actions' => [],
        ];

        $client = new Client('http://fake.endpoint', [
            'username' => 'Test',
            'channel' => '#general',
        ]);

        $message = $client->createMessage()->setText('Message');

        $attachment = new Attachment($attachmentInput);

        $message->attach($attachment);

        $payload = $client->preparePayload($message);

        // Subtle difference with timestamp
        $attachmentOutput = [
            'fallback' => 'Some fallback text',
            'text' => 'Some text to appear in the attachment',
            'pretext' => null,
            'color' => 'bad',
            'footer' => 'Footer',
            'footer_icon' => 'https://platform.slack-edge.com/img/default_application_icon.png',
            'ts' => $now->getTimestamp(),
            'mrkdwn_in' => ['pretext', 'text'],
            'image_url' => 'http://fake.host/image.png',
            'thumb_url' => 'http://fake.host/image.png',
            'fields' => [],
            'title' => null,
            'title_link' => null,
            'author_name' => 'Joe Bloggs',
            'author_link' => 'http://fake.host/',
            'author_icon' => 'http://fake.host/image.png',
            'actions' => [],
        ];

        $expectedHttpData = [
            'username' => 'Test',
            'channel' => '#general',
            'text' => 'Message',
            'response_type' => 'ephemeral',
            'link_names' => 0,
            'unfurl_links' => false,
            'unfurl_media' => true,
            'mrkdwn' => true,
            'attachments' => [$attachmentOutput],
        ];

        $this->assertEquals($expectedHttpData, $payload);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \RuntimeException
     */
    public function testMessageWithAttachmentsAndFields()
    {
        $now = new DateTime;

        $attachmentInput = [
            'fallback' => 'Some fallback text',
            'text' => 'Some text to appear in the attachment',
            'pretext' => null,
            'color' => 'bad',
            'footer' => 'Footer',
            'footer_icon' => 'https://platform.slack-edge.com/img/default_application_icon.png',
            'timestamp' => $now,
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
                    'short' => false,
                ],
                [
                    'title' => 'Field 2',
                    'value' => 'Value 2',
                    'short' => false,
                ],
            ],
            'actions' => [],
        ];

        $attachmentOutput = [
            'fallback' => 'Some fallback text',
            'text' => 'Some text to appear in the attachment',
            'pretext' => null,
            'color' => 'bad',
            'footer' => 'Footer',
            'footer_icon' => 'https://platform.slack-edge.com/img/default_application_icon.png',
            'ts' => $now->getTimestamp(),
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
                    'short' => false,
                ],
                [
                    'title' => 'Field 2',
                    'value' => 'Value 2',
                    'short' => false,
                ],
            ],
            'actions' => [],
        ];

        $client = new Client('http://fake.endpoint', [
            'username' => 'Test',
            'channel' => '#general',
        ]);

        $message = $client->createMessage()->setText('Message');

        $attachment = new Attachment($attachmentInput);

        $message->attach($attachment);

        $payload = $client->preparePayload($message);

        $expectedHttpData = [
            'username' => 'Test',
            'channel' => '#general',
            'text' => 'Message',
            'response_type' => 'ephemeral',
            'link_names' => 0,
            'unfurl_links' => false,
            'unfurl_media' => true,
            'mrkdwn' => true,
            'attachments' => [$attachmentOutput],
        ];

        $this->assertEquals($expectedHttpData, $payload);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \RuntimeException
     */
    public function testMessageWithAttachmentsAndActions()
    {
        $now = new DateTime;

        $attachmentInput = [
            'fallback' => 'Some fallback text',
            'text' => 'Some text to appear in the attachment',
            'pretext' => null,
            'color' => 'bad',
            'footer' => 'Footer',
            'footer_icon' => 'https://platform.slack-edge.com/img/default_application_icon.png',
            'timestamp' => $now,
            'mrkdwn_in' => [],
            'image_url' => 'http://fake.host/image.png',
            'thumb_url' => 'http://fake.host/image.png',
            'title' => 'A title',
            'title_link' => 'http://fake.host/',
            'author_name' => 'Joe Bloggs',
            'author_link' => 'http://fake.host/',
            'author_icon' => 'http://fake.host/image.png',
            'fields' => [],
            'actions' => [
                [
                    'name' => 'Name 1',
                    'text' => 'Text 1',
                    'style' => 'default',
                    'type' => 'button',
                    'value' => 'Value 1',
                    'url' => 'https://www.example.com/',
                    'confirm' => [
                        'title' => 'Title 1',
                        'text' => 'Text 1',
                        'ok_text' => 'OK Text 1',
                        'dismiss_text' => 'Dismiss Text 1',
                    ],
                ],
                [
                    'name' => 'Name 2',
                    'text' => 'Text 2',
                    'style' => 'default',
                    'type' => 'button',
                    'value' => 'Value 2',
                    'url' => null,
                    'confirm' => [
                        'title' => 'Title 2',
                        'text' => 'Text 2',
                        'ok_text' => 'OK Text 2',
                        'dismiss_text' => 'Dismiss Text 2',
                    ],
                ],
            ],
        ];

        $attachmentOutput = [
            'fallback' => 'Some fallback text',
            'text' => 'Some text to appear in the attachment',
            'pretext' => null,
            'color' => 'bad',
            'footer' => 'Footer',
            'footer_icon' => 'https://platform.slack-edge.com/img/default_application_icon.png',
            'ts' => $now->getTimestamp(),
            'mrkdwn_in' => [],
            'image_url' => 'http://fake.host/image.png',
            'thumb_url' => 'http://fake.host/image.png',
            'title' => 'A title',
            'title_link' => 'http://fake.host/',
            'author_name' => 'Joe Bloggs',
            'author_link' => 'http://fake.host/',
            'author_icon' => 'http://fake.host/image.png',
            'fields' => [],
            'actions' => [
                [
                    'name' => 'Name 1',
                    'text' => 'Text 1',
                    'style' => 'default',
                    'type' => 'button',
                    'value' => 'Value 1',
                    'url' => 'https://www.example.com/',
                    'confirm' => [
                        'title' => 'Title 1',
                        'text' => 'Text 1',
                        'ok_text' => 'OK Text 1',
                        'dismiss_text' => 'Dismiss Text 1',
                    ],
                ],
                [
                    'name' => 'Name 2',
                    'text' => 'Text 2',
                    'style' => 'default',
                    'type' => 'button',
                    'value' => 'Value 2',
                    'url' => null,
                    'confirm' => [
                        'title' => 'Title 2',
                        'text' => 'Text 2',
                        'ok_text' => 'OK Text 2',
                        'dismiss_text' => 'Dismiss Text 2',
                    ],
                ],
            ],
        ];

        $client = new Client('http://fake.endpoint', [
            'username' => 'Test',
            'channel' => '#general',
        ]);

        $message = $client->createMessage()->setText('Message');

        $attachment = new Attachment($attachmentInput);

        $message->attach($attachment);

        $payload = $client->preparePayload($message);

        $expectedHttpData = [
            'username' => 'Test',
            'channel' => '#general',
            'text' => 'Message',
            'response_type' => 'ephemeral',
            'link_names' => 0,
            'unfurl_links' => false,
            'unfurl_media' => true,
            'mrkdwn' => true,
            'attachments' => [$attachmentOutput],
        ];

        $this->assertEquals($expectedHttpData, $payload);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \RuntimeException
     */
    public function testMessageWithBlocks()
    {
        $client = new Client('http://fake.endpoint', [
            'username' => 'Test',
            'channel' => '#general',
        ]);

        $message = $client->createMessage()->setText('Fallback text');

        $block = Block::factory([
            'type' => 'actions',
            'elements' => [[
                'type'      => 'button',
                'text'      => 'Positive button',
                'style'     => 'primary',
                'action_id' => 'yes',
            ], [
                'type'      => 'button',
                'text'      => 'Negative button',
                'style'     => 'danger',
                'action_id' => 'no',
            ]],
        ]);

        $message->withBlock($block);

        $payload = $client->preparePayload($message);

        $blockOutput = [
            'type' => 'actions',
            'elements' => [[
                'type'  => 'button',
                'text' => [
                    'type' => 'plain_text',
                    'text'  => 'Positive button',
                    'emoji' => false,
                ],
                'style' => 'primary',
                'action_id' => 'yes',
            ], [
                'type'  => 'button',
                'text' => [
                    'type' => 'plain_text',
                    'text'  => 'Negative button',
                    'emoji' => false,
                ],
                'style' => 'danger',
                'action_id' => 'no',
            ]],
        ];

        $expectedHttpData = [
            'username' => 'Test',
            'channel' => '#general',
            'text' => 'Fallback text',
            'response_type' => 'ephemeral',
            'link_names' => 0,
            'unfurl_links' => false,
            'unfurl_media' => true,
            'mrkdwn' => true,
            'blocks' => [$blockOutput],
        ];

        $this->assertEquals($expectedHttpData, $payload);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function testBadEncodingThrowsException()
    {
        $client = $this->getNetworkStubbedClient();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('JSON encoding error');

        // Force encoding to ISO-8859-1 so we know we're providing malformed
        // encoding to json_encode
        $client->send(mb_convert_encoding('æøå', 'ISO-8859-1', 'UTF-8'));
    }

    /**
     * @return \Maknz\Slack\Client
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    protected function getNetworkStubbedClient()
    {
        /** @var \Mockery\Mock $guzzle */
        $guzzle = Mockery::mock('GuzzleHttp\Client');

        $guzzle->shouldReceive('post');

        return new Client('http://fake.endpoint', [], $guzzle);
    }
}
