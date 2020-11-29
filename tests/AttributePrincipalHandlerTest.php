<?php

declare(strict_types=1);

namespace Bogosoft\Http\Auth\Tests;

use Bogosoft\Http\Auth\AttributePrincipalHandler;
use Bogosoft\Identity\GenericIdentity;
use Bogosoft\Identity\GenericPrincipal;
use Bogosoft\Identity\IPrincipal;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class AttributePrincipalHandlerTest extends TestCase
{
    function testAttachesPrincipalToDefaultNamedAttribute(): void
    {
        $handler = new AttributePrincipalHandler();

        $key = $handler->getAttributeName();

        $request = new ServerRequest('GET', '/');

        $this->assertNull($request->getAttribute($key, null));

        $expected = new GenericPrincipal(new GenericIdentity('Alice'));

        $request = $handler->handlePrincipal($request, $expected);

        /** @var IPrincipal $actual */
        $actual = $request->getAttribute($key, null);

        $this->assertNotNull($actual);

        $this->assertEquals(
            $expected->getIdentity()->getName(),
            $actual->getIdentity()->getName()
            );
    }

    function testAttachesPrincipalToExplicitlyNamedAttribute(): void
    {
        $handler = new AttributePrincipalHandler('principal');

        $key = $handler->getAttributeName();

        $request = new ServerRequest('GET', '/');

        $this->assertNull($request->getAttribute($key, null));

        $expected = new GenericPrincipal(new GenericIdentity('Alice'));

        $request = $handler->handlePrincipal($request, $expected);

        /** @var IPrincipal $actual */
        $actual = $request->getAttribute($key, null);

        $this->assertNotNull($actual);

        $this->assertEquals(
            $expected->getIdentity()->getName(),
            $actual->getIdentity()->getName()
        );
    }
}
