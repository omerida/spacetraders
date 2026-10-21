---
status: proposed | rejected | accepted | deprecated | … | superseded by ADR-0123
date: OCT-20-2026
---

# ADR 09: Custom PHP 8 Enums and Entities for Ship State Machine and Order Queue

## Context and Problem Statement

We want to automate ship activity and need a way to model the current state 
and represent a queue of orders for any given ship. 

Orders are in-game actions that a ship can perform which can change its state. Orders may have their own internal logic, like "Mine until cargo is full, jettison a subset of goods."

Initial set of orders to implement

1. Travel to a Waypoint
   1.1. Set mode. Internal logic should handle going to orbit, and docking at destination.
2. Extract Resources
   2.1. Set which goods you want to keep and automatically jettison the rest
   2.2. Keep extracting if cargo is not full
   2.3. Wait for cooldown if needed.
3. Sell goods at a Marketplace
   3.1. Travel handled as separate action
   3.2. Check if good available to sell. 
4. Purchase set quantity of goods at a Marketplace
   4.1. Travel handled as separate action
   4.2. Check if enough credits are available to purchase.
   4.3. Check if desired good, quantity is available -> if not availble go to next order?
   4.4. Purchase the good.
5. Visit a marketplace to gather price data
6. Refuel at Waypoint.  Refueling doesn't ask for a purchase quantity.
   6.1. Initial implementation will rely on players tracking fuel consumption 

Ship Queue and Order State is separate from the in-game state of a ship. The orders we queue may change in-game state more than once. For example, to travel, the order would first check if a ship is docked, if true, set it to orbit, then check the thrust, set to desired, and finally issue the travel command.
 
## Considered Options

1. Homegrown finite-state machine can be implemented with PHP 8.x syntax. We can use Enums to define possible states, a Doctrine entity to persist the current state of a ship, and a simple finite-state machine to model moving from one possible state to another. Another Doctrine Entity can model the queue of orders defined for a ship and how to manage the queue. A queue should be able to loop once it gets to the end, or it can stop when the queue is exhausted. 
2. Symfony has a component for building [state machines and workflows](https://symfony.com/doc/current/workflow/workflow-and-state-machine.html). Pulls in Symfony dependencies. 
3. [php-architecture-kit/state-machine](https://packagist.org/packages/php-architecture-kit/state-machine) is a newer library in development for graph-based state machines in PHP. It implements many features.

## Decision Outcome

Chosen option: **Custom Domain-Driven State Machine (Option 1)**.

Keep implementation simple with our own set of enums, entities, and supporting classes.

* **`ShipOrderType` Enum:** Defines high-level routines (`TRAVEL`, `EXTRACT`, `SELL_GOODS`, `PURCHASE_GOODS`, `VISIT_MARKETPLACE`, `REFUEL`, `IDLE`).
* **Order Handler Strategy:** Dedicated handler classes for each `ShipOrderType` to encapsulate specific step logic and API integration.
* **`ShipOrderQueue` Entity:** Manages the order sequence for a given ship, including queue position, execution mode (`LOOP` vs. `ONCE`), and current status (`PENDING`, `IN_PROGRESS`, `FAILED`, `COMPLETED`).
* **Event-Driven Execution:** Leverage existing PHP Event Dispatcher to handle task completions, transitions, and automated queue progression.

The Symfony and PHP-Architecture state machine packages seem designed for more complex business workflows with features like multi-user approval flows that we dont' need for our game client. Adding either one introduces external dependencies and configuration files that must be maintained across updates. Implementing a custom FSM using PHP 8 Backed Enums and Doctrine keeps the domain logic lightweight and tailored directly to our application's database schema.

### Consequences

PROS:

* **Zero External Dependencies:** Eliminates reliance on third-party state machine libraries, keeping our domain model clean and fully customized to SpaceTraders API quirks.
* **Resilient State Persistence:** Storing queue state, step progress, and cooldown timestamps in SQLite/Doctrine ensures background FleetCom daemons can recover seamlessly after script restarts.
* **Extensible Architecture:** Adding new order types (e.g., `SURVEY`, `SIPHON_GAS`) requires only adding an Enum case and a corresponding Handler class.

CONS:

* **Custom Maintenance:** We are responsible for managing state transition edge cases, failure recoveries, and order validation.
* **Sub-State Complexity:** High-level orders with internal steps (e.g., `EXTRACT` handling transit, docking, mining, and jettisoning) require careful sub-state tracking to prevent duplicate API dispatches. We should only handle complex sequences of game commands when there aren't cooldowns between orders.