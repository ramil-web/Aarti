<?php

namespace App\Attributes;

use \Attribute as CorePhpAttribute;

#[CorePhpAttribute(CorePhpAttribute::TARGET_PROPERTY | CorePhpAttribute::IS_REPEATABLE)]
class Fillable
{
    public function __construct(public string $field)
    {
    }
}
