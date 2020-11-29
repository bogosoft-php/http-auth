<?php

declare(strict_types=1);

namespace Bogosoft\Http\Auth;

use Psr\Http\Message\ResponseFactoryInterface as IResponseFactory;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use Psr\Http\Server\MiddlewareInterface as IMiddleware;
use Psr\Http\Server\RequestHandlerInterface as IRequestHandler;

/**
 * An HTTP middleware component that authenticates an HTTP request.
 *
 * @package Bogosoft\Http\Auth
 */
class AuthenticationMiddleware implements IMiddleware
{
    private IAuthenticator $authenticator;
    private IPrincipalHandler $principalHandler;
    private ?IResponseFactory $responses;

    /**
     * Create a new HTTP authentication middleware component.
     *
     * If an HTTP response factory is provided, unsuccessful attempts to
     * authenticate an HTTP request will result in an HTTP response with a
     * status code of 401 Unauthorized.
     *
     * @param IAuthenticator        $authenticator    An HTTP request
     *                                                authentication strategy.
     * @param IPrincipalHandler     $principalHandler A strategy for handling
     *                                                a principal derived from
     *                                                the given HTTP request.
     * @param IResponseFactory|null $responses        An HTTP response factory.
     */
    function __construct(
        IAuthenticator $authenticator,
        IPrincipalHandler $principalHandler,
        IResponseFactory $responses = null
        )
    {
        $this->authenticator    = $authenticator;
        $this->principalHandler = $principalHandler;
        $this->responses        = $responses;
    }

    /**
     * @inheritDoc
     */
    function process(IRequest $request, IRequestHandler $handler): IResponse
    {
        $result = $this->authenticator->authenticate($request);

        if ($result->success && null !== $result->principal)
            $request = $this->principalHandler->handlePrincipal(
                $request,
                $result->principal
                );
        elseif (null !== $this->responses)
            return $this->responses->createResponse(401);

        return $handler->handle($request);
    }
}
