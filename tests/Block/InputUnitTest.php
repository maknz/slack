<?php
namespace Slack\Tests\Block;

use InvalidArgumentException;
use Maknz\Slack\Block\Input;
use Maknz\Slack\BlockElement\Text;
use Maknz\Slack\BlockElement\TextInput;
use Slack\Tests\TestCase;

class InputUnitTest extends TestCase
{
    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testInputFromArray()
    {
        $i = new Input([
            'hint' => 'Input hint',
            'element' => [
                'type' => 'plain_text_input',
                'action_id' => 'input_action',
            ],
        ]);

        $element = $i->getElement();

        $this->assertInstanceOf(TextInput::class, $element);

        $this->assertSame('Input hint', $i->getHint()->getText());
    }

    public function testInvalidElement()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/Block element .+ is not valid/');
        $i = new Input([
            'element' => [
                'type' => 'button',
            ],
        ]);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testInputToArray()
    {
        $i = new Input([
            'label' => 'Input label',
            'element' => [
                'type' => 'plain_text_input',
                'action_id' => 'input_action',
            ],
            'optional' => true,
        ]);

        $out = [
            'type' => 'input',
            'label' => [
                'type' => Text::TYPE_PLAIN,
                'text' => 'Input label',
                'emoji' => false,
            ],
            'element' => [
                'type' => 'plain_text_input',
                'action_id' => 'input_action',
            ],
            'optional' => true,
        ];

        $this->assertEquals($out, $i->toArray());
    }
}
