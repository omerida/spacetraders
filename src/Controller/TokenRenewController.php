<?php

namespace Phparch\SpaceTraders\Controller;

use GuzzleHttp\Psr7;
use Phparch\SpaceTraders\Attribute\Route;
use Phparch\SpaceTraders\Controller\Trait\RequestAwareController;
use Phparch\SpaceTraders\Data\SystemRegistry;
use Phparch\SpaceTraders\Interface\RequestAware;
use Phparch\SpaceTraders\Interface\TwigAware;
use Psr\Http\Message\ResponseInterface;

class TokenRenewController implements RequestAware, TwigAware
{
    use RequestAwareController;
    use Trait\TwigAwareController;

    public function __construct(
        private SystemRegistry $registry,
    ) {
    }

    #[Route(
        name: 'get-new-token',
        path: '/get-new-token',
        methods: ['GET'],
        strategy: 'application'
    )]
    public function getNewToken(): ResponseInterface
    {
        return $this->render('get-new-token.html.twig', [
        ]);
    }

    #[Route(
        name: 'save-new-token',
        path: '/get-new-token',
        methods: ['POST'],
        strategy: 'application'
    )]
    public function saveNewToken(): ResponseInterface
    {
        /**
         * @var array{token?:string} $post
         */
        $post = (array) $this->getRequest()->getParsedBody();
        $token = trim($post['token'] ?? '');
        if (!$token) {
            return new Psr7\Response(
                status: 303, // Ensure GET
                headers: ['Location' => '/get-new-token']
            );
        }
        $this->registry->storeText('spacetraders_token', $token);
        return new Psr7\Response(
            status: 303, // Ensure GET
            headers: ['Location' => '/']
        );
    }
}
