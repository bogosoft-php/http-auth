<?php

declare(strict_types=1);

namespace Bogosoft\Http\Auth\Tests;

use Bogosoft\Http\Auth\DelegatedPrincipalHandler;
use Bogosoft\Identity\GenericIdentity;
use Bogosoft\Identity\GenericPrincipal;
use Bogosoft\Identity\IPrincipal;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface as IRequest;

class DelegatedPrincipalHandlerTest extends TestCase
{
    function testCallsDelegateWithWhichItWasConstructed(): void
    {
        /** @var string $actual */
        $actual = null;

        $expected = 'Gaius Marius';

        $handle = function(IRequest $request, IPrincipal $principal)
            use (&$actual): IRequest
        {
            $actual = $principal->getIdentity()->getName();

            return $request;
        };

        $handler = new DelegatedPrincipalHandler($handle);

        $request = new ServerRequest('GET', '/');

        $identity = new GenericIdentity($expected);

        $principal = new GenericPrincipal($identity);

        $handler->handlePrincipal($request, $principal);

        $this->assertEquals($expected, $actual);
    }
}
