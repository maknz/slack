<?php
namespace Slack\Tests;

use InvalidArgumentException;
use Maknz\Slack\AttachmentField;
use Maknz\Slack\BlockElement\Text;
use Maknz\Slack\FieldsTrait;

class FieldsTraitUnitTest extends TestCase
{
    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testAddFieldAsArray()
    {
        $obj = new FieldsMock;

        $obj->addField([
            'title' => 'Title 1',
            'value' => 'Value 1',
            'short' => true,
        ]);

        $fields = $obj->getFields();

        $this->assertSame(1, count($fields));

        $this->assertInstanceOf(AttachmentField::class, $fields[0]);

        $this->assertSame('Title 1', $fields[0]->getTitle());
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testAddFieldAsObject()
    {
        $obj = new FieldsMock;

        $f = new AttachmentField([
            'title' => 'Title 1',
            'value' => 'Value 1',
            'short' => true,
        ]);

        $obj->addField($f);

        $fields = $obj->getFields();

        $this->assertSame(1, count($fields));

        $this->assertSame($f, $fields[0]);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testAddFieldAsWrongObject()
    {
        $obj = new FieldsMock;

        $f = new Text([
            'type' => Text::TYPE_PLAIN,
            'text' => 'Text',
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The field must be an instance of '.AttachmentField::class.' or a keyed array');
        $obj->addField($f);
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testSetFields()
    {
        $obj = new FieldsMock;

        $obj->addField([
            'title' => 'Title 1',
            'value' => 'Value 1',
            'short' => true,
        ])->addField([
            'title' => 'Title 2',
            'value' => 'Value 2',
            'short' => true,
        ]);

        $this->assertSame(2, count($obj->getFields()));

        $obj->setFields([]);

        $this->assertSame(0, count($obj->getFields()));
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testFieldsAsArrays()
    {
        $obj = new FieldsMock;

        $f = new AttachmentField([
            'title' => 'Title 1',
            'value' => 'Value 1',
            'short' => true,
        ]);

        $obj->addField($f);

        $fields = $obj->mockGetFieldsAsArrays();

        $this->assertSame([[
            'title' => 'Title 1',
            'value' => 'Value 1',
            'short' => true,
        ]], $fields);
    }
}

class FieldsMock
{
    use FieldsTrait;
    protected static $fieldClass = AttachmentField::class;

    public function mockGetFieldsAsArrays()
    {
        return $this->getFieldsAsArrays();
    }
}
