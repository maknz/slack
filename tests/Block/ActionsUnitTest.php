<?php
namespace Slack\Tests\Block;

use InvalidArgumentException;
use Maknz\Slack\Block\Actions;
use Maknz\Slack\BlockElement\Select;
use Maknz\Slack\BlockElement\Text;
use Slack\Tests\TestCase;

class ActionsUnitTest extends TestCase
{
    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testSectionFromArray()
    {
        $a = new Actions([
            'elements' => [[
                'type' => 'static_select',
                'placeholder' => 'Select placeholder',
                'options' => [[
                    'text'  => 'Option 1',
                    'value' => 'option_1',
                ], [
                    'text'  => 'Option 2',
                    'value' => 'option_2',
                ]],
            ], [
                'type' => 'button',
                'text' => 'OK',
            ]],
        ]);

        $elements = $a->getElements();
        $this->assertSame(2, count($elements));

        $this->assertInstanceOf(Select::class, $elements[0]);

        $this->assertSame('OK', $elements[1]->getText()->getText());
    }

    public function testInvalidElement()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/Block element .+ is not valid/');
        $a = new Actions([
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
    public function testActionsToArray()
    {
        $a = new Actions([
            'elements' => [[
                'type' => 'static_select',
                'action_id' => 'select_action',
                'placeholder' => 'Select placeholder',
                'options' => [[
                    'text'  => 'Option 1',
                    'value' => 'option_1',
                ], [
                    'text'  => 'Option 2',
                    'value' => 'option_2',
                ]],
            ], [
                'type' => 'button',
                'action_id' => 'button_action',
                'text' => 'OK',
            ]],
        ]);

        $out = [
            'type' => 'actions',
            'elements' => [[
                'type' => 'static_select',
                'action_id' => 'select_action',
                'placeholder' => [
                    'type' => Text::TYPE_PLAIN,
                    'text' => 'Select placeholder',
                    'emoji' => false,
                ],
                'options' => [[
                    'text'  => [
                        'type' => 'plain_text',
                        'text' => 'Option 1',
                        'emoji' => false,
                    ],
                    'value' => 'option_1',
                ], [
                    'text'  => [
                        'type' => 'plain_text',
                        'text' => 'Option 2',
                        'emoji' => false,
                    ],
                    'value' => 'option_2',
                ]],
            ], [
                'type' => 'button',
                'action_id' => 'button_action',
                'text' => [
                    'type' => Text::TYPE_PLAIN,
                    'text' => 'OK',
                    'emoji' => false,
                ],
            ]],
        ];

        $this->assertEquals($out, $a->toArray());
    }
}
