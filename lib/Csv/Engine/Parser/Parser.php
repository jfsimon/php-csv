<?php

namespace Csv\Engine\Parser;

use Csv\Engine\Tokenizer\TokenIterator;
use Csv\Engine\Tokenizer\Token;
use Csv\Exception\ParsingFinishedException;

/**
 * Parser class.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class Parser
{
    /**
     * @var TokenIterator
     */
    private $tokens;

    /**
     * @param TokenIterator $tokens
     */
    public function __construct(TokenIterator $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * Parses row.
     *
     * @param State $state
     *
     * @return array Parsed row
     *
     * @throws ParsingFinishedException
     */
    public function parse(State $state)
    {
        /** @var Token $token */
        foreach ($this->tokens as $token) {
            if ($token->is(Token::ENCLOSURE_ESCAPE)) {
                $state->setEscaped(true);
            }

            if ($state->isEnclosed()) {
                if ($token->is(Token::ENCLOSURE_END)) {
                    $state->setEnclosed(false);
                } else {
                    $state->addContent($token->getContent());
                }
            } else {
                if ($token->is(Token::ENCLOSURE_START && !$state->isEscaped())) {
                    $state->setEnclosed(true);
                } elseif ($token->is(Token::SEPARATOR)) {
                    $state->nextCell();
                } elseif ($token->is(Token::LINE_BREAK)) {
                    return $state->fetchRow();
                } else {
                    $state->addContent($token->getContent());
                }
            }

            $state->setEscaped(false);
        }

        return $state->fetchRow();
    }
}
