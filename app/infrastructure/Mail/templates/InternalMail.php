<?php

namespace Infrastructure\Mail\templates;

class InternalMail
{
    public static function UnknownError (\Throwable $exception): string
    {
        // todo notfound, fournir l'uri et les params

        $message = $exception->getMessage();
        $traces = $exception->getTraceAsString();

        return <<<HEREA
        <H1>ALERTE ROUTE</H1>
        <label style="background-color: red; color: antiquewhite">
            une exception non reconnue a été levée
        </label>
        <p>$message</p>
        <p>$traces</p>
    HEREA;
    }
}