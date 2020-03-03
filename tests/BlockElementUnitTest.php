<?php
namespace Slack\Tests;

use InvalidArgumentException;
use Maknz\Slack\BlockElement;
use Maknz\Slack\BlockElement\Button;

class BlockElementtUnitTest extends TestCase
{
    public function testFactoryWithArray()
    {
        $element = BlockElement::factory([
            'type' => 'button',
        ]);

        $this->assertInstanceOf(Button::class, $element);
    }

    public function testFactoryMissingType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create BlockElement without a type attribute');
        $element = BlockElement::factory([]);
    }

    public function testFactoryInvalidType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/Invalid Block type "invalid"/');
        $element = BlockElement::factory([
            'type' => 'invalid',
        ]);
    }
}
