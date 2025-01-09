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
    private const CYN = '36';
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
        '<:MAG:>' => self::ESC . self::MAG . self::CLOSE,
        '<:MAGBOLD:>' => self::ESC . self::BOLD . ';'
            . self::MAG . self::CLOSE,
        '<:CYN:>' => self::ESC . self::CYN . self::CLOSE,
        '<:CYNBOLD:>' => self::ESC . self::BOLD . ';'
            . self::CYN . self::CLOSE,
    ];

    private const RESET = '0';

    public function black(string $in): string
    {
        return $this->colorize('<:BLACK:>' . $in . '<:DEF:>');
    }
    public function red(string $in): string
    {
        return $this->colorize('<:RED:>' . $in . '<:DEF:>');
    }
    public function green(string $in): string
    {
        return $this->colorize('<:GRN:>' . $in . '<:DEF:>');
    }
    public function blue(string $in): string
    {
        return $this->colorize('<:BLU:>' . $in . '<:DEF:>');
    }
    public function magenta(string $in): string
    {
        return $this->colorize('<:MAG:>' . $in . '<:DEF:>');
    }
    public function yellow(string $in): string
    {
        return $this->colorize('<:YEL:>' . $in . '<:DEF:>');
    }
    public function cyan(string $in): string
    {
        return $this->colorize('<:CYN:>' . $in . '<:DEF:>');
    }
    private function colorize(string $in): string
    {
        // reset the colors automaticall
        if (str_contains($in, '<:') && !str_contains($in, '<:END:>')) {
            $in .= '<:DEF:>';
        }

        return strtr($in, $this->colors);
    }

    public function heading(string $heading, bool $blankAfter = false): void
    {
        $this->writeln(
            $this->colorize('<:REDBOLD:>' . $heading . '<:DEF:>'),
        );
        if ($blankAfter) {
            $this->writeln('');
        }
    }

    public function newline(): void
    {
        $this->writeln('');
    }

    public function passthru(RenderInterface $in): void
    {
        $this->out[] = $in->output();
    }

    protected function writeln(string ...$lines): void
    {
        array_walk($lines, fn($line) => $this->colorize($line));

        $this->out = array_merge($this->out, $lines);
    }

    protected function divider(string $c = '=', int $width = 100, string $color = '<:RED:>'): void
    {
        $this->out[] = $this->colorize($color . str_repeat($c, $width));
    }

    /**
     * @param array<string|int|float|callable-string> ...$params
     */
    protected function sprintf(...$params): void
    {
        $repl = sprintf(...$params);
        $this->out[] = $this->colorize($repl);
    }

    public function output(): string
    {
        return implode(PHP_EOL, $this->out) . PHP_EOL;
    }

    public function getOutput(): array
    {
        return $this->out;
    }

    public function formatDate(\DateTimeInterface $when): string
    {
        return $when->format(DATE_COOKIE);
    }
}
