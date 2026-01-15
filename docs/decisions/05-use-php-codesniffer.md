---
status: accepted
date: 01-JAN-2026
---

# Use PHPCodeSniffer to enforce PER-3

## Context and Problem Statement

We have adopted [PER-3](https://www.php-fig.org/per/coding-style/) as our coding style and need automated tools to check, fix, and enforce adherence.

## Considered Options

* [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
  * PRO: Familiar with configuring it
  * CON: XML for rules configuration
* [PHP CS Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer)
  * PRO: Part of symfony


## Decision Outcome

Use PHP_Codesniffer since we are familiar with configuring it.

### Consequences

* Add to dev packages in `composer.json`
* Document how to run its linter and fixer at the command line
* Report violations when someone tries to push code.
* Check for violations in CI.
