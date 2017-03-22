<?php

/**
 * TechDivision\Import\Product\Bundle\Observers\BundleSelectionUpdateObserver
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

use TechDivision\Import\Product\Bundle\Utils\ColumnKeys;

/**
 * Oberserver that provides functionality for the bundle selection add/update operation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class BundleSelectionUpdateObserver extends BundleSelectionObserver
{

    /**
     * Initialize the bundle selection with the passed attributes and returns an instance.
     *
     * @param array $attr The bundle selection attributes
     *
     * @return array The initialized bundle selection
     */
    protected function initializeBundleSelection(array $attr)
    {

        try {
            // try to load the product bundle option SKU/ID
            $parentProductId= $this->mapSkuToEntityId($this->getValue(ColumnKeys::BUNDLE_PARENT_SKU));
        } catch (\Exception $e) {
            throw $this->wrapException(array(ColumnKeys::BUNDLE_PARENT_SKU), $e);
        }

        try {
            // try to load the product ID
            $productId = $this->mapSkuToEntityId($this->getValue(ColumnKeys::BUNDLE_VALUE_SKU));
        } catch (\Exception $e) {
            throw $this->wrapException(array(ColumnKeys::BUNDLE_PARENT_SKU), $e);
        }

        // load the actual option ID
        $optionId = $this->getLastOptionId();

        // try to load the bundle selection with the passed option/product/parent product ID
        if ($entity = $this->loadBundleSelection($optionId, $productId, $parentProductId)) {
            return $this->mergeEntity($entity, $attr);
        }

        // simply return the attributes
        return $attr;
    }

    /**
     * Load's the bundle selection value with the passed option/product/parent product ID.
     *
     * @param integer $optionId        The option ID of the bundle selection to be returned
     * @param integer $productId       The product ID of the bundle selection to be returned
     * @param integer $parentProductId The parent product ID of the bundle selection to be returned
     *
     * @return array The bundle selection
     */
    protected function loadBundleSelection($optionId, $productId, $parentProductId)
    {
        return $this->getSubject()->loadBundleSelection($optionId, $productId, $parentProductId);
    }
}
