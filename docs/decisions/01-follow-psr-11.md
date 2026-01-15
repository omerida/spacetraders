---
status: accepted
date: 14-JAN-2026
---

# Follow PSR-11 for Dependency Injection Containers

## Context and Problem Statement

The spacetraders application should use a dependency injection container (DIC) to promote object composition and discourage direct instantiation of service classes.

## Considered Options

* [Adopting PSR-11](https://www.php-fig.org/psr/psr-11/)
* Rolling a custom container

## Decision Outcome

Any DIC we use must follow PSR-11 to allow us to change to a new or better implementation in the future.

### Consequences

* May limit the packages we can use as a DIC
* We are not locked into using any single solution.
