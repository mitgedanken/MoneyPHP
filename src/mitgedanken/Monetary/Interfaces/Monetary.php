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

namespace mitgedanken\Monetary\Interfaces;

/**
 * Global interface for all Monetary classes.<br>
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
interface Monetary {

    const PROJECT_GLOBAL_VERSION = '0.0.1-alpha+2714m0';

    /**
     * Indicates whether the object is "equal to" another.<br/>
     * If a class does not implement its own equal(..) method, than the object is
     * "equal to" another if both are the same.<br>
     *
     * @param mixed $object
     * @return boolean
     */
    function equals($object);

    /**
     * Return its identifier.
     *
     * @return string
     */
    function identify();

    /**
     * The __toString() method allows a class to decide how it will react when it
     * is treated like a string. For example, what echo $obj; will print.<br>
     * This method must return a string, as otherwise a fatal E_RECOVERABLE_ERROR
     * level error is emitted.<br>
     *
     * @return string Its representation.
     */
    function __toString();

    /**
     * It returns the class version, which can be different to the global version.
     *
     * @return string
     */
    static function version();
}
