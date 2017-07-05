<?php

/**
 * TechDivision\Import\Product\Bundle\Observers\BundleOptionUpdateObserver
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
 * Oberserver that provides functionality for the bundle option add/update operation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class BundleOptionUpdateObserver extends BundleOptionObserver
{

    /**
     * Initialize the bundle option with the passed attributes and returns an instance.
     *
     * @param array $attr The bundle option attributes
     *
     * @return array The initialized bundle option
     */
    protected function initializeBundleOption(array $attr)
    {

        // load the parent ID, the name and the store ID
        $parentId = $attr[MemberNames::PARENT_ID];
        $name = $this->getValue(ColumnKeys::BUNDLE_VALUE_NAME);
        $storeId = $this->getRowStoreId(StoreViewCodes::ADMIN);

        // try to load the bundle option with the passed name/store/parent ID
        if ($entity = $this->loadBundleOption($name, $storeId, $parentId)) {
            return $this->mergeEntity($entity, $attr);
        }

        // simply return the attributes
        return $attr;
    }

    /**
     * Load's the bundle option with the passed name, store + parent ID.
     *
     * @param string  $title    The title of the bundle option to be returned
     * @param integer $storeId  The store ID of the bundle option to be returned
     * @param integer $parentId The entity of the product the bundle option is related with
     *
     * @return array The bundle option
     */
    protected function loadBundleOption($title, $storeId, $parentId)
    {
        return $this->getProductBundleProcessor()->loadBundleOption($title, $storeId, $parentId);
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
