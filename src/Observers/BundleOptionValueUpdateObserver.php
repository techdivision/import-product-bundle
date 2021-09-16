<?php

/**
 * TechDivision\Import\Product\Bundle\Observers\BundleOptionValueUpdateObserver
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Bundle\Observers;

use TechDivision\Import\Utils\StoreViewCodes;
use TechDivision\Import\Product\Bundle\Utils\ColumnKeys;

/**
 * Oberserver that provides functionality for the bundle option value add/update operation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class BundleOptionValueUpdateObserver extends BundleOptionValueObserver
{

    /**
     * Initialize the bundle option value with the passed attributes and returns an instance.
     *
     * @param array $attr The bundle option value attributes
     *
     * @return array|null The initialized bundle option value, or NULL if the option value already exists
     */
    protected function initializeBundleOptionValue(array $attr)
    {

        // load and map the parent option ID
        $parentId = $this->mapSku($this->getValue(ColumnKeys::BUNDLE_PARENT_SKU));

        // load the parent ID, the name and the store ID
        $name = $this->getValue(ColumnKeys::BUNDLE_VALUE_NAME);
        $storeId = $this->getRowStoreId(StoreViewCodes::ADMIN);

        // try to load the bundle option value with the passed name/store/parent ID
        if ($this->loadBundleOptionValue($name, $storeId, $parentId)) {
            return;
        }

        // simply return the attributes
        return $attr;
    }

    /**
     * Load's the bundle option value with the passed name, store + parent ID.
     *
     * @param string  $title    The title of the bundle option value to be returned
     * @param integer $storeId  The store ID of the bundle option value to be returned
     * @param integer $parentId The entity of the product the bundle option value is related with
     *
     * @return array The bundle option
     */
    protected function loadBundleOptionValue($title, $storeId, $parentId)
    {
        return $this->getProductBundleProcessor()->loadBundleOptionValue($title, $storeId, $parentId);
    }

    /**
     * Return's the store ID of the actual row, or of the default store
     * if no store view code is set in the CSV file.
     *
     * @param string|null $default The default store view code to use, if no store view code is set in the CSV file
     *
     * @return integer The ID of the actual store
     * @throws \Exception Is thrown, if the store with the actual code is not available
     */
    protected function getRowStoreId($default = null)
    {
        return $this->getSubject()->getRowStoreId($default);
    }
}
