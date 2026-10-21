---
status: accepted
date: 11-10-2026
---

# Use RevoltPHP for Asynchronous Ship Order Daemon

## Context and Problem Statement

We need a long-running script to monitor a player's SpaceTrader ships and queue or issue orders for ships. This command is the central hub for automating activity in the game. It must constantly monitor which ships are awaiting a orders, then issue new ones. It should be scalable to handling dozens of ships at once by managing a single ship asynchronously. That is, giving orders to one ship should not block managing other ships.  

## Considered Options

1. **A Python or JavaScript solution:** While other languages are typically considered better suited for async loops, that would require adding another language, package manager, and integration to our current application. We'd prefer to use a PHP solution that can use our existing Doctrine classes and SpaceTraders REST client.
2. **A Cron Script:** Traditionally, one solution is to run a single PHP script via system cron every minute. However, this doesn't scale in this scenario because we may need to give one ship many orders in a single minute. Also, as the number of automated ships grows, each cron run can end up taking longer than one minute leading to race conditions.
3. **OpenSwoole** provides true async, coroutine-based execution with non-blocking I/O. It has high throughput and light memory usage because it's implemented as a PHP extension. Managing extensions adds to maintenance and set up costs and is not likely to pay off because our initial performance needs are not heavy. 
4. **ReactPHP** is a well established PHP implementation of the reactor pattern, similar to Node.js using promises and callbacks. It offers event loop abstractions, streams, promises plus third-party packages that build on it. It is useful for lightweight microservices and legacy-compatible PHP environments. However, it relies on chained promises via a `.then()` method within an explicit event-loop instance. It is built for PHP7.1, though it does support PHP 8.1. Exceptions are  passed down promise rejection handlers, and unhandled rejections in promises can cause errors to be "lost" if not explicitly logged. This adds another layer of syntax to learn and enforce.

```PHP
// ReactPHP Example
use React\Http\Browser;

$client = new Browser();
$client->get('https://api.spacetraders.io/v2/')
    ->then(function (\Psr\Http\Message\ResponseInterface $response) {
        echo (string) $response->getBody();
    }, function (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    });
```

5. **Revolt / Amp v3** (RevoltPHP (and the Amp v3 ecosystem) offers a fiber-based event loop for PHP 8.1. This gives us fiber-driven concurrency using syntax that looks synchronous. This avoids "callback hell".  It is well-suited for applications that want async code without requiring an extension. Revolt is designed as a single global event loop standard. It can act as a universal driver layer that other async libraries can  use. Exceptions are caught using standard `try/catch` blocks making stack traces easier to debug.

```php
// Revolt / Amp v3 Example
use Amp\Http\Client\HttpClientBuilder;
use Amp\Http\Client\Request;

$client = HttpClientBuilder::buildDefault();
$response = $client->request(new Request('https://api.spacetraders.io/v2/'));
$body = $response->getBody()->buffer();

echo $body; // Code executes sequentially without callbacks
```
## Decision Outcome

Recommend using RevoltPHP since we are already using PHP 8.1. It gives us a clean, maintainable codebase without callback nesting or promise chains. We may have complex domain logic with multiple sequential async steps and should use a modern fiber-native solution using current PHP engine features.

### Consequences

PRO: Native PHP 8.1 debugging (try/catch, readable stack traces).
PRO: Low CPU/RAM footprint when idling during long API cooldowns (delay()).
CON: **Doctrine ORM Memory Management:** Long-running daemon processes will retain entity states in Doctrine's UnitOfWork. The worker/dispatcher loop must explicitly clear the EntityManager ($em->clear()) per cycle to prevent memory leaks.
CON: Requires process supervision (e.g., Systemd, Supervisor, or Docker restart policy) to restart the script if an unhandled exception occurs.


