<?php

namespace App\Core\Interaction\FormulaLanguage;

use MathParser\Lexing\Lexer;
use MathParser\Lexing\TokenDefinition;
use MathParser\Lexing\TokenType;

class LexerFactory
{

    public function create(): Lexer
    {
        $lexer = new Lexer();
        $lexer->add(new TokenDefinition('/\d+[,\.]\d+(e[+-]?\d+)?/', TokenType::RealNumber));

        $lexer->add(new TokenDefinition('/\d+/', TokenType::PosInt));

        $lexer->add(new TokenDefinition('/\+/', TokenType::AdditionOperator));
        $lexer->add(new TokenDefinition('/\-/', TokenType::SubtractionOperator));
        $lexer->add(new TokenDefinition('/\*/', TokenType::MultiplicationOperator));
        $lexer->add(new TokenDefinition('/\//', TokenType::DivisionOperator));
        $lexer->add(new TokenDefinition('/\^/', TokenType::ExponentiationOperator));

        $lexer->add(new TokenDefinition('/$[a-zA-Z]/', TokenType::Identifier));

        $lexer->add(new TokenDefinition('/\n/', TokenType::Terminator));
        return $lexer;
    }
}