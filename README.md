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