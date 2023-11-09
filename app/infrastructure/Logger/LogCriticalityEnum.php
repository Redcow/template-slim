<?php

namespace Infrastructure\Logger;

enum LogCriticalityEnum
{
    case SQL;
    case LOG;
    case WARNING;
    case ERROR;
}