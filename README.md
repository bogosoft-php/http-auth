# bogosoft/http-auth

A PHP library for PSR-7 and PSR-15 compliant authentication middleware.

### Requirements

- PHP >= 7.4

### Package Dependencies

- [bogosoft/identity](https://packagist.org/packages/bogosoft/identity)

### Installation

```bash
composer require bogosoft/http-auth
```

### Summary

This small library contains a PSR-15 compliant authentication middleware component, `AuthenticationMiddleware`,
with the following responsibilities:

- Derive a security context (`IPrincipal` object) from a PSR-7 compliant HTTP request. The `IPrincipal` contract is
  from the [`bogosoft/identity`](https://packagist.org/packages/bogosoft/identity) package.
- Pass the derived security context to a `IPrincipalHandler` object on a successful authentication attempt.
- Immediately return an HTTP response with a `401 Unauthorized` status code on a failed authentication attempt.
  this is configurable.
  
### Interfaces

|Name|Purpose|
|----|-------|
|[`IAuthenticator`](src/IAuthenticator.php)|Derives a security context (`IPrincipal`) from an HTTP request.|
|[`IPrincipalHandler`](src/IPrincipalHandler.php)|Handles a derived security context and allows for HTTP request modification.|

### Implementations

|Name|Interface|Details|
|----|---------|-------|
|[`CompositeAuthenticator`](src/CompositeAuthenticator.php)|`IAuthenticator`|Allows multiple `IAuthenticator` objects to behave as one.|
|[`DelegatedAuthenticator`](src/DelegatedAuthenticator.php)|`IAuthenticator`|Turns a `callable` object in an `IAuthenticator` interface.|
|[`AuthenticationMiddleware`](src/AuthenticationMiddleware.php)|`MiddlewareInterface`|An authenticating middleware component.|
|[`AttributePrincipalHandler`](src/AttributePrincipalHandler.php)|`IPrincipalHandler`|Stores the derived `IPrincipal` object into a mutated HTTP request as an attribute.|
|[`DelegatedPrincipalHandler`](src/DelegatedPrincipalHandler.php)|`IPrincipalHandler`|Turns a `callable` object into a `IPrincipalHandler`.|
