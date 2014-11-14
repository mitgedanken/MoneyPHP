<?php

// version: 0.0.1-alpha+2714m0
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

namespace mitgedanken\Monetary\Exceptions;

use mitgedanken\Monetary\Exceptions\Monetary;

/**
 * A monetary exception.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
class Exception extends \Exception implements Monetary {

    /**
     * Exception code.
     *
     * @var integer
     */
    const CODE = 2;

    /**
     * Constructs this exception.
     *
     * @param string $message [recommended] Exception message.
     * @param integer $code [recommended]  Exception code.
     * @param \Exception $previous [optional] Previous exception.
     */
    public function __construct($message = NULL, $code = NULL, \Exception $previous = NULL) {
        $code = isset($code) ? $code : static::CODE;
        $message = $this->format($message);
        parent::__construct($message, $code, $previous);
    }

    public function identify() {
        return \get_class() . ' ERROR CODE: ' . static::CODE;
    }

    public static function getVersion() {
        return '0.0.1-alpha+2714m0';
    }

    /**
     *
     * @param mixed $thing
     * @return string
     */
    public static function was($thing) {
        if (\is_object($thing)):
            $was = \get_class($thing);
        elseif (\is_resource($thing)):
            $was = \get_resource_type($thing);
        else:
            $value = $thing;
            if (FALSE === $thing):
                $value = '0';
            endif;
            $was = \gettype($thing) . "($value)";
        endif;
        return ' was: ' . $was;
    }

    public static function methods($class) {
        assert(\is_string($class), '$class is a string');
        assert(\is_array(\get_class_methods($class)), '\get_class_methods($class) returns an array');
        $methods = "\nPublic methods of $class are ...\n";
        foreach (\get_class_methods($class) as $value):
            $methods .= "$value\n";
        endforeach;
        return $methods;
    }

    /**
     * Formats the message string for this exception.
     *
     * @param string $causeMessage
     */
    protected function format($causeMessage = NULL) {
        assert(\is_string($causeMessage) || \is_null($causeMessage), '$causeMessage is a string');
        return 'Exception' . static::reasonIfKnown($causeMessage);
    }

    /**
     *
     * @param string $causeMessage
     * @return string
     */
    protected static function reasonIfKnown($causeMessage) {
        assert(\is_string($causeMessage), '$causeMessage is a string');
        if (\is_null($causeMessage)):
            $message .= '.';
        else:
            $message .= ', reason: ' . \trim($causeMessage);
        endif;
        return $message;
    }

}
