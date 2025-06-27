<?php

namespace Phparch\SpaceTraders\Controller;

use League\Route\Http\Exception\BadRequestException;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\Controller\RequestAwareController;

class ContractsController extends RequestAwareController
{
    public function __construct(
        private Client\Contracts $client,
    ) {
    }

    /**
     * @return array<mixed>
     * @throws BadRequestException
     */
    #[Route(name: 'accept_contract', path: '/contract/accept', methods: ['POST'])]
    public function AccceptContract(): array
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

    /**
     * @return array<mixed>
    */
    #[Route(name: 'list_contracts', path: '/contracts/', methods: ['GET'])]
    public function list(): array
    {
        return (array) $this->client->MyContracts();
    }
}
