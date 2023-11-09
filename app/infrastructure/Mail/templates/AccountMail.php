<?php

namespace Infrastructure\Mail\templates;

class AccountMail
{
    static public function ActivateAccount(string $link): string
    {
        return <<<HEREA
        <H1>Bienvenue</H1>
        <label style="background-color: blue; color: antiquewhite">
            Pour activer votre compte cliquez
        </label>
        <a href="$link">ICI</a>
    HEREA;
    }
}