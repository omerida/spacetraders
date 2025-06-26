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

    #[Route(name: 'accept_contract', path: '/contract/accept', methods: ['POST'])]
    public function AccceptContract() {
        $post = $this->getRequest()->getParsedBody();
        if (!$post['id']) {
            throw new BadRequestException("Contract ID is required");
        }

        $id = $post['id'];

        return $this->client->accept($id);
    }

    #[Route(name: 'list_contracts', path: '/contracts/', methods: ['GET'])]
    public function list() {
        return $this->client->MyContracts();
    }

}