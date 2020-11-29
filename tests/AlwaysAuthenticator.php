<?php

declare(strict_types=1);

namespace Bogosoft\Http\Auth\Tests;

use Bogosoft\Http\Auth\AuthenticationResult;
use Bogosoft\Http\Auth\IAuthenticator;
use Bogosoft\Identity\GenericIdentity;
use Bogosoft\Identity\GenericPrincipal;
use Psr\Http\Message\ServerRequestInterface as IRequest;

class AlwaysAuthenticator implements IAuthenticator
{
    private string $name;

    function __construct(string $name = 'Alice')
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    function authenticate(IRequest $request): AuthenticationResult
    {
        $identity = new GenericIdentity($this->name);

        $principal = new GenericPrincipal($identity);

        return new AuthenticationResult(true, $principal);
    }
}
