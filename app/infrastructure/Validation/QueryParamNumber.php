<?php

namespace Infrastructure\Validation;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class QueryParamNumber extends QueryParam
{
    public function __construct(
        string $name,
        bool $required = false,
        public readonly ?int $min = null,
        public readonly ?int $max = null
    )
    {
        parent::__construct($name, $required);
    }

    public function check(array $queryParams): ?array
    {
        $errors = parent::check($queryParams);

        if($this->isMissing($queryParams)) return $errors;

        $queryParam = $queryParams[$this->name];

        $this->checkNumeric($queryParam, $errors);

        if($this->isNaN($queryParam)) return $errors;

        $this->checkMin($queryParam, $errors);

        $this->checkMax($queryParam, $errors);

        return $errors;
    }

    protected function isNaN(string $queryParam): bool
    {
        return !is_numeric($queryParam);
    }

    private function checkNumeric(string $queryParam, array &$errors): void
    {
        if(!$this->isNaN($queryParam)) {
            $errors[$this->name][] = [
                'NaN' => "{$this->name} doit être un nombre"
            ];
        }
    }

    private function checkMin(string $queryParam, array &$errors): void
    {
        $queryParamNumber = (double)$queryParam;
        if(!is_null($this->min) && $queryParamNumber < $this->min) {
            $errors[$this->name][] = [
                'min' => "{$this->name} doit être plus grand que {$this->min}"
            ];
        }
    }

    private function checkMax(string $queryParam, array &$errors): void
    {
        $queryParamNumber = (double)$queryParam;
        if(!is_null($this->max) && $queryParamNumber > $this->max) {
            $errors[$this->name][] = [
                'max' => "{$this->name} doit être plus petit que {$this->max}"
            ];
        }
    }
}