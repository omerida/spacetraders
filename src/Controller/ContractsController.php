<?php

namespace Phparch\SpaceTraders\Controller;

use League\Route\Http\Exception\BadRequestException;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Controller\Trait\RequestAwareController;
use Phparch\SpaceTraders\Controller\Trait\TwigAwareController;
use Phparch\SpaceTraders\RequestAwareInterface;
use Phparch\SpaceTraders\TwigAwareInterface;
use Psr\Http\Message\ResponseInterface;

class ContractsController implements RequestAwareInterface, TwigAwareInterface
{
    use RequestAwareController;
    use TwigAwareController;

    public function __construct(
        private Client\Contracts $client,
    ) {
    }

    /**
     * @return array<mixed>
     * @throws BadRequestException
     */
    #[Route(name: 'accept_contract', path: '/contract/accept', methods: ['POST'])]
    public function accceptContract(): array
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

        $id = $post['id'];

        return (array) $this->client->accept($id);
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
