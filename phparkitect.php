<?php
declare(strict_types=1);

use Arkitect\ClassSet;
use Arkitect\CLI\Config;
use Arkitect\Expression\ForClasses\DependsOnlyOnTheseNamespaces;
use Arkitect\Expression\ForClasses\HaveNameMatching;
use Arkitect\Expression\ForClasses\IsA;
use Arkitect\Expression\ForClasses\IsInterface;
use Arkitect\Expression\ForClasses\IsTrait;
use Arkitect\Expression\ForClasses\ResideInOneOfTheseNamespaces;
use Arkitect\Rules\Rule;

return static function (Config $config): void {
    $classSet = ClassSet::fromDir(__DIR__.'/src');

    $rules = [];

    /* Controller Rules */
    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('Phparch\SpaceTraders\Controller'))
        ->should(new HaveNameMatching('*Controller'))
        ->because('we want uniform naming for controllers');

    /* API Client Rules */
    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('Phparch\SpaceTraders\Client'))
        ->should(new IsA('Phparch\SpaceTraders\Client'))
        ->because('we want to ensure clients behave consistently.');

    /* Interface Rules */
    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('Phparch\SpaceTraders\Interface'))
        ->should(new IsInterface())
        ->because('we want only intefaces in this namespace.');

    /* Middleware Rules */
    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('Phparch\SpaceTraders\Middleware'))
        ->should(new IsA('Psr\Http\Server\MiddlewareInterface'))
        ->because('we want middleware classes to implement the PSR interface.');


    /* Traits Rules */
    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('Phparch\SpaceTraders\Trait'))
        ->should(new IsTrait())
        ->because('we want to be sure that there are only traits in a specific namespace');

    /* Value Object Rules */
    $rules[] = Rule::allClasses()
        ->that(new ResideInOneOfTheseNamespaces('Phparch\SpaceTraders\Value'))
        ->should(new DependsOnlyOnTheseNamespaces(['Phparch\SpaceTraders\Value'], []))
        ->because('we want to protect our domain from external dependencies except for Ramsey\Uuid');


    $config->add($classSet, ...$rules);
};