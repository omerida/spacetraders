<?php

namespace Phparch\SpaceTraders;

class SpaceTradersException extends \Exception
{
    /** @var array<string, mixed>  */
    public readonly array $data;

    public function __construct(string $message, ?\Throwable $previous = null)
    {
        /**
         * @var array{
         *    error: array{
         *      message: string,
         *      code: int,
         *      data: array<string, mixed>
         *    }
         *  } $json
         */
        $json = json_decode($message, true, 512, JSON_THROW_ON_ERROR);
        parent::__construct($json['error']['message'], (int) $json['error']['code'], $previous);

        $this->data = $json['error']['data'];
    }
}
