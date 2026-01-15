---
status: accepted
date: 01-Jan-2026
---


# Use PSR-4 for Autoloading custom classes

## Context and Problem Statement

Project needs to follow some autoloading mechanism for PHP classes.

## Considered Options

* Adopt [PSR-4](https://www.php-fig.org/psr/psr-4/)
  * PRO: supported by Composer and known wider PHP community
* Write a custom autoloader
  * CON: another thing to maintain

## Decision Outcome

Custom classes must follow PSR-4 conventions for naming files and namespaces.

### Consequences

We will need to register namespaces in `composer.json`. For example:

```json
  "autoload": {
    "psr-4": {
      "Phparch\\SpaceTradersCLI\\": "cli/",
      "Phparch\\SpaceTraders\\": "src/"
    }
  },
```

