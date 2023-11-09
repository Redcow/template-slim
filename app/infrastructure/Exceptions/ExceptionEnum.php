<?php

namespace Infrastructure\Exceptions;

enum ExceptionEnum
{
    case SQl_CONNECTION;
    case SQL_QUERY;
    case CACHE_CONNECTION;
    case UNAUTHORIZED;
    case FORBIDDEN;
    case MAIL;
    case QUERY_PARAMS;
    case NOT_FOUND;

    case TCP_SOCKET_CONNECTION;
}
