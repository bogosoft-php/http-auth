<?php

declare(strict_types=1);

namespace Bogosoft\Http\Auth\Tests;

use Bogosoft\Http\Auth\AuthenticationMiddleware;
use Bogosoft\Http\Auth\DelegatedPrincipalHandler;
use Bogosoft\Identity\IPrincipal;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use Psr\Http\Server\RequestHandlerInterface as IRequestHandler;

class AuthenticationMiddlewareTest extends TestCase
{
    function testForwardsRequestWhenAuthenticatorFailsWithNoResponseFactory(): void
    {
        $request = new ServerRequest('GET', '/');

        $authenticator = new NeverAuthenticator();

        $control = $authenticator->authenticate($request);

        $this->assertFalse($control->success);

        $middleware = new AuthenticationMiddleware(
            $authenticator,
            new NullPrincipalHandler()
            );

        $requestHandler = new class implements IRequestHandler
        {
            public function handle(IRequest $request): IResponse
            {
                return new Response(404);
            }
        };

        $response = $middleware->process($request, $requestHandler);

        $this->assertEquals(404, $response->getStatusCode());
    }

    function testHandlesPrincipalWhenAuthenticatorSucceeds(): void
    {
        $request = new ServerRequest('GET', '/');

        $authenticator = new AlwaysAuthenticator();

        $control = $authenticator->authenticate($request);

        $this->assertTrue($control->success);

        $handled = false;

        $principalHandler = new DelegatedPrincipalHandler(
            function(IRequest $request, IPrincipal $principal) use (&$handled)
                : IRequest
            {
                $handled = true;

                return $request;
            }
        );

        $middleware = new AuthenticationMiddleware(
            $authenticator,
            $principalHandler
            );

        $requestHandler = new class implements IRequestHandler
        {
            public function handle(IRequest $request): IResponse
            {
                return new Response(404);
            }
        };

        $middleware->process($request, $requestHandler);

        $this->assertTrue($handled);
    }

    function testReturns401WhenAuthenticatorFailsWithResponseFactory(): void
    {
        $request = new ServerRequest('GET', '/');

        $authenticator = new NeverAuthenticator();

        $control = $authenticator->authenticate($request);

        $this->assertFalse($control->success);

        $handler = new class implements IRequestHandler
        {
            public function handle(IRequest $request): IResponse
            {
                return new Response(404);
            }
        };

        $responses = new class implements ResponseFactoryInterface
        {
            public function createResponse(
                int $code = 200,
                string $reasonPhrase = ''
                )
                : IResponse
            {
                return new Response($code);
            }
        };

        $middleware = new AuthenticationMiddleware(
            $authenticator,
            new NullPrincipalHandler(),
            $responses
            );

        $response = $middleware->process($request, $handler);

        $this->assertEquals(401, $response->getStatusCode());
    }
}
