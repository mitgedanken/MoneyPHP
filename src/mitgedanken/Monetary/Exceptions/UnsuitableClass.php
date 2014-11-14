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

namespace mitgedanken\Monetary\Exceptions;

/**
 * Thrown if a given class is not a expected one
 * or does not declare a important method.<br>
 *
 * @author Sascha Tasche <hallo@mitgedanken>
 */
class UnsuitableClass extends UnexpectedType {

    /**
     * Exception code.
     */
    const CODE = 605;

    /**
     * Formats the message string for this exception.
     *
     * @param string $causeMessage
     */
    protected function format($causeMessage = NULL) {
        assert(\is_string($causeMessage), '$causeMessage is a string');
        $message = 'Unexpected class';
        if (\is_null($causeMessage)):
            $message .= '.';
        else:
            $message .= ', reason: ' . \trim($causeMessage);
        endif;
        return $message;
    }

}
