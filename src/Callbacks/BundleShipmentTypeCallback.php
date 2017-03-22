<?php

/**
 * TechDivision\Import\Product\Bundle\Callbacks\BundleShipmentTypeCallback
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Bundle\Callbacks;

use TechDivision\Import\Product\Bundle\Utils\ShipmentTypes;
use TechDivision\Import\Product\Callbacks\AbstractProductImportCallback;

/**
 * A SLSB that handles the process to import product bunches.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class BundleShipmentTypeCallback extends AbstractProductImportCallback
{

    /**
     * The array with the available shipment types.
     *
     * @var array
     */
    protected $availableShipmentTypes = array(
        'separately' => ShipmentTypes::SEPARATELY,
        'together'   => ShipmentTypes::TOGETHER
    );

    /**
     * Will be invoked by a observer it has been registered for.
     *
     * @param string $attributeCode  The code of the attribute the passed value is for
     * @param mixed  $attributeValue The value to handle
     *
     * @return mixed|null The modified value
     * @see \TechDivision\Import\Callbacks\CallbackInterface::handle()
     */
    public function handle($attributeCode, $attributeValue)
    {

        // query whether or not, the requested shipment type is available
        if (isset($this->availableShipmentTypes[$value = strtolower($attributeValue)])) {
            return $this->availableShipmentTypes[$value];
        }

        // return the default shipment type
        return ShipmentTypes::TOGETHER;
    }
}
