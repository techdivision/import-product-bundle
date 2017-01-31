<?php

/**
 * TechDivision\Import\Product\Bundle\Observers\BundleSelectionPriceObserver
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
use TechDivision\Import\Product\Observers\AbstractProductImportObserver;

/**
 * Oberserver that provides functionality for the bundle selection price replace operation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class BundleSelectionPriceObserver extends AbstractProductImportObserver
{

    /**
     * Process the observer's business logic.
     *
     * @return array The processed row
     */
    protected function process()
    {

        // prepare the store view code
        $this->prepareStoreViewCode($this->getRow());

        // load the store view code
        $storeViewCode = $this->getStoreViewCode(StoreViewCodes::ADMIN);

        // check if we're in default store
        if (!$this->isDefaultStore($storeViewCode)) {
            // prepare and initialize the bundle selection price
            $productBundleSelectionPrice = $this->initializeBundleSelectionPrice($this->prepareAttributes());

            // persist the bundle selection price
            $this->persistProductBundleSelectionPrice($productBundleSelectionPrice);
        }
    }

    /**
     * Prepare the attributes of the entity that has to be persisted.
     *
     * @return array The prepared attributes
     */
    protected function prepareAttributes()
    {

        // load the store view code
        $storeViewCode = $this->getStoreViewCode(StoreViewCodes::ADMIN);

        // load the store/website ID
        $store = $this->getStoreByStoreCode($storeViewCode);
        $websiteId = $store[MemberNames::WEBSITE_ID];

        // load the default values
        $selectionPriceType = $this->mapPriceType($this->getValue(ColumnKeys::BUNDLE_VALUE_PRICE_TYPE));
        $selectionPriceValue = $this->getValue(ColumnKeys::BUNDLE_VALUE_PRICE);

        // load the selection ID for the child SKU
        $selectionId = $this->getChildSkuSelectionMapping($this->getValue(ColumnKeys::BUNDLE_VALUE_SKU));

        // return the prepared bundle selection price
        return $this->initializeEntity(
            array(
                MemberNames::SELECTION_ID          => $selectionId,
                MemberNames::WEBSITE_ID            => $websiteId,
                MemberNames::SELECTION_PRICE_TYPE  => $selectionPriceType,
                MemberNames::SELECTION_PRICE_VALUE => $selectionPriceValue
            )
        );
    }

    /**
     * Initialize the bundle selection price with the passed attributes and returns an instance.
     *
     * @param array $attr The bundle selection price attributes
     *
     * @return array The initialized bundle selection price
     */
    protected function initializeBundleSelectionPrice(array $attr)
    {
        return $attr;
    }

    /**
     * Return's the mapping for the passed price type.
     *
     * @param string $priceType The price type to map
     *
     * @return integer The mapped price type
     * @throws \Exception Is thrown, if the passed price type can't be mapped
     */
    protected function mapPriceType($priceType)
    {
        return $this->getSubject()->mapPriceType($priceType);
    }

    /**
     * Return's the selection ID for the passed child SKU.
     *
     * @param string $childSku The child SKU to return the selection ID for
     *
     * @return integer The last created selection ID
     */
    protected function getChildSkuSelectionMapping($childSku)
    {
        return $this->getSubject()->getChildSkuSelectionMapping($childSku);
    }

    /**
     * Query whether or not the passed store view code is the default one.
     *
     * @param string $storeViewCode The store view code to be queried
     *
     * @return boolean TRUE if the passed store view code is the default one, else FALSE
     */
    protected function isDefaultStore($storeViewCode)
    {
        return StoreViewCodes::ADMIN === strtolower($storeViewCode);
    }

    /**
     * Return's the store for the passed store code.
     *
     * @param string $storeCode The store code to return the store for
     *
     * @return array The requested store
     * @throws \Exception Is thrown, if the requested store is not available
     */
    protected function getStoreByStoreCode($storeCode)
    {
        return $this->getSubject()->getStoreByStoreCode($storeCode);
    }

    /**
     * Persist's the passed product bundle selection price data and return's the ID.
     *
     * @param array $productBundleSelectionPrice The product bundle selection price data to persist
     *
     * @return void
     */
    protected function persistProductBundleSelectionPrice($productBundleSelectionPrice)
    {
        $this->getSubject()->persistProductBundleSelectionPrice($productBundleSelectionPrice);
    }
}
