<?php
namespace Maknz\Slack;

interface Field
{
    /**
     * Instantiate a new field.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes);

    /**
     * Convert this field to its array representation.
     *
     * @return array
     */
    public function toArray();
}
