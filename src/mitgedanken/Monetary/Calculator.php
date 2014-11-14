<?php

/*
 * Copyright (C) 2014 Sascha Tasche <hallo@mitgedanken.de>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace mitgedanken\Monetary;

/**
 * Calculator.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
class Calculator implements Interfaces\Calculator {

    use Traits\Monetary;

    /** Round halves up. */
    const HALF_UP = \PHP_ROUND_HALF_UP;

    /** Round halves down. */
    const HALF_DOWN = \PHP_ROUND_HALF_DOWN;

    /** Round halves to even numbers. */
    const HALF_EVEN = \PHP_ROUND_HALF_EVEN;

    /** Round halves to odd numbers. */
    const HALF_ODD = \PHP_ROUND_HALF_ODD;

    /**
     * Holds the scale.
     * @var integer
     */
    protected $scale;

    /**
     * Constructor.<br>
     * If an algorithm is not set, it will be set a default callback.<br>
     * Same for converter.<br>
     * @throws InvalidArgument
     *         If and only if argument is not a supported type.
     */
    public function __construct($scale = 3)
    {
        if (!\is_integer($scale)):
            throw new InvalidArgument('Argument 1 is not an integer')
            . InvalidArgument::was($scale);
        endif;
        $this->scale = $scale;
    }

    /**
     * Adds numbers.<br>
     * <b>Notice</b>: It accepts BigNumber, too.<br>
     *
     * @param numeric $left_operand
     * @param numeric $right_operand
     * @param array $options [optional]
     * @return numeric
     * @throws InvalidArgument
     *         If and only if argument 1 or argument 2 is not a supported type.
     */
    public function add($left_operand, $right_operand)
    {
        if (!\is_numeric($left_operand)):
            throw new InvalidArgument('Argument 1 is not an integer')
            . InvalidArgument::was($left_operand);
        endif;
        if (!\is_numeric($right_operand)):
            throw new InvalidArgument('Argument 2 is not an integer')
            . InvalidArgument::was($right_operand);
        endif;
        $result = \bcadd((string) $left_operand, (string) $right_operand, $this->scale);
        assert(\is_string($result), 'Returns a string');
        return $result;
    }

    /**
     * Substract numbers.<br>
     * <b>Notice</b>: It accepts BigNumber, too.<br>
     *
     * @param numeric $left_operand
     * @param numeric $right_operand
     * @param array $options [optional]
     * @return numeric
     * @throws InvalidArgument
     *         If and only if argument 1 or argument 2 is not a supported type.
     */
    public function subtract($left_operand, $right_operand)
    {
        if (!\is_numeric($left_operand)):
            throw new InvalidArgument('Argument 1 is not an integer')
            . InvalidArgument::was($left_operand);
        endif;
        if (!\is_numeric($right_operand)):
            throw new InvalidArgument('Argument 2 is not an integer')
            . InvalidArgument::was($right_operand);
        endif;
        $result = \bcsub((string) $left_operand, (string) $right_operand, $this->scale);
        assert(\is_string($result), 'Returns a string');
        return $result;
    }

    /**
     * Multiply numbers.<br>
     * <b>Notice</b>: It accepts BigNumber, too.<br>
     *
     * @param numeric $left_operand
     * @param numeric $right_operand
     * @param array $options [optional]
     * @return numeric
     * @throws InvalidArgument
     *         If and only if argument 1 or argument 2 is not a supported type.
     */
    public function multiply($left_operand, $right_operand)
    {
        if (!\is_numeric($left_operand)):
            throw new InvalidArgument('Argument 1 is not an integer')
            . InvalidArgument::was($left_operand);
        endif;
        if (!\is_numeric($right_operand)):
            throw new InvalidArgument('Argument 2 is not an integer')
            . InvalidArgument::was($right_operand);
        endif;
        $result = \bcmul((string) $left_operand, (string) $right_operand, $this->scale);
        assert(\is_string($result), 'Returns a string');
        return $result;
    }

    /**
     * Divides numbers.<br>
     * Implementors must catch 'Divison by zero'.<br>
     * <b>Notice</b>: It accepts BigNumber, too.<br>
     *
     * @param numeric $left_operand
     * @param numeric $right_operand
     * @param array $options [optional]
     * @return numeric
     * @throws InvalidArgument
     *         If and only if argument 1 or argument 2 is not a supported type.
     * @throws DivisionByZero If and only if value of divisor is 0.
     */
    public function divide($left_operand, $right_operand)
    {
        if (!\is_numeric($left_operand)):
            throw new InvalidArgument('Argument 1 is not an integer')
            . InvalidArgument::was($left_operand);
        endif;
        if (!\is_numeric($right_operand)):
            throw new InvalidArgument('Argument 2 is not an integer')
            . InvalidArgument::was($right_operand);
        endif;
        $result = \bcdiv((string) $left_operand, (string) $right_operand, $this->scale);
        assert(\is_string($result), 'Returns a string');
        return $result;
    }

    /**
     * Remainder of the division of numbers.<br>
     * <b>Notice</b>: It accepts BigNumber, too.<br>
     *
     * @param numeric $left_operand
     * @param numeric $right_operand
     * @param array $options [optional]
     * @return array an array, with the first element being [n/d]
     *        (the integer result of the division) and the
     *        second being (n - [n/d] * d) (the remainder of the division).
     * @throws InvalidArgument
     *         If and only if argument 1 or argument 2 is not a supported type.
     */
    public function getRemainder($left_operand, $right_operand)
    {
        if (!\is_numeric($left_operand)):
            throw new InvalidArgument('Argument 1 is not an integer')
            . InvalidArgument::was($left_operand);
        endif;
        if (!\is_numeric($right_operand)):
            throw new InvalidArgument('Argument 2 is not an integer')
            . InvalidArgument::was($right_operand);
        endif;
        $result = '' . \fmod($left_operand, $right_operand);
        assert(\is_string($result), 'Returns a string');
        return $result;
    }

    /**
     * <p><i>Extension</i></p>
     * Divides numbers and get quotient and remainder as array.<br>
     * <b>Notice</b>: It accepts BigNumber, too.<br>
     *
     * @param numeric $left_operand
     * @param numeric $right_operand
     * @param array $options [optional]
     * @return array [0 => (n/d), 1 => n - (n/d) * d].
     */
    public function getQuotientAndRemainder($left_operand, $right_operand)
    {
        if (!\is_numeric($left_operand)):
            throw new InvalidArgument('Argument 1 is not an integer')
            . InvalidArgument::was($left_operand);
        endif;
        if (!\is_numeric($right_operand)):
            throw new InvalidArgument('Argument 2 is not an integer')
            . InvalidArgument::was($right_operand);
        endif;
        $result_array = [];
        /* Why \intval(..) instead of \floor(..)?
         * With \floor(..) the result of -1.6 will be -2.
         * That's not what we wanted to have.
         */
        $result_array[0] = \intval($left_operand / $right_operand);
        $result_array[1] = fmod($left_operand, $right_operand);
        return $result_array;
    }

    /**
     * Find highest value.<br>
     * If the first and only parameter is an array, max() returns the highest
     * value in that array. If at least two parameters are provided, max()
     * returns the biggest of these values.<br>
     *
     * @param array_or_parameters $_
     * @return numeric
     */
    public function max($_ = NULL)
    {
        if (!\is_array($_)):
            $result = max(\func_get_args());
        else:
            $result = max($_);
        endif;
        return (string) $result;
    }

    /**
     * Find lowest value.<br>
     * If the first and only parameter is an array, max() returns the highest
     * value in that array. If at least two parameters are provided, max()
     * returns the biggest of these values.<br>
     *
     * @param array_or_arguments $_
     * @return numeric
     */
    public function min($_ = NULL)
    {
        if (!\is_array($_)):
            $result = min(\func_get_args());
        else:
            $result = min($_);
        endif;
        return (string) $result;
    }

    /**
     * <p><i>Internal use only</i></p>
     *
     * @param numeric $operand
     * @throws DivisionByZero
     */
    protected function _isDivisonByZero($operand)
    {
        if ('0' == $operand) {
            throw new DivisionByZero('Oh no! The world will blow up!');
        }
    }

    protected function _getValue(array $options, $key)
    {
        $result = NULL;
        if (0 < \count($options) && \is_numeric($options[$key])):
            $result = $options[$key];
        endif;
        return $result;
    }

}
