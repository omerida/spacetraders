<?php

declare(strict_types=1);

namespace Phparch\SpaceTraders\Rector;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\PropertyHook;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Expr\Throw_;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name;
use PhpParser\Node\Arg;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Stmt\If_;
use Rector\Rector\AbstractRector;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use PhpParser\Node\Stmt\Class_;

class NonEmptyStringToPromotedPropertyHook extends AbstractRector
{
    public function __construct(
        protected readonly PhpDocInfoFactory $phpDocInfoFactory
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Convert readonly non-empty-string promoted props to hooked props',
            []
        );
    }

    public function getNodeTypes(): array
    {
        return [Param::class];
    }

    /** @param Param $node */
    public function refactor(Node $node): ?Node
    {
        // 1. Must be a promoted property
        if ($node->flags === 0 || $node->type === null) {
            return null;
        }

        // 2. Parse DocBlock
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        $varTag = $phpDocInfo->getVarTagValueNode();
        if ($varTag === null || (string) $varTag->type !== 'non-empty-string') {
            return null;
        }

        // 3. Remove readonly (Incompatible with hooks)
        $node->flags &= ~Class_::MODIFIER_READONLY;

        $varName = $this->getName($node->var);
        if ($varName === null) {
            return null;
        }

        // 4. Define the Hook
        $node->hooks = [
            new PropertyHook('set', [
                new If_(
                    new Empty_(
                        new FuncCall(new Name('trim'), [
                            new Arg(new Variable('value'))
                        ])
                    ),
                    ['stmts' => [
                        new Expression(
                            new Throw_(
                                new New_(new Name('\InvalidArgumentException'), [
                                    new Arg(
                                        new String_("$varName cannot be empty")
                                    )
                                ])
                            )
                        )
                    ]]
                ),
                new Expression(
                    new Assign(
                        new PropertyFetch(new Variable('this'), $varName),
                        new Variable('value')
                    )
                )
            ])
        ];

        return $node;
    }
}
