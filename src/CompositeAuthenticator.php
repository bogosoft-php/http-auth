<?php

declare(strict_types=1);

namespace Bogosoft\Http\Auth;

use Psr\Http\Message\ServerRequestInterface as IRequest;

/**
 * An implementation of the {@see IAuthenticator} contract that allows
 * multiple authenticators to behave as if they were a single authenticator.
 *
 * When an object of this class attempts to authenticate a given HTTP request,
 * the first authenticator to indicate success wins; no further authenticators
 * are invoked and the result is returned immediately.
 *
 * If no authenticators are able to authenticate a given HTTP request, the
 * last failed result will be returned or, if no failed result was received,
 * a new failed result will be created and returned.
 *
 * The order in which a collection of authenticators was provided to the class
 * is the order in which each authenticator will be invoked.
 *
 * This class cannot be inherited.
 *
 * @package Bogosoft\Http\Auth
 */
final class CompositeAuthenticator implements IAuthenticator
{
    /** @var IAuthenticator[] */
    private array $authenticators;

    /**
     * Create a new composite authenticator.
     *
     * @param IAuthenticator ...$authenticators Zero or more authenticators
     *                                          to combine into a single
     *                                          authenticator.
     */
    function __construct(IAuthenticator ...$authenticators)
    {
        $this->authenticators = $authenticators;
    }

    /**
     * @inheritDoc
     */
    function authenticate(IRequest $request): AuthenticationResult
    {
        /** @var AuthenticationResult $result */
        $result = null;

        foreach ($this->authenticators as $authenticator)
            if (($result = $authenticator->authenticate($request))->success)
                return $result;

        return $result ?? new AuthenticationResult(false);
    }
}
