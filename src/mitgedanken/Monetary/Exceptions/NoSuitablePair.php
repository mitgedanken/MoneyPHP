<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace mitgedanken\Monetary\Exceptions;

/**
 * NoSuitablePair.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
class NoSuitablePair extends Logic
{

  /**
   * Exception code.
   */
  const CODE = 1402;

  /**
   * Formats the message string for this exception.
   *
   * @param string $causeMessage
   */
  protected function format($causeMessage = NULL)
  {
    $message      = 'No suitable pair was found';
    $causeMessage = \trim($causeMessage);
    if (empty($causeMessage)):
      $message .= '.';
    else:
      $message .= ', reason: ' . \trim($causeMessage);
    endif;
    return $message;
  }

}
