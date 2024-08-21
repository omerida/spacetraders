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

                $output = $this->buildCommandDetails($classname);

                $this->display($output);

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

    /**
     * Transform the filename into a fully-qualified classname (FQCN).
     * @return class-string
     */
    private function getClassName(\SplFileInfo $file): string
    {
        // Keep dirs from the CLI root forward and drop file extension
        if (preg_match('|SpaceTraders/cli/(.+)\.php$|', $file->getRealPath(), $match)) {
            return self::BASE_NS . str_replace('/', '\\', $match[1]);
        }
        throw new \RuntimeException("Could not find FQCN for " . $file->getRealPath());
    }

    /**
     * @param class-string $classname
     */
    private function getCommand(string $classname): string
    {
        $ns = str_replace('\\', '\\\\', self::BASE_NS);
        // remove common namespace at beginning
        $command = preg_replace('|^' . $ns . '\\Command|', '', $classname);
        // remove "Controller" suffix and trailing slash
        $command = preg_replace('|Controller$|', '', $command);
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
                $instance->required = $obj->required();
            }

            return $instance;
        }

        throw new \RuntimeException("No help info found for $classname");
    }

    /**
     * @param class-string $classname
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