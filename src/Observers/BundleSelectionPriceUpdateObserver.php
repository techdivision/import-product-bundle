<?php

/**
 * TechDivision\Import\Product\Bundle\Observers\BundleSelectionPriceUpdateObserver
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

namespace TechDivision\Import\Product\Bundle\Observers;

use TechDivision\Import\Utils\StoreViewCodes;
use TechDivision\Import\Product\Bundle\Utils\ColumnKeys;
use TechDivision\Import\Product\Bundle\Utils\MemberNames;

/**
 * Oberserver that provides functionality for the bundle selection price add/update operation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class BundleSelectionPriceUpdateObserver extends BundleSelectionPriceObserver
{

    /**
     * Initialize the bundle selection price with the passed attributes and returns an instance.
     *
     * @param array $attr The bundle selection price attributes
     *
     * @return array The initialized bundle selection price
     */
    protected function initializeBundleSelectionPrice(array $attr)
    {

        // load the store view code
        $storeViewCode = $this->getStoreViewCode(StoreViewCodes::ADMIN);

        // load the store/website ID
        $store = $this->getStoreByStoreCode($storeViewCode);
        $websiteId = $store[MemberNames::WEBSITE_ID];

        // load the selection ID for the child SKU
        $selectionId = $this->getChildSkuSelectionMapping($this->getValue(ColumnKeys::BUNDLE_VALUE_SKU));

        // try to load the bundle selection price with the passed selection/website ID
        if ($entity = $this->loadBundleSelectionPrice($selectionId, $websiteId)) {
            return $this->mergeEntity($entity, $attr);
        }

        // simply return the attributes
        return $attr;
    }

    /**
     * Load's the bundle selection price with the passed selection/website ID.
     *
     * @param integer $selectionId The selection ID of the bundle selection price to be returned
     * @param integer $websiteId   The website ID of the bundle selection price to be returned
     *
     * @return array The bundle selection price
     */
    protected function loadBundleSelectionPrice($selectionId, $websiteId)
    {
        return $this->getSubject()->loadBundleSelectionPrice($selectionId, $websiteId);
    }
}
