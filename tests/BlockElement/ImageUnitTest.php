<?php
namespace Slack\Tests\BlockElement;

use Slack\Tests\TestCase;
use Maknz\Slack\BlockElement\Image;
use Maknz\Slack\BlockElement\Text;

class ImageUnitTest extends TestCase
{
    public function testImageFromArray()
    {
        $i = new Image([
            'image_url' => 'http://fake.host/image.png',
            'alt_text'  => 'Alt text',
            'title'     => 'Image title',
        ]);

        $this->assertSame('http://fake.host/image.png', $i->getUrl());

        $this->assertSame('Alt text', $i->getAltText());

        $this->assertSame(Text::TYPE_PLAIN, $i->getTitle()->getType());
    }

    public function testToArray()
    {
        $i = new Image([
            'image_url' => 'http://fake.host/image.png',
            'alt_text'  => 'Alt text',
            'title'     => 'Image title',
        ]);

        $out = [
            'type' => 'image',
            'image_url' => 'http://fake.host/image.png',
            'alt_text' => 'Alt text',
            'title' => [
                'type' => Text::TYPE_PLAIN,
                'text' => 'Image title',
                'emoji' => false,
            ],
        ];

        $this->assertEquals($out, $i->toArray());
    }
}
