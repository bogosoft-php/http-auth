<?php

declare(strict_types=1);

namespace Bogosoft\Http\Auth\Tests;

use Bogosoft\Http\Auth\AuthenticationResult;
use Bogosoft\Http\Auth\DelegatedAuthenticator;
use Bogosoft\Identity\GenericIdentity;
use Bogosoft\Identity\GenericPrincipal;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface as IRequest;

class DelegatedAuthenticatorTest extends TestCase
{
    function testCallDelegatesWithWhichItWasConstructed(): void
    {
        $expected = 'Marcus Tullius Cicero';

        $authenticate = function(IRequest $request)
            use ($expected): AuthenticationResult
        {
            $identity = new GenericIdentity($expected);

            $principal = new GenericPrincipal($identity);

            return new AuthenticationResult(true, $principal);
        };

        $authenticator = new DelegatedAuthenticator($authenticate);

        $request = new ServerRequest('POST', '/');

        $result = $authenticator->authenticate($request);

        $this->assertTrue($result->success);

        $this->assertEquals(
            $expected,
            $result->principal->getIdentity()->getName());
    }
}
