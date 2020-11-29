<?php

declare(strict_types=1);

namespace Bogosoft\Http\Auth\Tests;

use Bogosoft\Http\Auth\AuthenticationResult;
use Bogosoft\Http\Auth\CompositeAuthenticator;
use Bogosoft\Http\Auth\DelegatedAuthenticator;
use Bogosoft\Http\Auth\IAuthenticator;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class CompositeAuthenticatorTest extends TestCase
{
    function testCanReturnFailedResult(): void
    {
        $authenticator = new CompositeAuthenticator(
            new NeverAuthenticator());

        $request = new ServerRequest('GET', '/');

        $result = $authenticator->authenticate($request);

        $this->assertFalse($result->success);
        $this->assertNull($result->principal);
    }

    function testCanReturnSuccessfulResult(): void
    {
        $expected = 'Bob';

        $authenticator = new CompositeAuthenticator(
            new AlwaysAuthenticator($expected));

        $request = new ServerRequest('GET', '/');

        $result = $authenticator->authenticate($request);

        $this->assertTrue($result->success);

        $this->assertEquals(
            $expected,
            $result->principal->getIdentity()->getName());
    }

    function testReturnsFailedResultWhenConstructedWithNoAuthenticators(): void
    {
        $authenticator = new CompositeAuthenticator();

        $request = new ServerRequest('GET', '/');

        $result = $authenticator->authenticate($request);

        $this->assertFalse($result->success);
        $this->assertNull($result->principal);
    }

    function testReturnsFirstSuccessfulAuthenticationAttempt(): void
    {
        /** @var IAuthenticator[] $authenticators */
        $authenticators = [];

        $j = -1;

        for ($i = 0; $i < 16; $i++)
        {
            $authenticators[] = new DelegatedAuthenticator(
                function(ServerRequestInterface $request) use ($i, &$j)
                    : AuthenticationResult
                {
                    $j = $i;

                    return new AuthenticationResult(true);
                }
            );
        }

        $authenticator = new CompositeAuthenticator(...$authenticators);

        $request = new ServerRequest('GET', '/');

        $result = $authenticator->authenticate($request);

        $this->assertTrue($result->success);

        $this->assertEquals(0, $j);
    }
}
