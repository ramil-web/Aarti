<?php

namespace App\Attributes;

use \Attribute as CorePhpAttribute;

#[CorePhpAttribute(CorePhpAttribute::TARGET_METHOD)]
class Throws
{
    public function __construct(public string $exceptionClass)
    {
    }
}
