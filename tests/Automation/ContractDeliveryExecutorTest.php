<?php

namespace Automation;

use Phparch\SpaceTradersRest\Value\Contract;
use PHPUnit\Framework\TestCase;

use Phparch\SpaceTraders\Automation\Executor\Ship\DeliverContractGoods;
use Phparch\SpaceTraders\Repository;
use Phparch\SpaceTradersRest\Client;
use Phparch\SpaceTradersRest\Value\Faction;
use Phparch\SpaceTradersRest\Value\Goods;
use Phparch\SpaceTradersRest\Value\PaymentTerms;
use Phparch\SpaceTradersRest\Value\RegistrationInfo;
use Phparch\SpaceTradersRest\Value\Route;
use Phparch\SpaceTradersRest\Value\Ship;
use Phparch\SpaceTradersRest\Value\System;
use Phparch\SpaceTradersRest\Value\Waypoint;

/**
 * Tests that the Contract Delivery Executor delivers the
 * correct amount of a good.
 */
class ContractDeliveryExecutorTest extends TestCase
{
    private const CONTRACT_ID = "cmws94xox0utata6xzin14gl5";

    public function testContractGoodsDelivery(): void
    {
        $shipID = 'PHP_ARCH-1';

        $fleet = $this->createStub(Client\Fleet::class);
        $fleet->method('getShip')
            ->willReturn($this->getShip());

        $contracts = $this->createMock(Client\Contracts::class);

        $contracts->method('details')
            ->willReturn($this->getContract(60, 0));

        $contracts->expects($this->once())
            ->method('deliverCargo')
            ->with(
                self::CONTRACT_ID,
                $shipID,
                Goods\Symbol::ALUMINUM_ORE,
                40
            );

        $shipTravel = $this->createMock(Client\ShipTravel::class);
        $shipTravel->expects($this->once())
            ->method('dockShip')
            ->with($shipID);

        $executor = new DeliverContractGoods(
            $shipID,
            $contracts,
            $fleet,
            $shipTravel,
            $this->createStub(Client\ShipActions::class),
            $this->createStub(Repository\Ship\OrderQueue::class)
        );

        $executor->setPayload([
            'contract' => self::CONTRACT_ID,
            'deliver' => ['ALUMINUM_ORE'],
        ]);

        $executor->execute();

        $this->assertNull($executor->getNextCheck());
        $this->assertTrue($executor->shouldAdvance());
    }

    public function testContractGoodsDeliverMoreThanRemaining(): void
    {
        $shipID = 'PHP_ARCH-1';

        $fleet = $this->createStub(Client\Fleet::class);
        $fleet->method('getShip')
            ->willReturn($this->getShip());

        $contracts = $this->createMock(Client\Contracts::class);

        $contracts->method('details')
            ->willReturn($this->getContract(60, 35));

        $contracts->expects($this->once())
            ->method('deliverCargo')
            ->with(
                self::CONTRACT_ID,
                $shipID,
                Goods\Symbol::ALUMINUM_ORE,
                25
            );

        $shipTravel = $this->createMock(Client\ShipTravel::class);
        $shipTravel->expects($this->once())
            ->method('dockShip')
            ->with($shipID);

        $executor = new DeliverContractGoods(
            $shipID,
            $contracts,
            $fleet,
            $shipTravel,
            $this->createStub(Client\ShipActions::class),
            $this->createStub(Repository\Ship\OrderQueue::class)
        );

        $executor->setPayload([
            'contract' => self::CONTRACT_ID,
            'deliver' => ['ALUMINUM_ORE'],
        ]);

        $executor->execute();

        $this->assertNull($executor->getNextCheck());
        $this->assertTrue($executor->shouldAdvance());
    }

    public function testContractGoodsDeliverWhenContractHasZeroRemaining(): void
    {
        $shipID = 'PHP_ARCH-1';

        $fleet = $this->createStub(Client\Fleet::class);
        $fleet->method('getShip')
            ->willReturn($this->getShip());

        $contracts = $this->createMock(Client\Contracts::class);

        $contracts->method('details')
            ->willReturn($this->getContract(60, 60));

        $contracts->expects($this->never())
            ->method('deliverCargo');

        $shipTravel = $this->createMock(Client\ShipTravel::class);
        $shipTravel->expects($this->once())
            ->method('dockShip')
            ->with($shipID);

        $executor = new DeliverContractGoods(
            $shipID,
            $contracts,
            $fleet,
            $shipTravel,
            $this->createStub(Client\ShipActions::class),
            $this->createStub(Repository\Ship\OrderQueue::class)
        );

        $executor->setPayload([
            'contract' => self::CONTRACT_ID,
            'deliver' => ['ALUMINUM_ORE'],
        ]);

        $executor->execute();

        $this->assertNull($executor->getNextCheck());
        $this->assertTrue($executor->shouldAdvance());
    }

    public function testContractGoodsDeliverWhenContractIsFulfilled(): void
    {
        $shipID = 'PHP_ARCH-1';

        $fleet = $this->createStub(Client\Fleet::class);
        $fleet->method('getShip')
            ->willReturn($this->getShip());

        $contracts = $this->createMock(Client\Contracts::class);

        $contracts->method('details')
            ->willReturn($this->getContract(60, 0, TRUE));

        $contracts->expects($this->never())
            ->method('deliverCargo');

        $shipTravel = $this->createMock(Client\ShipTravel::class);
        $shipTravel->expects($this->once())
            ->method('dockShip')
            ->with($shipID);

        $executor = new DeliverContractGoods(
            $shipID,
            $contracts,
            $fleet,
            $shipTravel,
            $this->createStub(Client\ShipActions::class),
            $this->createStub(Repository\Ship\OrderQueue::class)
        );

        $executor->setPayload([
            'contract' => self::CONTRACT_ID,
            'deliver' => ['ALUMINUM_ORE'],
        ]);

        $executor->execute();

        $this->assertNull($executor->getNextCheck());
        $this->assertTrue($executor->shouldAdvance());
    }

    public function testContractGoodsDeliveryWrongGoods(): void
    {
        $contractID = self::CONTRACT_ID;
        $shipID = 'PHP_ARCH-1';

        $fleet = $this->createStub(Client\Fleet::class);
        $fleet->method('getShip')
            ->willReturn($this->getShip());

        $contracts = $this->createMock(Client\Contracts::class);
        $contracts->method('details')
            ->willReturn($this->getContract(60, 0));

        $contracts->expects($this->never())
            ->method('deliverCargo')
            ->with(
                $contractID,
                $shipID,
                Goods\Symbol::ALUMINUM_ORE,
                40
            );

        $shipTravel = $this->createMock(Client\ShipTravel::class);
        $shipTravel->expects($this->once())
            ->method('dockShip')
            ->with($shipID);

        $executor = new DeliverContractGoods(
            $shipID,
            $contracts,
            $fleet,
            $shipTravel,
            $this->createStub(Client\ShipActions::class),
            $this->createStub(Repository\Ship\OrderQueue::class)
        );

        $executor->setPayload([
            'contract' => self::CONTRACT_ID,
            'deliver' => ['IRON_ORE'],
        ]);

        $executor->execute();

        $this->assertNull($executor->getNextCheck());
        $this->assertTrue($executor->shouldAdvance());
    }

    private function getContract(int $required, int $fulfilled, bool $isFulfilled = false): Contract {
        return new Contract(
            id: self::CONTRACT_ID,
            factionSymbol: $this->createStub(Faction\Symbol::class),
            type: Contract\Type::PROCUREMENT,
            terms: new Contract\Terms(
                deadline: new \DateTimeImmutable("+30 days"),
                payment: $this->createStub(PaymentTerms::class),
                deliver: [
                    new Contract\Deliver(
                        tradeSymbol: Goods\Symbol::ALUMINUM_ORE,
                        destinationSymbol: 'X1-MG38-H57',
                        unitsRequired: $required,
                        unitsFulfilled: $fulfilled,
                    )
                ],
            ),
            accepted: true,
            fulfilled: $isFulfilled,
            expiration: new \DateTimeImmutable("+30 days"),
            deadlineToAccept: new \DateTimeImmutable("+10 days"),
        );
    }

    private function getShip(): Ship {
        return new Ship(
            symbol: 'PHP_ARCH-1',
            nav: new Ship\Nav(
                systemSymbol: new System\Symbol('X1-MG38'),
                waypointSymbol: new Waypoint\Symbol('X1-MG38-H57'),
                route: $this->createStub(Route::class),
                status: Ship\Status::IN_ORBIT,
                flightMode: Ship\FlightMode::CRUISE
            ),
            crew: $this->createStub(Ship\Crew\Details::class),
            fuel: $this->createStub(Ship\Fuel::class),
            cooldown: $this->createStub(Ship\CoolDown::class),
            frame: $this->createStub(Ship\Frame::class),
            reactor: $this->createStub(Ship\Reactor::class),
            engine: $this->createStub(Ship\Engine::class),
            modules: [$this->createStub(Ship\Module::class)],
            mounts: [$this->createStub(Ship\Mount::class)],
            registration: $this->createStub(RegistrationInfo::class),
            cargo: new Ship\CargoDetails(
                capacity: 40,
                units: 40,
                inventory: [
                    new Goods(
                        symbol: Goods\Symbol::ALUMINUM_ORE,
                        name: Goods\Symbol::ALUMINUM_ORE->value,
                        description: "Description of Aluminum Ore",
                        units: 40,
                    )
                ]
            )
        );
    }
}
