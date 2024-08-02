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

    const BASE_NS = '\\Phparch\\SpaceTradersCLI\\';

    public function handle(): void
    {
        $controllers = $this->findControllers();

        foreach ($controllers as $file) {
            try {
                $classname = $this->getClassName($file);
                $command = $this->getCommand($classname);
                $info = $this->getHelpInfo($classname);

                if ($info->params) {
                    $params = array_map(fn($p) => "<{$p}>", $info->params);
                    $command .= " " . implode(" ", $params);
                }
                $this->display("$command\n    {$info->description}");

            } catch (\RuntimeException $ex) {
                $this->error($ex->getMessage());
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
        foreach (new \RecursiveIteratorIterator($commandRoot) as $file) {
            if (str_ends_with($file, 'Controller.php')) {
                $controllers[] = $file;
            }
        }
        return $controllers;
    }

    private function getClassName(\SplFileInfo $file): string
    {
        // transform the filename into a fully-qualified classname (FQCN)
        $path = realpath($file->getRealPath());
        // keep from the CLI root forward and drop the extension
        if (preg_match('|SpaceTraders/cli/(.+)\.php|', $file->getRealPath(), $match)) {
            return self::BASE_NS . str_replace('/', '\\', $match[1]);
        }
        throw new \RuntimeException("Could not find FQCN for $path");
    }

    /**
     * @param class-string $classname
     */
    private function getCommand(string $classname): string {

        $ns = str_replace('\\', '\\\\', self::BASE_NS);
        // remove common namespace at beginning
        $command = preg_replace('|^' . $ns . '\\Command|', '', $classname);
        // remove "Controller" suffix
        $command = preg_replace('|Controller$|', '', $command);
        $command = str_replace('\\', ' ', $command);
        // add space before camelcase
        $command = preg_replace('|([a-z])([A-Z])|', '$1 $2', $command);

        return trim(strtolower($command));
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
            return $attributes[0]->newInstance();
        }

        throw new \RuntimeException("No help info found for $classname");
    }
}