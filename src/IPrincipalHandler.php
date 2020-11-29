<?php

declare(strict_types=1);

namespace Bogosoft\Http\Auth;

use Bogosoft\Identity\IPrincipal;
use Psr\Http\Message\ServerRequestInterface as IRequest;

/**
 * Represents a strategy for handling a principal derived from an HTTP
 * request.
 *
 * @package Bogosoft\Http\Auth
 */
interface IPrincipalHandler
{
    /**
     * Handle a principal.
     *
     * @param  IRequest   $request   An HTTP request.
     * @param  IPrincipal $principal A security context (principal).
     * @return IRequest              A possibly altered HTTP request.
     */
    function handlePrincipal(
        IRequest $request,
        IPrincipal $principal
        )
        : IRequest;
}
