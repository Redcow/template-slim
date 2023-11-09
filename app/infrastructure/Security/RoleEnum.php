<?php

namespace Infrastructure\Security;

enum RoleEnum
{
    case ANONYMOUS;
    case USER;
    case ADMIN;
    case CONSOLE;
    case API;
}
