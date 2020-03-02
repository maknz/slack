<?php
namespace Slack\Tests\BlockElement;

use Maknz\Slack\BlockElement\MultiSelect;
use Maknz\Slack\BlockElement\Text;
use Slack\Tests\TestCase;

class MultiSelectUnitTest extends TestCase
{
    public function testMultiSelectFromArray()
    {
        $m = new MultiSelect([
            'placeholder' => 'Placeholder text',
            'action_id'   => 'MultiSelect action',
            'options'   => [[
                'text'     => 'Option 1',
                'value'    => 'option_1',
                'selected' => true,
            ], [
                'text'  => 'Option 2',
                'value' => 'option_2',
            ]],
        ]);

        $this->assertSame(Text::TYPE_PLAIN, $m->getPlaceholder()->getType());

        $options = $m->getOptions();

        $this->assertSame(2, count($options));

        $this->assertSame(Text::TYPE_PLAIN, $options[0]->getText()->getType());

        $this->assertSame('Option 1', $options[0]->getText()->getText());

        $this->assertSame('option_1', $options[0]->getValue());

        $initial = $m->getInitialOptions();

        $this->assertSame(1, count($initial));

        $this->assertSame('Option 1', $initial[0]->getText()->getText());
    }

    public function testMultiSelectWithOptionGroups()
    {
        $m = new MultiSelect([
            'placeholder' => 'Placeholder text',
            'action_id'   => 'MultiSelect action',
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

        $this->assertSame([], $m->getOptions());

        $groups = $m->getOptionGroups();

        $this->assertSame(2, count($groups));

        $options = $groups[1]->getOptions();

        $this->assertSame('Option 3', $options[0]->getText()->getText());

        $this->assertSame('option_3', $options[0]->getValue());

        $initial = $m->getInitialOptions();

        $this->assertSame(2, count($initial));

        $this->assertSame('Option 2', $initial[0]->getText()->getText());
    }

    public function testAddOptionThenOptionGroup()
    {
        $m = new MultiSelect([
            'placeholder' => 'Placeholder text',
            'action_id'   => 'MultiSelect action',
        ]);

        $m->addOption([
            'text' => 'Option 1',
            'value' => 'option_1',
        ]);

        $this->assertSame(1, count($m->getOptions()));

        $m->addOptionGroup([
            'label' => 'Group 1',
            'options' => [[
                'text' => 'Option 1',
                'value' => 'option_1',
            ]],
        ]);

        $this->assertSame(0, count($m->getOptions()));
    }

    public function testToArrayWithOptions()
    {
        $m = new MultiSelect([
            'placeholder' => 'Placeholder text',
            'action_id'   => 'MultiSelect action',
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
            'type'        => 'multi_static_select',
            'placeholder' => [
                'type' => Text::TYPE_PLAIN,
                'text' => 'Placeholder text',
                'emoji' => false,
            ],
            'action_id'   => 'MultiSelect action',
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
            'initial_options' => [[
                'text'  => [
                    'type' => 'plain_text',
                    'text' => 'Option 2',
                    'emoji' => false,
                ],
                'value' => 'option_2',
            ]],
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

        $this->assertEquals($out, $m->toArray());
    }

    public function testToArrayWithOptionGroups()
    {
        $m = new MultiSelect([
            'placeholder' => 'Placeholder text',
            'action_id'   => 'MultiSelect action',
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
            'confirm'   => [
                'title'   => 'Confirmation title',
                'text'    => 'Confirmation text',
                'confirm' => 'Confirm',
                'deny'    => 'Deny',
            ],
        ]);

        $out = [
            'type'        => 'multi_static_select',
            'placeholder' => [
                'type' => Text::TYPE_PLAIN,
                'text' => 'Placeholder text',
                'emoji' => false,
            ],
            'action_id'   => 'MultiSelect action',
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
            'initial_options' => [[
                'text'  => [
                    'type' => 'plain_text',
                    'text' => 'Option 2',
                    'emoji' => false,
                ],
                'value' => 'option_2',
            ], [
                'text'  => [
                    'type' => 'plain_text',
                    'text' => 'Option 3',
                    'emoji' => false,
                ],
                'value' => 'option_3',
            ]],
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

        $this->assertEquals($out, $m->toArray());
    }
}
