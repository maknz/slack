<?php

use Maknz\Slack\Attachment;
use Maknz\Slack\AttachmentAction;
use Maknz\Slack\AttachmentField;

class AttachmentUnitTest extends PHPUnit_Framework_TestCase
{
    public function testAttachmentCreationFromArray()
    {
        $now = new DateTime;

        $a = new Attachment([
            'fallback' => 'Fallback',
            'text' => 'Text',
            'pretext' => 'Pretext',
            'color' => 'bad',
            'footer' => 'Footer',
            'footer_icon' => 'https://platform.slack-edge.com/img/default_application_icon.png',
            'timestamp' => $now,
            'mrkdwn_in' => ['pretext', 'text', 'fields'],
        ]);

        $this->assertEquals('Fallback', $a->getFallback());

        $this->assertEquals('Text', $a->getText());

        $this->assertEquals('Pretext', $a->getPretext());

        $this->assertEquals('bad', $a->getColor());

        $this->assertEquals([], $a->getFields());

        $this->assertEquals(['pretext', 'text', 'fields'], $a->getMarkdownFields());

        $this->assertEquals('Footer', $a->getFooter());

        $this->assertEquals('https://platform.slack-edge.com/img/default_application_icon.png', $a->getFooterIcon());

        $this->assertEquals($now, $a->getTimestamp());
    }

    public function testAttachmentCreationFromArrayWithFields()
    {
        $a = new Attachment([
            'fallback' => 'Fallback',
            'text' => 'Text',
            'fields' => [
              [
                'title' => 'Title 1',
                'value' => 'Value 1',
                'short' => false,
              ],
              [
                'title' => 'Title 2',
                'value' => 'Value 1',
                'short' => false,
              ],
            ],
        ]);

        $fields = $a->getFields();

        $this->assertSame('Title 1', $fields[0]->getTitle());

        $this->assertSame('Title 2', $fields[1]->getTitle());
    }

    public function testAttachmentToArray()
    {
        $now = new DateTime;

        $in = [
            'fallback' => 'Fallback',
            'text' => 'Text',
            'pretext' => 'Pretext',
            'color' => 'bad',
            'footer' => 'Footer',
            'footer_icon' => 'https://platform.slack-edge.com/img/default_application_icon.png',
            'timestamp' => $now,
            'mrkdwn_in' => ['pretext', 'text'],
            'image_url' => 'http://fake.host/image.png',
            'thumb_url' => 'http://fake.host/image.png',
            'title' => 'A title',
            'title_link' => 'http://fake.host/',
            'author_name' => 'Joe Bloggs',
            'author_link' => 'http://fake.host/',
            'author_icon' => 'http://fake.host/image.png',
            'callback_id' => 'comic_1234_xyz',
            'fields' => [
              [
                'title' => 'Title 1',
                'value' => 'Value 1',
                'short' => false,
              ],
              [
                'title' => 'Title 2',
                'value' => 'Value 1',
                'short' => false,
              ],
            ],
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

        // Sublte difference with timestamp
        $out = [
            'fallback' => 'Fallback',
            'text' => 'Text',
            'pretext' => 'Pretext',
            'color' => 'bad',
            'footer' => 'Footer',
            'footer_icon' => 'https://platform.slack-edge.com/img/default_application_icon.png',
            'ts' => $now->getTimestamp(),
            'mrkdwn_in' => ['pretext', 'text'],
            'image_url' => 'http://fake.host/image.png',
            'thumb_url' => 'http://fake.host/image.png',
            'title' => 'A title',
            'title_link' => 'http://fake.host/',
            'author_name' => 'Joe Bloggs',
            'author_link' => 'http://fake.host/',
            'author_icon' => 'http://fake.host/image.png',
            'fields' => [
              [
                'title' => 'Title 1',
                'value' => 'Value 1',
                'short' => false,
              ],
              [
                'title' => 'Title 2',
                'value' => 'Value 1',
                'short' => false,
              ],
            ],
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

        $a = new Attachment($in);

        $this->assertSame($out, $a->toArray());
    }

    public function testAddActionAsArray()
    {
        $a = new Attachment([
            'fallback' => 'Fallback',
            'text' => 'Text',
        ]);

        $a->addAction([
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
        ]);

        $actions = $a->getActions();

        $this->assertSame(1, count($actions));

        $this->assertSame('Text 1', $actions[0]->getText());
    }

    public function testAddActionAsObject()
    {
        $a = new Attachment([
            'fallback' => 'Fallback',
            'text' => 'Text',
        ]);

        $ac = new AttachmentAction([
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
        ]);

        $a->addAction($ac);

        $actions = $a->getActions();

        $this->assertSame(1, count($actions));

        $this->assertSame($ac, $actions[0]);
    }

    public function testAddFieldAsArray()
    {
        $a = new Attachment([
            'fallback' => 'Fallback',
            'text' => 'Text',
        ]);

        $a->addField([
            'title' => 'Title 1',
            'value' => 'Value 1',
            'short' => true,
        ]);

        $fields = $a->getFields();

        $this->assertSame(1, count($fields));

        $this->assertSame('Title 1', $fields[0]->getTitle());
    }

    public function testAddFieldAsObject()
    {
        $a = new Attachment([
          'fallback' => 'Fallback',
          'text' => 'Text',
        ]);

        $f = new AttachmentField([
          'title' => 'Title 1',
          'value' => 'Value 1',
          'short' => true,
        ]);

        $a->addField($f);

        $fields = $a->getFields();

        $this->assertSame(1, count($fields));

        $this->assertSame($f, $fields[0]);
    }

    public function testSetFields()
    {
        $a = new Attachment([
          'fallback' => 'Fallback',
          'text' => 'Text',
        ]);

        $a->addField([
          'title' => 'Title 1',
          'value' => 'Value 1',
          'short' => true,
        ])->addField([
          'title' => 'Title 2',
          'value' => 'Value 2',
          'short' => true,
        ]);

        $this->assertSame(2, count($a->getFields()));

        $a->setFields([]);

        $this->assertSame(0, count($a->getFields()));
    }
}
