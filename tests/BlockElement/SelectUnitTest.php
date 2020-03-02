<?php
namespace Slack\Tests\BlockElement;

use InvalidArgumentException;
use Maknz\Slack\BlockElement\Select;
use Maknz\Slack\BlockElement\Text;
use Slack\Tests\TestCase;

class SelectUnitTest extends TestCase
{
    public function testSelectFromArray()
    {
        $s = new Select([
            'placeholder' => 'Placeholder text',
            'action_id'   => 'Select action',
            'options'   => [[
                'text'     => 'Option 1',
                'value'    => 'option_1',
                'selected' => true,
            ], [
                'text'  => 'Option 2',
                'value' => 'option_2',
            ]],
        ]);

        $this->assertSame(Text::TYPE_PLAIN, $s->getPlaceholder()->getType());

        $options = $s->getOptions();

        $this->assertSame(2, count($options));

        $this->assertSame(Text::TYPE_PLAIN, $options[0]->getText()->getType());

        $this->assertSame('Option 1', $options[0]->getText()->getText());

        $this->assertSame('option_1', $options[0]->getValue());
    }

    public function testSelectWithOptionGroups()
    {
        $s = new Select([
            'placeholder' => 'Placeholder text',
            'action_id'   => 'Select action',
            'option_groups' => [[
                'label' => 'Group 1',
                'options'   => [[
                    'text'  => 'Option 1',
                    'value' => 'option_1',
                ], [
                    'text'  => 'Option 2',
                    'value' => 'option_2',
                ]],
            ], [
                'label' => 'Group 2',
                'options'   => [[
                    'text'     => 'Option 3',
                    'value'    => 'option_3',
                    'selected' => true,
                ], [
                    'text'  => 'Option 4',
                    'value' => 'option_4',
                ]],
            ]],
        ]);

        $this->assertSame([], $s->getOptions());

        $groups = $s->getOptionGroups();

        $this->assertSame(2, count($groups));

        $options = $groups[1]->getOptions();

        $this->assertSame('Option 3', $options[0]->getText()->getText());

        $this->assertSame('option_3', $options[0]->getValue());
    }

    public function testAddOptionThenOptionGroup()
    {
        $s = new Select([
            'placeholder' => 'Placeholder text',
            'action_id'   => 'Select action',
        ]);

        $s->addOption([
            'text' => 'Option 1',
            'value' => 'option_1',
        ]);

        $this->assertSame(1, count($s->getOptions()));

        $s->addOptionGroup([
            'label' => 'Group 1',
            'options' => [[
                'text' => 'Option 1',
                'value' => 'option_1',
            ]],
        ]);

        $this->assertSame(0, count($s->getOptions()));
    }

    public function testCreateWithTooManySelected()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only one option can be initially selected');

        $s = new Select([
            'placeholder' => 'Placeholder text',
            'action_id'   => 'Select action',
            'options'   => [[
                'text'     => 'Option 1',
                'value'    => 'option_1',
                'selected' => true,
            ], [
                'text'     => 'Option 2',
                'value'    => 'option_2',
                'selected' => true,
            ]],
        ]);
    }

    public function testCreateWithTooManySelectedInOptionGroups()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only one option can be initially selected');

        $s = new Select([
            'placeholder' => 'Placeholder text',
            'action_id'   => 'Select action',
            'option_groups' => [[
                'label' => 'Group 1',
                'options'   => [[
                    'text'  => 'Option 1',
                    'value' => 'option_1',
                ], [
                    'text'     => 'Option 2',
                    'value'    => 'option_2',
                    'selected' => true,
                ]],
            ], [
                'label' => 'Group 2',
                'options'   => [[
                    'text'     => 'Option 3',
                    'value'    => 'option_3',
                    'selected' => true,
                ], [
                    'text'  => 'Option 4',
                    'value' => 'option_4',
                ]],
            ]],
        ]);
    }

    public function testToArrayWithOptions()
    {
        $s = new Select([
            'placeholder' => 'Placeholder text',
            'action_id'   => 'Select action',
            'options'   => [[
                'text'  => 'Option 1',
                'value' => 'option_1',
            ], [
                'text'     => 'Option 2',
                'value'    => 'option_2',
                'selected' => true,
            ]],
            'confirm'   => [
                'title'   => 'Confirmation title',
                'text'    => 'Confirmation text',
                'confirm' => 'Confirm',
                'deny'    => 'Deny',
            ],
        ]);

        $out = [
            'type'        => 'static_select',
            'placeholder' => [
                'type' => Text::TYPE_PLAIN,
                'text' => 'Placeholder text',
                'emoji' => false,
            ],
            'action_id'   => 'Select action',
            'options'   => [[
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
            'initial_option' => [
                'text'  => [
                    'type' => 'plain_text',
                    'text' => 'Option 2',
                    'emoji' => false,
                ],
                'value' => 'option_2',
            ],
            'confirm'   => [
                'title'   => [
                    'type' => Text::TYPE_PLAIN,
                    'text' => 'Confirmation title',
                    'emoji' => false,
                ],
                'text'    => [
                    'type' => Text::TYPE_PLAIN,
                    'text' => 'Confirmation text',
                    'emoji' => false,
                ],
                'confirm' => [
                    'type' => Text::TYPE_PLAIN,
                    'text' => 'Confirm',
                    'emoji' => false,
                ],
                'deny'    => [
                    'type' => Text::TYPE_PLAIN,
                    'text' => 'Deny',
                    'emoji' => false,
                ],
            ],
        ];

        $this->assertEquals($out, $s->toArray());
    }

    public function testToArrayWithOptionGroups()
    {
        $s = new Select([
            'placeholder' => 'Placeholder text',
            'action_id'   => 'Select action',
            'option_groups' => [[
                'label' => 'Group 1',
                'options'   => [[
                    'text'  => 'Option 1',
                    'value' => 'option_1',
                ], [
                    'text'  => 'Option 2',
                    'value' => 'option_2',
                ]],
            ], [
                'label' => 'Group 2',
                'options'   => [[
                    'text'     => 'Option 3',
                    'value'    => 'option_3',
                    'selected' => true,
                ], [
                    'text'  => 'Option 4',
                    'value' => 'option_4',
                ]],
            ]],
            'confirm'   => [
                'title'   => 'Confirmation title',
                'text'    => 'Confirmation text',
                'confirm' => 'Confirm',
                'deny'    => 'Deny',
            ],
        ]);

        $out = [
            'type'        => 'static_select',
            'placeholder' => [
                'type' => Text::TYPE_PLAIN,
                'text' => 'Placeholder text',
                'emoji' => false,
            ],
            'action_id'   => 'Select action',
            'option_groups' => [[
                'label' => [
                    'type' => Text::TYPE_PLAIN,
                    'text' => 'Group 1',
                    'emoji' => false,
                ],
                'options'   => [[
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
                'label' => [
                    'type' => Text::TYPE_PLAIN,
                    'text' => 'Group 2',
                    'emoji' => false,
                ],
                'options'   => [[
                    'text'  => [
                        'type' => 'plain_text',
                        'text' => 'Option 3',
                        'emoji' => false,
                    ],
                    'value' => 'option_3',
                ], [
                    'text'  => [
                        'type' => 'plain_text',
                        'text' => 'Option 4',
                        'emoji' => false,
                    ],
                    'value' => 'option_4',
                ]],
            ]],
            'initial_option' => [
                'text'  => [
                    'type' => 'plain_text',
                    'text' => 'Option 3',
                    'emoji' => false,
                ],
                'value' => 'option_3',
            ],
            'confirm'   => [
                'title'   => [
                    'type' => Text::TYPE_PLAIN,
                    'text' => 'Confirmation title',
                    'emoji' => false,
                ],
                'text'    => [
                    'type' => Text::TYPE_PLAIN,
                    'text' => 'Confirmation text',
                    'emoji' => false,
                ],
                'confirm' => [
                    'type' => Text::TYPE_PLAIN,
                    'text' => 'Confirm',
                    'emoji' => false,
                ],
                'deny'    => [
                    'type' => Text::TYPE_PLAIN,
                    'text' => 'Deny',
                    'emoji' => false,
                ],
            ],
        ];

        $this->assertEquals($out, $s->toArray());
    }
}
