<?php

namespace Phparch\SpaceTradersCLI\Render;

class AbstractRenderer implements RenderInterface
{
    /**
     * @var string[]
     */
    private $out = [];

    private const ESC = "\033["; // double quotes critical
    private const CLOSE = 'm';
    private const BLK = '30';
    private const RED = '31';
    private const GRN = '32';
    private const YEL = '33';
    private const BLU = '34';
    private const MAG = '35';
    private const CYN = '35';
    private const BOLD = '1';

    // placeholder for ASCII terminal colors
    private array $colors = [
        '<:DEF:>' => self::ESC . self::RESET . self::CLOSE,
        '<:BLK:>' => self::ESC . self::BLK . self::CLOSE,
        '<:BLKBOLD:>' => self::ESC . self::BOLD . ';'
            . self::BLK . self::CLOSE,
        '<:RED:>' => self::ESC . self::RED . self::CLOSE,
        '<:REDBOLD:>' => self::ESC . self::BOLD . ';'
            . self::RED . self::CLOSE,
        '<:GRN:>' => self::ESC . self::GRN . self::CLOSE,
        '<:GRNBOLD:>' => self::ESC . self::BOLD . ';'
            . self::GRN . self::CLOSE,
        '<:YEL:>' => self::ESC . self::YEL . self::CLOSE,
        '<:YELBOLD:>' => self::ESC . self::BOLD . ';'
            . self::YEL . self::CLOSE,
        '<:BLU:>' => self::ESC . self::BLU . self::CLOSE,
        '<:BLUBOLD:>' => self::ESC . self::BOLD . ';'
            . self::BLU . self::CLOSE,
        '<:MAG:>' => self::ESC . self::GRN . self::CLOSE,
        '<:MAGBOLD:>' => self::ESC . self::BOLD . ';'
            . self::MAG . self::CLOSE,
        '<:CYN:>' => self::CYN . self::RED . self::CLOSE,
        '<:CYNBOLD:>' => self::ESC . self::BOLD . ';'
            . self::CYN . self::CLOSE,
    ];

    private const RESET = '0';

    private function colorize(string $in): string {
        // reset the colors automaticall
        if (str_contains($in, '<:') && !str_contains($in, '<:END:>')) {
            $in .= '<:DEF:>';
        };


        return strtr($in, $this->colors);
    }

    public function heading(string $heading, bool $blankAfter = false): void {
        $this->writeln(
            $this->colorize('<:REDBOLD:>' . $heading . '<:DEF:>'),
        );
        if ($blankAfter) {
            $this->writeln('');
        }
    }

    protected function writeln(string ...$lines) {
        $this->out = array_merge($this->out, $lines);
    }

    protected function sprintf(...$params) {
        $repl = sprintf(...$params);
        $this->out[] = $this->colorize($repl);
    }

    public function output(): string
    {
        return implode(PHP_EOL, $this->out) . PHP_EOL;
    }
}