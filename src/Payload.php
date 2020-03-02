<?php
namespace Maknz\Slack;

abstract class Payload
{
    /**
     * Internal attribute to property map.
     *
     * @var array
     */
    protected static $availableAttributes = [];

    /**
     * Instantiate a new payload.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        foreach ($attributes as $attribute => $value) {
            $setter = self::getAttributeSetter($attribute);
            if ($setter !== null) {
                $this->$setter($value);
            }
        }
    }

    /**
     * Returns property setter method by given attribute name.
     *
     * @param string $attribute
     *
     * @return null|string
     */
    protected static function getAttributeSetter(string $attribute)
    {
        $property = self::getAttributeProperty($attribute);

        return $property !== null ? self::propertyToSetter($property) : null;
    }

    /**
     * Returns property name by given attribute name.
     *
     * @param string $attribute
     *
     * @return string|null
     */
    protected static function getAttributeProperty(string $attribute)
    {
        return static::$availableAttributes[$attribute] ?? null;
    }

    /**
     * Converts property name to setter method name.
     *
     * @param string $property
     *
     * @return string
     */
    protected static function propertyToSetter(string $property): string
    {
        $property = str_replace('_', ' ', $property);
        $property = ucwords($property);
        $property = str_replace(' ', '', $property);

        return 'set'.$property;
    }

    /**
     * Convert this payload to its array representation.
     *
     * @return array
     */
    abstract public function toArray();
}
