<?php

declare(strict_types=1);

namespace Bogosoft\Http\Auth;

use Bogosoft\Identity\IPrincipal;
use Psr\Http\Message\ServerRequestInterface as IRequest;

/**
 * An implementation of the {@see IPrincipalHandler} class that attaches a
 * principal to an HTTP request via an attribute.
 *
 * This class cannot be inherited.
 *
 * @package Bogosoft\Http\Auth
 */
final class AttributePrincipalHandler implements IPrincipalHandler
{
    private string $attributeName;

    /**
     * Create a new attribute principal handler.
     *
     * @param string|null $attributeName The name of the attribute to which
     *                                   a principal will be attached. If
     *                                   omitted, the attribute name will be
     *                                   the fully qualified name of the
     *                                   {@see IPrincipal} interface.
     */
    function __construct(string $attributeName = null)
    {
        $this->attributeName = $attributeName ?? IPrincipal::class;
    }

    /**
     * Get the name of the attribute in an HTTP request to which a principal
     * will be attached.
     *
     * @return string The name of an attribute.
     */
    function getAttributeName(): string
    {
        return $this->attributeName;
    }

    /**
     * @inheritDoc
     */
    function handlePrincipal(IRequest $request, IPrincipal $principal): IRequest
    {
        return $request->withAttribute($this->attributeName, $principal);
    }
}
