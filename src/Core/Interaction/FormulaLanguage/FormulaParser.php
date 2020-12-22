<?php

namespace App\Core\Interaction\FormulaLanguage;

use MathParser\Lexing\Lexer;
use MathParser\Parsing\Nodes\Node;
use MathParser\Parsing\Parser;

class FormulaParser
{
    private Lexer $lexer;
    private Parser $parser;

    public function parse(string $input): Node
    {
        $tokens = $this->lexer->tokenize($input);
        return $this->parser->parse($tokens);
    }
}