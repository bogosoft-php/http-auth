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

|Name|Purpose|Link|
|----|-------|----|
|`IAuthenticator`|Derives a security context (`IPrincipal`) from an HTTP request.|[Link](src/IAuthenticator.php)|
|`IPrincipalHandler`|Handles a derived security context and allows for HTTP request modification.|[Link](src/IPrincipalHandler.php)|

### Implementations

|Name|Interface|Details|Link|
|----|---------|-------|----|
|`CompositeAuthenticator`|`IAuthenticator`|Allows multiple `IAuthenticator` objects to behave as one.|[Link](src/CompositeAuthenticator.php)||
|`DelegatedAuthenticator`|`IAuthenticator`|Turns a `callable` object in an `IAuthenticator` interface.|[Link](src/DelegatedAuthenticator.php)||
|`AuthenticationMiddleware`|`MiddlewareInterface`|An authenticating middleware component.|[Link](src/AuthenticationMiddleware.php)||
|`AttributePrincipalHandler`|`IPrincipalHandler`|Stores the derived `IPrincipal` object into a mutated HTTP request as an attribute.|[Link](src/AttributePrincipalHandler.php)|
|`DelegatedPrincipalHandler`|`IPrincipalHandler`|Turns a `callable` object into a `IPrincipalHandler`.|[Link](src/DelegatedPrincipalHandler.php)|
