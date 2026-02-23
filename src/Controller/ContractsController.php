<?php

namespace Phparch\SpaceTraders\Controller;

use League\Route\Http\Exception\BadRequestException;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Controller\Trait\RequestAwareController;
use Phparch\SpaceTraders\Controller\Trait\TwigAwareController;
use Phparch\SpaceTraders\Interface\RequestAware;
use Phparch\SpaceTraders\Interface\TwigAware;
use Phparch\SpaceTraders\Value\Contract;
use Phparch\SpaceTraders\Value\Contracts;
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
}
