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

/**
 * <i>Immutable</i><br/>
 * Money container.
 *
 * @author Sascha Tasche <hallo@mitgedanken.de>
 */
class SlenderMoney implements Interfaces\Monetary {

    use \mitgedanken\Monetary\Traits\Monetary;

    /**
     * Its amount.
     *
     * @var integer
     */
    protected $amount;

    /**
     * Its currency.
     *
     * @var Currency
     */
    protected $currency;

    /**
     * Return its amount.<br/>
     *
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Return its currency.<br/>
     *
     * @return \mitgedanken\Monetary\Currency
     */
    public function getCurrency()
    {
        return new Currency($this->currency);
    }

    /**
     * Constructs this <i>Interfaces\Money</i> object.
     *
     * @param integer $amount Its amount.
     * @param \mitgedanken\Monetary\Currency $currency Its currency.
     * @throws InvalidArgument
     */
    public function __construct($amount, Currency $currency)
    {
        $this->currency = "$currency";
        $this->amount = $amount;
    }

    /** TODO
     *
     * @throws Exceptions\UnsupportedOperation
     */
    public function __clone()
    {
        throw new Exceptions\UnsupportedOperation('__clone not supported');
    }

    /** TODO
     *
     * @param Interfaces\Money $money
     * @return type
     */
    public static function slenderize(Interfaces\Money $money)
    {
        return new SlenderMoney($money->getAmount(), $money->getCurrency());
    }

    /**
     * <i>
     *
     * It returns a storage with slenderized monies.
     *
     * @return \SplObjectStorage
     */
    public static function slenderizeStorage(\ArrayAccess $storage)
    {
        $slenderized = new \SplObjectStorage();
        foreach ($storage as $item):
            $slenderized->attach(SlenderMoney::slenderize($item));
        endforeach;
        return $slenderized;
    }

    public static function generateSlenderizedMonies(\ArrayAccess $storage)
    {
        foreach ($storage as $item):
            yield new SlenderMoney($item->getAmount, $item->getCurrency);
        endforeach;
    }

    /** TODO
     *
     * @param Interfaces\Money $object
     * @return type
     */
    public function equals($object)
    {
        $equals = FALSE;
        if ($object instanceof SlenderMoney):
            $equals = ($this->currency == $object->currency) && ($this->amount == $object->amount);
        endif;
        return $equals;
    }

    /**
     * Identifies instances of this object.
     *
     * @return type
     */
    public function identify()
    {
        return "$this->amount $this->currency";
    }

    /**
     * Return this <i>SlenderMoney</i> object as a string.
     *
     * @return string ("amount" "currency")
     * @see \mitgedanken\Monetary\Currency
     */
    public function __toString()
    {
        return \get_class() . '(' . $this->number->toString() . $this->currency . ')';
    }

}
