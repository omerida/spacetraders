<?php

namespace Phparch\SpaceTraders\Value\Ship\Reactor;

class Requirements
{
    public function __construct(
        /** @var non-negative-int */
        public int $crew {
            set {
                if ($value < 0) {
                    throw new \InvalidArgumentException('crew cannot be negative');
                }
                $this->crew = $value;
            }
        },
        /** @var null|non-negative-int */
        public ?int $power = null {
            set {
                if (is_null($value)) {
                    $this->power = null;
                    return;
                }
                if ($value < 0) {
                    throw new \InvalidArgumentException('power cannot be negative');
                }
                $this->power = $value;
            }
        },
        public ?int $slots = null {
            set {
                if (is_null($value)) {
                    $this->slots = null;
                    return;
                }
                if ($value < 0) {
                    throw new \InvalidArgumentException('slots cannot be negative');
                }
                $this->slots = $value;
            }
        }
    )
    {
    }
}
