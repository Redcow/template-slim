<?php

namespace Infrastructure\Validation;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class QueryParamString extends QueryParam
{
    public function __construct(
        string $name,
        bool $required = false,
        public readonly ?int $minLength = null,
        public readonly ?int $maxLength = null,
        public readonly bool $specialCharacter = false
    )
    {
        parent::__construct(
            $name,
            $required
        );
    }

    public function check(array $queryParams): ?array
    {
        $errors = parent::check($queryParams);

        if($this->isMissing($queryParams)) return $errors;

        $queryParam = $queryParams[$this->name];

        $this->checkMaxLength($queryParam, $errors);

        $this->checkMinLength($queryParam, $errors);

        $this->checkSpecialCharacter($queryParam, $errors);

        return $errors;
    }

    private function checkMaxLength(string $queryParam, array &$errors): void
    {
        if(!is_null($this->maxLength) && strlen($queryParam) > $this->maxLength) {
            $errors[$this->name][] = [
                'maxLength' => "{$this->name} ne doit pas dépasser {$this->maxLength} caractères"
            ];
        }
    }

    private function checkMinLength(string $queryParam, array &$errors): void
    {
        if(!is_null($this->minLength) && strlen($queryParam) < $this->minLength) {
            $errors[$this->name][] = [
                'minLength' => "{$this->name} doit faire au moins {$this->minLength} caractères"
            ];
        }
    }

    private function checkSpecialCharacter(string $queryParam, array &$errors): void
    {
        if(!$this->specialCharacter) return;

        $regex = preg_match('/[@_!#$%^&*()<>?\/|}{~:]/', $queryParam);

        if(!$regex) {
            $errors[$this->name][] = [
                'specialCharacter' => "{$this->name} doit comporter un caractère spécial"
            ];
        }
    }
}