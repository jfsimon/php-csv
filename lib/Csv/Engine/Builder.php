<?php

namespace Csv\Engine;

use Csv\Transformer\TransformerInterface;

/**
 * Row builder.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class Builder
{
    /**
     * @var string
     */
    private $separator;

    /**
     * @var Enclosure|null
     */
    private $enclosure;

    /**
     * Constructor.
     *
     * @param string         $separator
     * @param Enclosure|null $enclosure
     */
    public function __construct($separator, Enclosure $enclosure = null)
    {
        $this->separator = $separator;
        $this->enclosure = $enclosure;
    }

    /**
     * Builds row with given values.
     *
     * @param array $values         Row values
     * @param bool  $forceEnclosure Force enclosure
     *
     * @return string
     */
    public function build(array $values, $forceEnclosure = false)
    {
        $separator = $this->separator;
        if (null !== $enclosure = $this->enclosure) {
            $values = array_map(function ($value) use ($enclosure, $separator, $forceEnclosure) {
                return $enclosure->enclose($value, array($separator), $forceEnclosure);
            }, $values);
        }

        return implode($separator, $values);
    }
}
