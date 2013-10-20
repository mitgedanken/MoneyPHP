<?php

/*
 * Copyright (C) 2013 Sascha Tasche <hallo@mitgedanken.de>
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
 * A monetary exception.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
class Exception extends \Exception implements MonetaryException {

  /**
   * Exception code.
   */
  const CODE = 0;

  /**
   * Constructs this exception.
   *
   * @param string $message [recommended] Exception message.
   * @param integer $code [recommended]  Exception code.
   * @param \Exception $previous [optional] Previous exception.
   */
  public function __construct($message = NULL, $code = NULL,
                              \Exception $previous = NULL)
  {
    $code = isset($code) ? $code : static::CODE;
    $message = $this->format($message);
    parent::__construct($message, $code, $previous);
  }

  /**
   * Formats the message string for an exception.
   *
   * @param string $causeMessage [recommended] A detailed message about the cause.
   */
  protected function format($causeMessage = NULL)
  {
    $message = 'A monetary exception, ';
    $message .= ' reason: ' . \trim($causeMessage);
    return $message;
  }
}
