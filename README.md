# SpaceTrader PHP

Client and tools for interacting with the [SpaceTraders](https://spacetraders.io) API. Follow development along in a series for php[architect] magazine at <https://phparch.com>

## Sample Usage

Register a new agent. Don't forget to grab the token for authentication until the next server reset. Save the token in `.env`. 

Visit <https://my.spacetraders.io/agents> to generate a token after each server reset.


Get Info about your agent:

```
./spacetraders agent
```

Add the token to `.env` file to authenticate other requests.

## Branch Names

For any PR, the commit hook will check the following requirements for a branch name:

1. Must start with 'task/', 'bug/', or 'feature/'
2. Must include an issue number (digits) followed by a dash.
3. Must end with a descriptive slug (letters, numbers, dashes).

## Database Tables

### Migrations

We are using Doctrine/Migrations to manage tables in our database. Common commands:

1. Create a migrations for a new or modified entity. Use `--formatted` so that:
   PHPCodeSniffer does not complain about lines > 100 chars.

```sh
./bin/doctrine-migrations.sh diff --formatted
```

2. See status of executed and pending migrations

```sh
./bin/doctrine-migrations.sh status
```

3. Run pending migrations

```sh
./bin/doctrine-migrations.sh migrate
```

