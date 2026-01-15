---
status: accepted
date: 01-JAN-2026
---

# Adopts PER-3 as project coding style

## Context and Problem Statement

Spacetraders application code needs to follow a consistent coding style guide that can be enforced with an automatic linter and supported by most IDEs.

## Considered Options

Both of these candidates report violations and can try to fix them.

* [PSR-12](https://www.php-fig.org/psr/psr-12/)
* * PRO: existing coding standard we can adopt
  * CON: superceded by PER-3
* [PER-3](https://www.php-fig.org/per/coding-style/)
  * PRO: evolving standard, adopted in wider PHP community
* Symfony, Drupal, WordPress or other coding standard
  * PRO: existing coding standard we can adopt
  * CON: We are not any of those projects and they each have their own quirks.

## Decision Outcome

Use PER-3 as the project's coding style and enforce it automatically.

### Consequences

Need to identify tooling for linting and automated checks.

