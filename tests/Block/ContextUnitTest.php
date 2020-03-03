<?php
namespace Slack\Tests\Block;

use InvalidArgumentException;
use Maknz\Slack\Block\Context;
use Maknz\Slack\BlockElement\Image;
use Maknz\Slack\BlockElement\Text;
use Slack\Tests\TestCase;

class ContextUnitTest extends TestCase
{
    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testContextFromArray()
    {
        $c = new Context([
            'elements' => [[
                'type' => 'image',
                'image_url' => 'http://fake.host/image.png',
                'alt_text' => 'Image alt',
            ], [
                'type' => Text::TYPE_PLAIN,
                'text' => 'Hi',
            ]],
        ]);

        $elements = $c->getElements();
        $this->assertSame(2, count($elements));

        $this->assertInstanceOf(Image::class, $elements[0]);

        $this->assertSame('Hi', $elements[1]->getText());
    }

    public function testInvalidElement()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/Block element .+ is not valid/');
        $c = new Context([
            'elements' => [[
                'type' => 'multi_static_select',
                'placeholder' => 'MultiSelect placeholder',
                'options' => [[
                    'text'  => 'Option 1',
                    'value' => 'option_1',
                ], [
                    'text'  => 'Option 2',
                    'value' => 'option_2',
                ]],
            ]],
        ]);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testContextToArray()
    {
        $c = new Context([
            'elements' => [[
                'type' => 'image',
                'image_url' => 'http://fake.host/image.png',
                'alt_text' => 'Image alt',
            ], [
                'type' => Text::TYPE_PLAIN,
                'text' => 'Hi',
            ]],
        ]);

        $out = [
            'type' => 'context',
            'elements' => [[
                'type' => 'image',
                'image_url' => 'http://fake.host/image.png',
                'alt_text' => 'Image alt',
            ], [
                'type' => Text::TYPE_PLAIN,
                'text' => 'Hi',
                'emoji' => false,
            ]],
        ];

        $this->assertEquals($out, $c->toArray());
    }
}
