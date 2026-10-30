<?php

namespace Phparch\SpaceTraders\Presenter;

use Phparch\SpaceTraders\Entity\Ship\Order;
use Twig\Markup;

class ShipOrderSummary
{
    public function __construct(
        private Order $order,
    )
    {
    }

    public function getType(): string
    {
        return $this->order->getType()->value;
    }

    public function getStatus(): string
    {
        return $this->order->getStatus()->value;
    }

    public function getPayload(): string|Markup
    {

        switch ($this->getType()) {
            case 'TRAVEL':
                return $this->formatTravelPayload();

            default:
                return json_encode($this->order->getPayload(), JSON_THROW_ON_ERROR);
        }
    }

    private function formatTravelPayload(): Markup
    {
        /**
         * @var array{
         *   destination: string,
         *   flightmod: string,
         * } $payload
         */
        $payload = $this->order->getPayload();

        // Sanitize untrusted dynamic data before wrapping in HTML
        $destination = htmlspecialchars(
            $payload['destination'] ?? '',
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );
        $flightmode = htmlspecialchars(
            $payload['flightmode'] ?? '',
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );

        $html = sprintf(
            "To <em>%s</em> in <em>%s</em> mode.",
            $destination,
            $flightmode
        );

        // Avoids using raw filter
        return new Markup($html, 'UTF-8');
    }
}
