<?php

declare(strict_types=1);

namespace Bogosoft\Http\Auth;

use Bogosoft\Identity\IPrincipal;
use Psr\Http\Message\ServerRequestInterface as IRequest;

/**
 * An implementation of the {@see IPrincipalHandler} contract that delegates
 * principal handling to a {@see callable} object.
 *
 * @package Bogosoft\Http\Auth
 */
final class DelegatedPrincipalHandler implements IPrincipalHandler
{
    /** @var callable */
    private $delegate;

    /**
     * Create a new delegated principal handler.
     *
     * @param callable $delegate An invokable object to which principal
     *                           handling will be delegated.
     */
    function __construct(callable $delegate)
    {
        $this->delegate = $delegate;
    }

    /**
     * @inheritDoc
     */
    function handlePrincipal(IRequest $request, IPrincipal $principal): IRequest
    {
        return ($this->delegate)($request, $principal);
    }
}
