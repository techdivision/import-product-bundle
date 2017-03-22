<?php

/**
 * TechDivision\Import\Product\Bundle\Observers\BundleOptionObserver
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
 * Oberserver that provides functionality for the bundle option replace operation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class BundleOptionObserver extends AbstractProductImportObserver
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

        // query whether or not the option has already been created
        if (!$this->exists($name = $this->getValue(ColumnKeys::BUNDLE_VALUE_NAME))) {
            // load the bundle option
            $bundleOption = $this->initializeBundleOption($this->prepareAttributes());

            // persist the product bundle option
            $optionId = $this->persistProductBundleOption($bundleOption);

            // store the name => option ID mapping
            $this->addNameOptionIdMapping($name, $optionId);
        }
    }

    /**
     * Prepare the attributes of the entity that has to be persisted.
     *
     * @return array The prepared attributes
     */
    protected function prepareAttributes()
    {

        // reset the position counter for the bundle selection
        $this->resetPositionCounter();

        try {
            // load and map the parent SKU
            $parentId = $this->mapSku($this->getValue(ColumnKeys::BUNDLE_PARENT_SKU));
        } catch (\Exception $e) {
            throw $this->wrapException(array(ColumnKeys::BUNDLE_PARENT_SKU), $e);
        }

        // extract the parent/child ID as well as type and position
        $required = $this->getValue(ColumnKeys::BUNDLE_VALUE_REQUIRED);
        $type = $this->getValue(ColumnKeys::BUNDLE_VALUE_TYPE);
        $position = 1;

        // return the prepared product
        return $this->initializeEntity(
            array(
                MemberNames::PARENT_ID => $parentId,
                MemberNames::REQUIRED  => $required,
                MemberNames::POSITION  => $position,
                MemberNames::TYPE      => $type
            )
        );
    }

    /**
     * Initialize the bundle option with the passed attributes and returns an instance.
     *
     * @param array $attr The bundle option attributes
     *
     * @return array The initialized bundle option
     */
    protected function initializeBundleOption(array $attr)
    {
        return $attr;
    }

    /**
     * Reset the position counter to 1.
     *
     * @return void
     */
    protected function resetPositionCounter()
    {
        $this->getSubject()->resetPositionCounter();
    }

    /**
     * Add's the mapping for the passed name => option ID.
     *
     * @param string  $name     The name of the option
     * @param integer $optionId The created option ID
     *
     * @return void
     */
    protected function addNameOptionIdMapping($name, $optionId)
    {
        $this->getSubject()->addNameOptionIdMapping($name, $optionId);
    }

    /**
     * Query whether or not the option with the passed name has already been created.
     *
     * @param string $name The option name to query for
     *
     * @return boolean TRUE if the option already exists, else FALSE
     */
    protected function exists($name)
    {
        return $this->getSubject()->exists($name);
    }

    /**
     * Return the entity ID for the passed SKU.
     *
     * @param string $sku The SKU to return the entity ID for
     *
     * @return integer The mapped entity ID
     * @throws \Exception Is thrown if the SKU is not mapped yet
     */
    protected function mapSku($sku)
    {
        return $this->getSubject()->mapSkuToEntityId($sku);
    }

    /**
     * Persist's the passed product bundle option data and return's the ID.
     *
     * @param array $productBundleOption The product bundle option data to persist
     *
     * @return string The ID of the persisted entity
     */
    protected function persistProductBundleOption($productBundleOption)
    {
        return $this->getSubject()->persistProductBundleOption($productBundleOption);
    }
}
