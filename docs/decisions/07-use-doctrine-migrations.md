---
status: accepted
date: 16-SEP-2026
---

# Use Doctrine/Migrations for Schema management.

## Context and Problem Statement

We need a more robust and production-ready way to manage database schemas than collecting SQL statements in our docker setup and then manually running `CREATE TABLE` statements in a query console.

## Considered Options

* [Doctrine/Migrations](https://github.com/doctrine/migrations)
  * PRO: Integrates with exists DBAL 
* [phinx](https://github.com/cakephp/migrations)
  * PRO: Used it on a previous project. No dependency on Doctrine
  * CON: More setup work and doesn't leverage Doctrine entities.


## Decision Outcome

Use Doctrine Migrations to manage schemas because it integrates with our chosen DBAL.

### Consequences

* Add to packages in `composer.json`
* Set up migrations for non-entity tables
* Set up migrations for entities.
* Document how to run migration in `README`

