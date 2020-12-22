<?php

namespace App\Tests\Core\Interaction\Action;

use App\Core\Interaction\Action\FormulaAction;
use App\Tests\Utils\GameContextFactory;
use MathParser\Parsing\Nodes\ExpressionNode;
use MathParser\Parsing\Nodes\IntegerNode;
use MathParser\Parsing\Nodes\VariableNode;
use PHPUnit\Framework\TestCase;

class FormulaActionTest extends TestCase
{

    public function testSimpleInteger()
    {
        $formulaAction = new FormulaAction('lol', new IntegerNode(123));
        $gameObject = GameContextFactory::createGameContext([
            ['name' => 'lol', 'value_int' => 0]
        ]);
        $formulaAction->execute($gameObject);
        $expected = GameContextFactory::createGameContext([
            ['name' => 'lol', 'value_int' => 123]
        ]);
        $this->assertEquals($expected->getContext(), $gameObject->getContext());
    }

    public function testFormulaWithVar()
    {
        $formulaAction = new FormulaAction('lol', new ExpressionNode(
            new IntegerNode(1),
            '+',
            new VariableNode('my_var')
        ));
        $gameObject = GameContextFactory::createGameContext([
            ['name' => 'lol', 'value_int' => 0],
            ['name' => 'my_var', 'value_int' => 9],
        ]);
        $formulaAction->execute($gameObject);
        $expected = GameContextFactory::createGameContext([
            ['name' => 'lol', 'value_int' => 10],
            ['name' => 'my_var', 'value_int' => 9]
        ]);
        $this->assertEquals($expected->getContext(), $gameObject->getContext());
    }
}