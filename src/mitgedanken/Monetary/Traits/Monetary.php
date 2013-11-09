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

namespace mitgedanken\Monetary\Traits;

/**
 * Description of Component
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
trait Monetary {

  /**
   * Indicates whether this object is "equal to" another.</br>
   * This object is "equal to" another if they are the same object.
   *
   * @param mixed $object
   * @return boolean
   *    <i>TRUE</i> if this object is "equal to" parameter $object;
   *    <i>FALSE</i> otherwise.
   */
  public function equals($object) {
    return \is_object($object) && $this === $object;
  }

  /**
   * Return its identifier.
   *
   * @return string
   */
  public function identify() {
    return get_called_class();
  }

  /**
   * Returns its version number.
   */
  public function getVersion() {
    return "13.44.0-alpha";
  }

}
