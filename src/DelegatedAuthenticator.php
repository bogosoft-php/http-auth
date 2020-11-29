<?php

declare(strict_types=1);

namespace Bogosoft\Http\Auth;

use Psr\Http\Message\ServerRequestInterface as IRequest;

/**
 * An implementation of the {@see IAuthenticator} contract that delegates
 * HTTP request authentication to a {@see callable} object.
 *
 * This class cannot be inherited.
 *
 * @package Bogosoft\Http\Auth
 */
final class DelegatedAuthenticator implements IAuthenticator
{
    /** @var callable */
    private $delegate;

    /**
     * Create a new delegated authenticator.
     *
     * The given {@see callable} is expected to be of the form:
     *
     * - fn({@see IRequest}): {@see AuthenticationResult}
     *
     * @param callable $delegate An invokable object to which HTTP request
     *                           authentication will be delegated.
     */
    function __construct(callable $delegate)
    {
        $this->delegate = $delegate;
    }

    /**
     * @inheritDoc
     */
    function authenticate(IRequest $request): AuthenticationResult
    {
        return ($this->delegate)($request);
    }
}
