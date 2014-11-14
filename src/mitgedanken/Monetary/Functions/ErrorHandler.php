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

/**
 * ErrorHandler<br>
 *
 */
$error_handler = function ($errno, $errstr, $errfile, $errline) {
    throw new mitgedanken\Monetary\Exceptions\Error($errstr, 0, $errno, $errfile, $errline);
};
set_error_handler($error_handler, E_ALL & ~E_NOTICE & ~E_USER_NOTICE);
