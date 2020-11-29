<?php

declare(strict_types=1);

namespace Bogosoft\Http\Auth\Tests;

use Bogosoft\Http\Auth\IPrincipalHandler;
use Bogosoft\Identity\IPrincipal;
use Psr\Http\Message\ServerRequestInterface as IRequest;

final class NullPrincipalHandler implements IPrincipalHandler
{
    /**
     * @inheritDoc
     */
    function handlePrincipal(IRequest $request, IPrincipal $principal): IRequest
    {
        return $request;
    }
}
