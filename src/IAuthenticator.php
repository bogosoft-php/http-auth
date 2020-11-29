<?php

declare(strict_types=1);

namespace Bogosoft\Http\Auth;

use Bogosoft\Identity\IPrincipal;
use Psr\Http\Message\ServerRequestInterface as IRequest;

/**
 * Represents a strategy for authenticating an HTTP request.
 *
 * @package Bogosoft\Http\Auth
 */
interface IAuthenticator
{
    /**
     * Authenticate a given HTTP request.
     *
     * @param  IRequest             $request An HTTP request.
     * @return AuthenticationResult          The result of authenticating the
     *                                       given HTTP request.
     */
    function authenticate(IRequest $request): AuthenticationResult;
}
