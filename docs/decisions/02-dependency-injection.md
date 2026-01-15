---
status: accepted
date: 14-JAN-2026
---

# Use PHP-DI for service container

## Context and Problem Statement

PHP classes should support dependency injection for composing objects instead of direct instantiation. We need to provide an easy-to-use Dependency Injection container for developers that follows PSR-11 container interface.

## Considered Options

* php-di/php-di
  * PRO: autowiring based on typehints, can register with callbacks. Have previous experience using it.
  * CON: many open issues
* league/container
  * PRO: actively maintained
  * CON: service registration is verbose
* symfony/dependency-injection
  * PRO: backed by symfony development
  * CON: service registration is verbose

## Decision Outcome

Use PHP-DI due to familiarity with it and its syntax.

### Consequences

* Will implement a service container that uses PHP-DI
* May need to look into container compilation as application grows.

