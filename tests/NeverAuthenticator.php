<?php

declare(strict_types=1);

namespace Bogosoft\Http\Auth\Tests;

use Bogosoft\Http\Auth\AuthenticationResult;
use Bogosoft\Http\Auth\IAuthenticator;
use Psr\Http\Message\ServerRequestInterface as IRequest;

class NeverAuthenticator implements IAuthenticator
{
    /**
     * @inheritDoc
     */
    function authenticate(IRequest $request): AuthenticationResult
    {
        return new AuthenticationResult(false);
    }
}
