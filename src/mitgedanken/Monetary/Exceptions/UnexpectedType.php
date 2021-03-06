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
 * Thrown if a value of a backed object is not in an expected type.<br>
 */
class UnexpectedType extends Runtime {

    /**
     * Exception code.
     */
    const CODE = 505;

    /**
     * Formats the message string for this exception.
     *
     * @param string $causeMessage
     */
    protected function format($causeMessage = NULL) {
        assert(\is_string($causeMessage), '$causeMessage is a string');
        $message = 'Unexpected type';
        if (\is_null($causeMessage)):
            $message .= '.';
        else:
            $message .= ', reason: ' . \trim($causeMessage);
        endif;
        return $message;
    }

}
