<?php

declare(strict_types=1);

namespace Bogosoft\Http\Auth;

use Bogosoft\Identity\IPrincipal;

/**
 * Represents the result of attempting to authenticate an entity.
 *
 * @package Bogosoft\Http\Auth
 */
class AuthenticationResult
{
    /**
     * @var IPrincipal|null Get or set the security context (principal)
     *                      resulting from an authentication attempt.
     */
    public ?IPrincipal $principal = null;

    /**
     * @var bool Get or set a value indicating whether or not an
     *           authentication attempt succeeded.
     */
    public bool $success = false;

    /**
     * Create a new authentication result object.
     *
     * @param bool            $success   A value indicating whether or not an
     *                                   authentication attempt succeeded.
     * @param IPrincipal|null $principal A security context (principal)
     *                                   resulting from an authentication
     *                                   attempt.
     */
    function __construct(bool $success, IPrincipal $principal = null)
    {
        $this->principal = $principal;
        $this->success   = $success;
    }
}
