<?php

namespace Phparch\SpaceTradersCLI\Command\Help;

use Minicli\Command\CommandController;
use Phparch\SpaceTraders\Client;
use Phparch\SpaceTraders\ServiceContainer;
use Phparch\SpaceTraders\Trait\TerminalOutputHelper;
use Phparch\SpaceTradersCLI\Command\HelpInfo;

#[HelpInfo(description: "Show registered commands")]
class DefaultController extends CommandController
{
    use TerminalOutputHelper;

    private const BASE_NS = '\\Phparch\\SpaceTradersCLI\\';

    public function handle(): void
    {
        $controllers = $this->findControllers();
        $grouped = [];
        foreach ($controllers as $file) {
            try {
                $classname = $this->getClassName($file);

                $detail = $this->buildCommandDetails($classname);

                preg_match('/^(\w+)\s/', $detail, $match);
                if (isset($match[1])) {
                    $ns = $match[1];
                    $grouped[$ns][] = $detail;
                } else {
                    trigger_error('Could not determine namespace for ' . $detail, E_USER_WARNING);
                }
            } catch (\RuntimeException $ex) {
                $this->error($ex->getMessage());
            }
        }

        ksort($grouped);
        foreach ($grouped as $group => $commands) {
            $this->out($group, 'success_alt');
            $this->newline();
            foreach ($commands as $command) {
                $lines = explode(PHP_EOL, $command);
                if ($subc = preg_replace("/^({$group}\s+)/", '   ', $lines[0])) {
                    $this->out(sprintf('   %-35s', trim($subc)), 'info');
                    $this->out($lines[1]);
                    $this->newline();
                }
            }
        }
    }

    /**
     * @return \SplFileInfo[]
     */
    private function findControllers(): array
    {
        $commandRoot = new \RecursiveDirectoryIterator(__DIR__ . DIRECTORY_SEPARATOR . '..');
        $controllers = [];
        /** @var \SplFileInfo $file */
        foreach (new \RecursiveIteratorIterator($commandRoot) as $file) {
            if (str_ends_with($file->getBasename(), 'Controller.php')) {
                $controllers[] = $file;
            }
        }
        return $controllers;
    }

    /**
     * Transform the filename into a fully-qualified classname (FQCN).
     * @return class-string<CommandController>
     */
    private function getClassName(\SplFileInfo $file): string
    {
        // Keep dirs from the CLI root forward and drop file extension
        if (preg_match('|SpaceTraders/cli/(.+)\.php$|', $file->getRealPath(), $match)) {
            /** @var class-string<CommandController> */
            return self::BASE_NS . str_replace('/', '\\', $match[1]);
        }
        throw new \RuntimeException("Could not find FQCN for " . $file->getRealPath());
    }

    /**
     * @param class-string<CommandController> $classname
     */
    private function getCommand(string $classname): string
    {
        $ns = str_replace('\\', '\\\\', self::BASE_NS);
        // remove common namespace at beginning
        $command = preg_replace('|^' . $ns . '\\Command|', '', $classname);

        if ($command === null) {
            throw new \RuntimeException("Unexpected classname: . $classname");
        }

        // remove "Controller" suffix and trailing slash
        $command = preg_replace('|Controller$|', '', $command);
        if ($command === null) {
            throw new \RuntimeException("Unexpected classname: . $classname");
        }

        $command = str_replace('\\', ' ', $command);

        return strtolower(trim($command));
    }

    /**
     * @param class-string $classname
     * @throws \ReflectionException
     */
    private function getHelpInfo(string $classname): HelpInfo
    {
        $reflection = new \ReflectionClass($classname);

        $attributes = $reflection->getAttributes(HelpInfo::class);

        if ($attributes) {
            $instance = $attributes[0]->newInstance();

            if (
                !$instance->params
                && (method_exists($instance, 'required'))
            ) {
                $obj = new $classname();

                if (method_exists($obj, 'required')) {
                    $instance->required = $obj->required();
                }
            }

            return $instance;
        }

        throw new \RuntimeException("No help info found for $classname");
    }

    /**
     * @param class-string<CommandController> $classname
     * @throws \ReflectionException
     */
    private function buildCommandDetails(string $classname): string
    {
        $command = $this->getCommand($classname);
        $info = $this->getHelpInfo($classname);

        if ($info->required) {
            $required = array_map(fn($p) => "{$p}=value", $info->required);
            $command .= ' ' . implode(" ", $required);
        }

        if ($info->params) {
            $params = array_map(fn($p) => "<{$p}>", $info->params);
            $command .= " " . implode(" ", $params);
        }

        return "$command\n    {$info->description}";
    }
}
