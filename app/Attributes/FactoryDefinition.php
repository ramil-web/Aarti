<?php

namespace App\Attributes;

use \Attribute as CorePhpAttribute;

#[CorePhpAttribute(CorePhpAttribute::TARGET_METHOD | CorePhpAttribute::IS_REPEATABLE)]
class FactoryDefinition
{
    public function __construct(public string $field, public string $type)
    {
    }
}
