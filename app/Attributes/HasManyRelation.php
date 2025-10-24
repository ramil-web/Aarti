<?php

namespace App\Attributes;

use \Attribute as CorePhpAttribute;

#[CorePhpAttribute(CorePhpAttribute::TARGET_METHOD)]
class HasManyRelation
{
    public function __construct(public string $relatedModel, public ?string $foreignKey = null)
    {
    }
}
