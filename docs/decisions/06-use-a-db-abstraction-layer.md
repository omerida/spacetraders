---
status: accepted
date: 1-SEP-2026
---

# Use a DBAL/ORM Layer

## Context and Problem Statement

We need to persist data to a database and prefer to minimize writing SQL.

## Considered Options

* [Doctrine](https://github.com/doctrine)
  * PRO: Supports many features beyond access layer, including entities, migrations
  * PRO: Supports multiple databases
  * PRO: Entitye
  * CON: Can't easily switch later.
* PHP PDO
  * PRO: Built-in to PHP, no additional dependencies.
  * CON: Have to roll-our-own solutions to migrations, entities, or write a lot of raw SQL.

## Decision Outcome

Use Doctrine for direct DB access at first, introduce Entities as needed.

### Consequences

* Add to packages in `composer.json`
* Configure services for reading/writing to an application database.
* Update docker scripts to initialize a functional database when creating application.

