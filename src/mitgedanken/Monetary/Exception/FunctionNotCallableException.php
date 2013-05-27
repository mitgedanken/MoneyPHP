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
 * FunctionNotCallableException.
 *
 * @author Sascha Tasche <sascha@mitgedanken.de>
 */
class FunctionNotCallableException extends \BadFunctionCallException {
  /**
   * Exception code.
   */
  const CODE = 1404;

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
    $code = isset($code) ? $code : self::CODE;
    if (isset($this->message)):
      $message = $this->message;
    else:
      $message = isset($message) ? 'Function is not callable' : $message;
    endif;
    parent::__construct($this->format($message), $code, $previous);
  }

  /**
   * Formats the message String for this Exception.
   *
   * @param string $function
   */
  public static function format($function, $additional = NULL)
  {
    $this->message = "Function $function is not callable";
    if (isset($additional)):
      $this->message = $this->message . ". $additional";
    endif;
  }

}
