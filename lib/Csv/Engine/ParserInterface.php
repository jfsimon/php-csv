<?php

namespace Csv\Engine;

use Csv\Engine\Parser\State;
use Csv\Exception\ParsingFinishedException;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
interface ParserInterface
{
    /**
     * Parses next row.
     *
     * @param State $state
     *
     * @return array Parsed row
     *
     * @throws ParsingFinishedException
     */
    public function parse(State $state);
}
