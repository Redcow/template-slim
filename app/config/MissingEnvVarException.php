<?php

namespace Config;

use Error;
use Throwable;

class MissingEnvVarException extends Error
{
    public function __construct(string $envVariableName, ?Throwable $previous = null)
    {
        parent::__construct("missing env variable : {$envVariableName}", 418, $previous);
    }
}