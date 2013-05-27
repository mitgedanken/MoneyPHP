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

namespace mitgedanken\Monetary\Exception;

/**
 * InvalidArgumentException.
 *
 * @author Sascha Tasche <sascha@mitgedanken.de>
 */
class InvalidArgumentException extends LogicException {
  /**
   * Exception code.
   */
  const CODE = 1400;

  /**
   * Formats the message string for this exception.
   *
   * @param string $causeMessage
   */
  protected function format($causeMessage = NULL)
  {
    $message = 'Invalid argument';
    if (!empty($causeMessage) && 0 < \strlen($message)):
      $causeMessage = \trim($causeMessage);
      $message .= '; caused by: ' . $causeMessage;
    endif;
    return $message .= '.';
  }

}
