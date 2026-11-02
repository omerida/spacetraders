<?php

namespace Phparch\SpaceTraders\Presenter;

use Phparch\SpaceTraders\Automation\Ship\OrderType;
use Phparch\SpaceTraders\Entity\Ship\Order;
use Twig\Markup;

class ShipOrderSummary
{
    public function __construct(
        private Order $order,
    )
    {
    }

    public function getID(): int {
        if ($id = $this->order->getID()) {
            return $id;
        }

        throw new \RuntimeException('Order ID not set');
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
            case OrderType::EXTRACT_RESOURCES->value:
                return $this->formatExtractPayload();

            case OrderType::TRAVEL->value:
                return $this->formatTravelPayload();

            case OrderType::REFUEL->value:
                return $this->formatRefuelPayload();

            case OrderType::SELL_GOODS->value:
                return $this->formatSellGoodsPayload();
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

    private function formatExtractPayload(): Markup {
        /**
         * @var array{
         *   keep: string[],
         * } $payload
         */
        $payload = $this->order->getPayload();

        $html = '';
        if ($payload['keep']) {
            $goods = [];
            foreach ($payload['keep'] as $value) {
                $value = strtolower($value);
                $value = str_replace('_', ' ', $value);
                $value = ucwords($value);

                $good = htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $goods[] = '<em>' . $good . '</em>';
            }

            $html .= 'Only keep: ' . implode(', ', $goods);
        }
        // Avoids using raw filter
        return new Markup($html, 'UTF-8');
    }

    private function formatRefuelPayload(): Markup {
        return new Markup('Refuel if possible at current waypoint.', 'UTF-8');
    }

    private function formatSellGoodsPayload(): Markup {
        /**
         * @var array{
         *   keep: string[],
         * } $payload
         */
        $payload = $this->order->getPayload();

        $html = '';
        if ($payload['sell']) {
            $goods = [];
            foreach ($payload['sell'] as $value) {
                $value = strtolower($value);
                $value = str_replace('_', ' ', $value);
                $value = ucwords($value);

                $good = htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $goods[] = '<em>' . $good . '</em>';
            }

            $html .= 'Sell all quantities of: ' . implode(', ', $goods);
        }
        // Avoids using raw filter
        return new Markup($html, 'UTF-8');
    }
}
