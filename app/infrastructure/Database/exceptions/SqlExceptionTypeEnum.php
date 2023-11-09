<?php

namespace Infrastructure\Database\exceptions;

enum SqlExceptionTypeEnum
{
    case DUPLICATE;

    case DEFAULT;
}
