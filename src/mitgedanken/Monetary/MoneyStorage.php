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

namespace mitgedanken\Monetary;

use mitgedanken\Monetary\Abstracts\Money as AbstractsMoney;
use Interfaces\MoneyStorage as StorageInterface;

/**
 * MonetaryStorage.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
class MoneyStorage extends \SplObjectStorage implements StorageInterface {

    use Traits\Monetary;

    /**
     * Calculate a unique identifier for the contained objects.<br/>
     *
     * @link http://php.net/manual/en/splobjectstorage.gethash.php
     * @param object $object The object whose identifier is to be calculated.
     * @return string A string with the calculated identifier.
     */
    public function getHash($object)
    {
        assert($object instanceof AbstractsMoney);
        return $object->identify();
    }

}
