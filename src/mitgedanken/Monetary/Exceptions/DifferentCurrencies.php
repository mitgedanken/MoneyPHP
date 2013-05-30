<?php

/*
 * Copyright (C) 2013 Sascha Tasche <sascha@mitgedanken.de>
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
 * DifferentCurrencies.
 *
 * @author Sascha Tasche <sascha@mitgedanken.de>
 */
class DifferentCurrencies extends LogicException {

  /**
   * Exception code.
   */
  const CODE = 1401;

  /**
   * Formats the message string for this exception.
   *
   * @param string $causeMessage
   */
  protected function format($causeMessage = NULL)
  {
    $message = 'Different currencies';
    $causeMessage = \trim($causeMessage);
    if (empty($causeMessage)):
      $message .= '.';
    else:
      $message .= ', caused by: ' . \trim($causeMessage);
    endif;
    return $message;
  }
}
