<?php

namespace FluentConnect\Framework\Container\Contracts;

use Exception;
use FluentConnect\Framework\Container\Contracts\Psr\ContainerExceptionInterface;

class CircularDependencyException extends Exception implements ContainerExceptionInterface
{
    //
}
