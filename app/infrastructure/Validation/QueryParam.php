<?php

namespace Infrastructure\Validation;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class QueryParam
{
    public function __construct(
        public readonly string $name,
        public readonly bool $required = false
    ) {}

    public function check(array $queryParams): ?array
    {
        $error = [];

        if($this->required && $this->isMissing($queryParams)) {
             $error = [
                 $this->name => [
                     'required' => "{$this->name} est requis"
                 ]
             ];
        }

        return $error;
    }

    protected function isMissing(array $queryParams): bool
    {
        return !array_key_exists($this->name, $queryParams);
    }
}