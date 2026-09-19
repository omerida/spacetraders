<?php

namespace Phparch\SpaceTraders\Controller;

use League\Route\Http\Exception\BadRequestException;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTradersRest\Client;
use Phparch\SpaceTraders\Controller\Trait\RequestAwareController;
use Phparch\SpaceTraders\Controller\Trait\TwigAwareController;
use Phparch\SpaceTraders\Interface\RequestAware;
use Phparch\SpaceTraders\Interface\TwigAware;
use Phparch\SpaceTradersRest\Value\Goods\Symbol;
use Psr\Http\Message\ResponseInterface;

class ContractsController implements RequestAware, TwigAware
{
    use RequestAwareController;
    use TwigAwareController;

    public function __construct(
        private Client\Contracts $client,
    ) {
    }

    /**
     * @throws BadRequestException
     */
    #[Route(
        name: 'accept_contract',
        path: '/contract/accept',
        methods: ['POST'],
        strategy: 'application'
    )]
    public function accceptContract(): ResponseInterface
    {
        /**
         * @var array{
         *     id ?: string
         * } $post
         */
        $post = $this->getRequest()->getParsedBody();
        if (!isset($post['id']) || !$post['id']) {
            throw new BadRequestException("Contract ID is required");
        }

        $contract = $this->client->accept($post['id']);
        $contracts = $this->client->myContracts();

        return $this->render('contracts/accept.html.twig', [
            'contract' => $contract,
            'contracts' => $contracts->contracts,
        ]);
    }

    #[Route(
        name: 'list_contracts',
        path: '/contracts/',
        methods: ['GET'],
        strategy: 'application'
    )]
    public function list(): ResponseInterface
    {
        $contracts = $this->client->MyContracts()->contracts;
        return $this->render('contracts/list.html.twig', [
            'contracts' => $contracts,
        ]);
    }

    #[Route(
        name: 'get_contract',
        path: '/contracts/get/',
        methods: ['GET'],
        strategy: 'application'
    )]
    public function get(): ResponseInterface
    {
        /**
         * @var array{
         *     id ?: string
         * } $get
         */
        $get = $this->getRequest()->getQueryParams();
        if (!isset($get['id']) || !$get['id']) {
            throw new BadRequestException("Contract ID is required");
        }

        $contract = $this->client->details($get['id']);
        return $this->render('contracts/details.html.twig', [
            'contract' => $contract,
        ]);
    }

    /**
     * @throws BadRequestException
     */
    #[Route(
        name: 'contracts_deliver_cargo',
        path: '/contracts/deliver_cargo',
        methods: ['POST'],
        strategy: 'application'
    )]
    public function deliverCargo(): ResponseInterface
    {
        /**
         * @var array{contract?: string, good?: string, units?: int} $post
         */
        $post = (array) $this->getRequest()->getParsedBody();

        $good = strtoupper($post['good'] ?? '');
        if (!$good) {
            throw new BadRequestException("Please specify good to sell");
        }

        if (!($good = Symbol::tryFrom($good))) {
            throw new BadRequestException("Unknown good to sell.");
        }

        $ship = $post['ship'] ?? null;
        if (!$ship || !is_string($ship)) {
            throw new BadRequestException("Ship POST param missing");
        }

        $contract = $post['contract'] ?? null;
        if (!$contract || !is_string($contract)) {
            throw new BadRequestException("Contract ID POST param missing");
        }

        $units = $post['units'] ?? 0;
        if ($units == 0 || !is_numeric($units)) {
            throw new BadRequestException("Units POST param missing or zero");
        }
        $units = (int) $units;

        $response = $this->client->deliverCargo(
            id: $contract,
            shipSymbol: $ship,
            good: $good,
            units: $units
        );

        return $this->render('ships/ship-sell-goods.html.twig', [
            'cargo' => $response->cargo,
        ]);
    }
}
