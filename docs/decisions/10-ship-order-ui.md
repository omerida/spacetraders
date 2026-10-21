---
status: proposed
date: 20-OCT-2026
---

# UX for Managing a Ship's Order Queue

## Context and Problem Statement

We want to allow players to automate a ship's behavior by queuing a set of orders for the ship to execute. The UI must display:

1. The overall queue execution mode (`LOOP`, `ONCE`, `PAUSED`).
2. The current active/executing order processed by the background daemon (FleetCom).
3. The remaining ordered sequence of queued tasks.
4. Controls to add, edit, reorder, or delete orders

## Considered Options

1. **Fleet-Wide Overview Page with Fly-Out/Modal Panels:** A centralized "Ships" table listing all ships in the fleet. Clicking an order status button opens an Unpoly modal or drawer showing that ship's order queue.
2. **Dedicated Card on Individual Ship Page:** A standalone UI card embedded on the existing single-ship view page. This option keeps order managementnear other ship details like fuel levels, location coordinates, cargo inventory, and ship navigation controls.

### UI/UX Implementation Details

* **Queue Mode Toggle:** A prominent status button or radio group (`LOOP`, `ONCE`, `PAUSED`) in the card header. Toggling mode sends an inline Unpoly form POST/PATCH (`up-target="#ship-order-queue-card"`) to update state immediately. T
* **Active Task Highlight:** The currently executing order is distinctly highlighted (e.g., active border or badge) with its live execution status (`PENDING`, `IN_PROGRESS`, `WAITING_COOLDOWN`).
* **Order Item Controls:** Each queued item displays its action details (e.g., `EXTRACT (Target: IRON_ORE)`) with inline actions to delete or shift priority order up/down.
* **Unpoly Modal Integration for Order Creation:** Clicking "Add Order" opens an Unpoly modal layer (`up-layer="new"`) containing a dynamic Twig form to configure order details (selecting waypoints, trade goods, or quantities). Submitting the form validates input server-side, saves the order, closes the modal, and refreshes the `#ship-order-queue-card` fragment seamlessly.

## Consequences

PROS:

* **High Context Visibility:** Players see their ship's live cargo, fuel, and location right next to the active order queue, making order creation less prone to user error (e.g., queuing `EXTRACT` when cargo is already full).
* **Lightweight & Fast Interactions:** Unpoly fragment updates (`up-target="#ship-order-queue-card"`) allow smooth CRUD operations without full-page reloads or complex JS front-end state frameworks.
* **Clear State Representation:** Visually separating the active order from upcoming queued orders prevents confusion about what FleetCom is executing in real time.

CONS:

* **Requires Navigating to Ship Page:** Managing queues across a large fleet requires navigating to each individual ship's page rather than making bulk batch updates from a single master fleet list. (A future global fleet view can build upon these same underlying order endpoints).
* **Live Status Refresh:** If FleetCom advances an order while the user has the page open, the client UI won't update until the user interacts with the card or an Unpoly polling interval (`up-poll`) fetches the latest fragment state. We should explore some method to communicate to the game client when an order is issued by fleetcom, using WebSockets or Server-Sent Events


