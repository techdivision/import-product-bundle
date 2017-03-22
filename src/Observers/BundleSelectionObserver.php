<?php

/**
 * TechDivision\Import\Product\Bundle\Observers\BundleSelectionObserver
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
use TechDivision\Import\Product\Observers\AbstractProductImportObserver;
use TechDivision\Import\Product\Bundle\Utils\MemberNames;

/**
 * Oberserver that provides functionality for the bundle selection replace operation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class BundleSelectionObserver extends AbstractProductImportObserver
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

        // return immediately if we're have no store view code set
        if (StoreViewCodes::ADMIN !== $this->getStoreViewCode(StoreViewCodes::ADMIN)) {
            return;
        }

        // prepare, initialize and persist the product bundle selection data
        $productBundleSelection = $this->initializeBundleSelection($this->prepareAttributes());
        $selectionId = $this->persistProductBundleSelection($productBundleSelection);

        // add the mapping for the child SKU => selection ID
        $this->addChildSkuSelectionIdMapping($this->getValue(ColumnKeys::BUNDLE_VALUE_SKU), $selectionId);
    }

    /**
     * Prepare the attributes of the entity that has to be persisted.
     *
     * @return array The prepared attributes
     */
    protected function prepareAttributes()
    {

        // load the product bundle option SKU
        $parentSku = $this->getValue(ColumnKeys::BUNDLE_PARENT_SKU);

        // load parent/option ID
        $parentId = $this->mapSkuToEntityId($parentSku);

        // load the actual option ID
        $optionId = $this->getLastOptionId();

        try {
            // try to load the child ID
            $childId = $this->mapSkuToEntityId($this->getValue(ColumnKeys::BUNDLE_VALUE_SKU));
        } catch (\Exception $e) {
            throw $this->wrapException(array(ColumnKeys::BUNDLE_VALUE_SKU), $e);
        }

        try {
            // try to load the selection price type
            $selectionPriceType = $this->mapPriceType($this->getValue(ColumnKeys::BUNDLE_VALUE_PRICE_TYPE));
        } catch (\Exception $e) {
            throw $this->wrapException(array(ColumnKeys::BUNDLE_VALUE_PRICE_TYPE), $e);
        }

        // load the default values
        $selectionCanChangeQty = 1;
        $selectionPriceValue = $this->getValue(ColumnKeys::BUNDLE_VALUE_PRICE);
        $selectionQty = $this->getValue(ColumnKeys::BUNDLE_VALUE_DEFAULT_QTY);
        $isDefault = $this->getValue(ColumnKeys::BUNDLE_VALUE_DEFAULT);

        // laod the position counter
        $position = $this->raisePositionCounter();

        // prepare the product bundle selection data
        return $this->initializeEntity(
            array(
                MemberNames::OPTION_ID                => $optionId,
                MemberNames::PARENT_PRODUCT_ID        => $parentId,
                MemberNames::PRODUCT_ID               => $childId,
                MemberNames::POSITION                 => $position,
                MemberNames::IS_DEFAULT               => $isDefault,
                MemberNames::SELECTION_PRICE_TYPE     => $selectionPriceType,
                MemberNames::SELECTION_PRICE_VALUE    => $selectionPriceValue,
                MemberNames::SELECTION_QTY            => $selectionQty,
                MemberNames::SELECTION_CAN_CHANGE_QTY => $selectionCanChangeQty
            )
        );
    }

    /**
     * Initialize the bundle selection with the passed attributes and returns an instance.
     *
     * @param array $attr The bundle selection attributes
     *
     * @return array The initialized bundle selection
     */
    protected function initializeBundleSelection(array $attr)
    {
        return $attr;
    }

    /**
     * Return's the last created option ID.
     *
     * @return integer $optionId The last created option ID
     */
    protected function getLastOptionId()
    {
        return $this->getSubject()->getLastOptionId();
    }

    /**
     * Save's the mapping of the child SKU and the selection ID.
     *
     * @param string  $childSku    The child SKU of the selection
     * @param integer $selectionId The selection ID to save
     *
     * @return void
     */
    protected function addChildSkuSelectionIdMapping($childSku, $selectionId)
    {
        $this->getSubject()->addChildSkuSelectionIdMapping($childSku, $selectionId);
    }

    /**
     * Returns the acutal value of the position counter and raise's it by one.
     *
     * @return integer The actual value of the position counter
     */
    protected function raisePositionCounter()
    {
        return $this->getSubject()->raisePositionCounter();
    }

    /**
     * Return's the option ID for the passed name.
     *
     * @param string $name The name to return the option ID for
     *
     * @return integer The option ID for the passed name
     * @throws \Exception Is thrown, if no option ID for the passed name is available
     */
    protected function getOptionIdForName($name)
    {
        return $this->getSubject()-> getOptionIdForName($name);
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
     * Return the entity ID for the passed SKU.
     *
     * @param string $sku The SKU to return the entity ID for
     *
     * @return integer The mapped entity ID
     * @throws \Exception Is thrown if the SKU is not mapped yet
     */
    protected function mapSkuToEntityId($sku)
    {
        return $this->getSubject()->mapSkuToEntityId($sku);
    }

    /**
     * Persist's the passed product bundle selection data and return's the ID.
     *
     * @param array $productBundleSelection The product bundle selection data to persist
     *
     * @return string The ID of the persisted entity
     */
    protected function persistProductBundleSelection($productBundleSelection)
    {
        return $this->getSubject()->persistProductBundleSelection($productBundleSelection);
    }
}
