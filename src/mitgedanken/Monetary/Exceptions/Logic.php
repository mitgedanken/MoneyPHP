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

/**
 * LogicException.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
class Logic extends Exception {

    /**
     * Exception code.
     */
    const CODE = 1000;

    /**
     * Formats the message string for this exception.
     *
     * @param string $causeMessage
     */
    protected function format($causeMessage = NULL) {
        assert(\is_string($causeMessage) || \is_null($causeMessage), '$causeMessage is a string');
        return 'Logic exception' . self::reasonIfKnown($causeMessage);
    }

}
