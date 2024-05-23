<?php

namespace FluentConnect\Framework\Container;

use Exception;
use FluentConnect\Framework\Container\Contracts\Psr\NotFoundExceptionInterface;

class EntryNotFoundException extends Exception implements NotFoundExceptionInterface
{
    //
}
