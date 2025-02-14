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
    /** @var array<string, string>  */
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

    public function black(string $input): string
    {
        return $this->colorize('<:BLACK:>' . $input . '<:DEF:>');
    }
    public function red(string $input): string
    {
        return $this->colorize('<:RED:>' . $input . '<:DEF:>');
    }
    public function green(string $input): string
    {
        return $this->colorize('<:GRN:>' . $input . '<:DEF:>');
    }
    public function blue(string $input): string
    {
        return $this->colorize('<:BLU:>' . $input . '<:DEF:>');
    }
    public function magenta(string $input): string
    {
        return $this->colorize('<:MAG:>' . $input . '<:DEF:>');
    }
    public function yellow(string $input): string
    {
        return $this->colorize('<:YEL:>' . $input . '<:DEF:>');
    }
    public function cyan(string $input): string
    {
        return $this->colorize('<:CYN:>' . $input . '<:DEF:>');
    }
    private function colorize(string $input): string
    {
        // reset the colors automaticall
        if (str_contains($input, '<:') && !str_contains($input, '<:END:>')) {
            $input .= '<:DEF:>';
        }

        return strtr($input, $this->colors);
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

    public function passthru(RenderInterface $input): void
    {
        $this->out[] = $input->output();
    }

    protected function writeln(string ...$lines): void
    {
        array_walk($lines, fn($line) => $this->colorize($line));

        $this->out = array_merge($this->out, $lines);
    }

    protected function divider(
        string $char = '=',
        int $width = 100,
        string $color = '<:RED:>'
    ): void {
        $this->out[] = $this->colorize($color . str_repeat($char, $width));
    }

    /**
     * @param array<int|string|float> ...$params
     */
    protected function sprintf(...$params): void
    {
        /** @phpstan-ignore-next-line */
        $repl = sprintf(...$params);
        $this->out[] = $this->colorize($repl);
    }

    public function output(): string
    {
        return implode(PHP_EOL, $this->out) . PHP_EOL;
    }

    /**
     * @return string[]
     */
    public function getOutput(): array
    {
        return $this->out;
    }

    public function formatDate(\DateTimeInterface $when): string
    {
        return $when->format(DATE_COOKIE);
    }
}
