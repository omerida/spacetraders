<?php

declare(strict_types=1);

namespace Phparch\SpaceTraders\PHPStan;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassMethodNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;
use Psr\Http\Message\ResponseInterface;
use Phparch\SpaceTraders\Attribute\Route;

/**
 * @implements Rule<InClassMethodNode>
 */
final class ControllerMethodSignatureRule implements Rule
{
    public function getNodeType(): string
    {
        return InClassMethodNode::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $methodReflection = $node->getMethodReflection();
        $classReflection = $node->getClassReflection();
        $methodName = $methodReflection->getName();

        if (
            !str_starts_with(
                $classReflection->getName(),
                'Phparch\SpaceTraders\Controller\\'
            )
        ) {
            return [];
        }

        if (
            !$methodReflection->isPublic()
            || $methodName === '__construct'
        ) {
            return [];
        }

        // 1. SKIP IF INHERITED OR OVERRIDING:
        // We check if the method exists in any parent class or interface.
        // If it does, we assume it follows a different contract.
        foreach ($classReflection->getAncestors() as $ancestor) {
            if ($ancestor->getName() === $classReflection->getName()) {
                continue; // Skip the class itself
            }
            if ($ancestor->hasMethod($methodName)) {
                return [];
            }
        }

        // 2. SKIP IF FROM A TRAIT:
        if ($methodReflection->getDeclaringClass()->isTrait()) {
            return [];
        }

        $errors = [];
        $className = $classReflection->getDisplayName();

        // 3. Signature Validation (No Arguments)
        foreach ($methodReflection->getVariants() as $variant) {
            if (count($variant->getParameters()) > 0) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'Controller action %s::%s() must not accept arguments.',
                        $className,
                        $methodName
                    )
                )
                    ->identifier('spacetraders.controller.noArguments')
                    ->build();
                break;
            }
        }

        // 4. Attribute-based Return Type Logic
        $returnType = $methodReflection->getVariants()[0]->getReturnType();
        $isApplicationStrategy = false;

        // PHPStan 2.x: Safely check attributes via the ReflectionProvider
        $nativeClass = $classReflection->getNativeReflection();
        if ($nativeClass->hasMethod($methodName)) {
            $routeAttributes = $nativeClass->getMethod($methodName)->getAttributes(Route::class);
            if (count($routeAttributes) > 0) {
                $args = $routeAttributes[0]->getArguments();
                if (($args['strategy'] ?? $args[0] ?? null) === 'application') {
                    $isApplicationStrategy = true;
                }
            }
        }

        if ($isApplicationStrategy) {
            $expected = new ObjectType(ResponseInterface::class);
            if (
                !$expected->accepts(
                    $returnType,
                    $scope->isDeclareStrictTypes()
                )->yes()
            ) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'Action %s::%s() must return %s.',
                        $className,
                        $methodName,
                        ResponseInterface::class
                    )
                )
                    ->identifier('spacetraders.controller.invalidResponseReturn')
                    ->build();
            }
        } else {
            if (!$returnType->isArray()->yes()) {
                $errors[] = RuleErrorBuilder::message(
                    sprintf(
                        'Action %s::%s() must return an array or use application strategy.',
                        $className,
                        $methodName
                    )
                )
                    ->identifier(
                        'spacetraders.controller.invalidArrayReturn'
                    )
                    ->build();
            }
        }

        return $errors;
    }
}
