<?php

use Razorpay\Slack\Client;
use Razorpay\Slack\Attachment;
use Illuminate\Container\Container;
use Illuminate\Queue\Jobs\SyncJob as Jobs;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;

class ClientFunctionalTest extends PHPUnit_Framework_TestCase
{
    public function testPlainMessage()
    {
        $expectedHttpData = [
            'username' => 'Archer',
            'channel' => '@regan',
            'text' => 'Message',
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
            'link_names' => 0,
            'unfurl_links' => false,
            'unfurl_media' => true,
            'mrkdwn' => true,
            'attachments' => [$attachmentOutput],
        ];

        $this->assertEquals($expectedHttpData, $payload);
    }

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
            'link_names' => 0,
            'unfurl_links' => false,
            'unfurl_media' => true,
            'mrkdwn' => true,
            'attachments' => [$attachmentOutput],
        ];

        $this->assertEquals($expectedHttpData, $payload);
    }

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
            'link_names' => 0,
            'unfurl_links' => false,
            'unfurl_media' => true,
            'mrkdwn' => true,
            'attachments' => [$attachmentOutput],
        ];

        $this->assertEquals($expectedHttpData, $payload);
    }

    public function testBadEncodingThrowsException()
    {
        $client = $this->getNetworkStubbedClient();

        $this->setExpectedException(RuntimeException::class, 'JSON encoding error');

        // Force encoding to ISO-8859-1 so we know we're providing malformed
        // encoding to json_encode
        $client->send(mb_convert_encoding('æøå', 'ISO-8859-1', 'UTF-8'));
    }

    public function testSendMessageWithSlackDisabled()
    {
        $client = $this->getNetworkStubbedClient();

        $client->setSlackStatus(false);

        $client->send('Test Message');
    }

    public function testSendMessage()
    {
        $client = $this->getNetworkStubbedClient();

        $client->send('Test Message');
    }

    public function testSendMessageWithPostData()
    {
        $client = $this->getNetworkStubbedClient();

        $client->send('Test Message', ['test'=> 'data']);
    }

    public function testSendMessageWithMultiplArrayPostData()
    {
        $client = $this->getNetworkStubbedClient();

        $client->send('Test Message', ['test'=> ['key' =>'value']]);
    }

    public function testSendMessageWithInvalidRetry()
    {
        $client = $this->getNetworkStubbedClient();

        $client->send('Test Message', [], [], '', -10);
    }

    public function testSendMessageWithChannel()
    {
        $client = $this->getNetworkStubbedClient();

        $client->send('Test Message', [], ['channel'=> '#general']);
    }

    public function testSendMessageQueue()
    {
        $client = $this->getNetworkStubbedClient();

        $client->queue('Test Message');

        $client->setSlackStatus(false);

        $client->queue('Test Message');
    }

    public function testSendMessageOnQueue()
    {
        $client = $this->getNetworkStubbedClient();

        $client->onQueue('queue', 'Test Message');

        $client->setSlackStatus(false);

        $client->onQueue('queue', 'Test Message');
    }

    public function testInvalidAttachments()
    {
        $client = $this->getNetworkStubbedClient();

        $this->setExpectedException(InvalidArgumentException::class, 'Attachment must be an instance of Razorpay\Slack\Attachment or a keyed array');

        $client->attach('invalid attachment');
    }

    public function testUnchaughtExceptionInSend()
    {
        $client = $this->getNetworkStubbedClientAndThrowException();

        $client->send('Test Message');
    }

    public function testNoOfRetries()
    {
        $client = $this->getNetworkStubbedClientAndThrowException(11, 1);

        $client->send('Test Message');

        $this->guzzle->mockery_verify();
        $this->queue->mockery_verify();
    }

    public function testClientFire()
    {
        $job = new Jobs(new Container(), '', 'test-connection', 'test-queue');

        $client = $this->getNetworkStubbedClient();

        $data = [
            'metadata' => [
                'num_retries' => 1,
            ]
        ];

        $client->fire($job, $data);

        $data = [
            'metadata' => [
                'num_retries' => 10,
            ],
            'text' => 'Test Message'
        ];

        $client->fire($job, $data);
    }

    public function testClientFireWithException()
    {
        $job = new Jobs(new Container(), '', 'test-connection', 'test-queue');

        $client = $this->getNetworkStubbedClientAndThrowClientException();

        $data = [
            'metadata' => [
                'num_retries' => 1,
            ]
        ];

        $client->fire($job, $data);

        $data = [
            'metadata' => [
                'num_retries' => 10,
            ],
            'text' => 'Test Message'
        ];

        $client->fire($job, $data);
    }

    protected function getNetworkStubbedClient()
    {
        $guzzle = Mockery::mock('GuzzleHttp\Client');

        $guzzle->shouldReceive('post');

        $queue = Mockery::mock('Illuminate\queue');

        $queue->shouldReceive('push');

        return new Client('http://fake.endpoint', [], $queue, $guzzle);
    }

    protected function getNetworkStubbedClientAndThrowException($guzzleCount = 1, $queueCount = 1)
    {
        $this->guzzle = Mockery::mock('GuzzleHttp\Client');

        $this->guzzle->shouldReceive('post')->times($guzzleCount)->andThrow(new Exception());

        $this->queue = Mockery::mock('Illuminate\queue');

        $this->queue->shouldReceive('push')->times($queueCount);

        return new Client('http://fake.endpoint', [], $this->queue, $this->guzzle);
    }

    protected function getNetworkStubbedClientAndThrowClientException($guzzleCount = 1, $queueCount = 1)
    {
        $this->guzzle = Mockery::mock('GuzzleHttp\Client');

        $this->guzzle->shouldReceive('post')->times($guzzleCount)->andThrow(new ClientException('Invalid', new Request('post', 'http://fake.endpoint')));

        $this->queue = Mockery::mock('Illuminate\queue');

        $this->queue->shouldReceive('push')->times($queueCount);

        return new Client('http://fake.endpoint', [], $this->queue, $this->guzzle);
    }
}
