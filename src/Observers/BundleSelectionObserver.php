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
use TechDivision\Import\Utils\BackendTypeKeys;
use TechDivision\Import\Observers\StateDetectorInterface;
use TechDivision\Import\Observers\AttributeLoaderInterface;
use TechDivision\Import\Observers\DynamicAttributeObserverInterface;
use TechDivision\Import\Product\Observers\AbstractProductImportObserver;
use TechDivision\Import\Product\Bundle\Utils\ColumnKeys;
use TechDivision\Import\Product\Bundle\Utils\MemberNames;
use TechDivision\Import\Product\Bundle\Utils\EntityTypeCodes;
use TechDivision\Import\Product\Bundle\Services\ProductBundleProcessorInterface;

/**
 * Oberserver that provides functionality for the bundle selection replace operation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class BundleSelectionObserver extends AbstractProductImportObserver implements DynamicAttributeObserverInterface
{

    /**
     * The product bundle processor instance.
     *
     * @var \TechDivision\Import\Product\Bundle\Services\ProductBundleProcessorInterface
     */
    protected $productBundleProcessor;

    /**
     * The attribute loader instance.
     *
     * @var \TechDivision\Import\Observers\AttributeLoaderInterface
     */
    protected $attributeLoader;

    /**
     * Initialize the "dymanmic" columns.
     *
     * @var array
     */
    protected $columns = array(
        MemberNames::POSITION                 => array(ColumnKeys::BUNDLE_VALUE_SELECTION_POSITION, BackendTypeKeys::BACKEND_TYPE_INT),
        MemberNames::IS_DEFAULT               => array(ColumnKeys::BUNDLE_VALUE_DEFAULT, BackendTypeKeys::BACKEND_TYPE_INT),
        MemberNames::SELECTION_PRICE_VALUE    => array(ColumnKeys::BUNDLE_VALUE_PRICE, BackendTypeKeys::BACKEND_TYPE_FLOAT),
        MemberNames::SELECTION_CAN_CHANGE_QTY => array(ColumnKeys::BUNDLE_VALUE_CAN_CHANGE_QTY, BackendTypeKeys::BACKEND_TYPE_INT)
    );

    /**
     * Array with virtual column name mappings (this is a temporary
     * solution till techdivision/import#179 as been implemented).
     *
     * @var array
     * @todo https://github.com/techdivision/import/issues/179
     */
    protected $virtualMapping = array(
        MemberNames::POSITION                 => ColumnKeys::BUNDLE_VALUE_SELECTION_POSITION,
        MemberNames::IS_DEFAULT               => ColumnKeys::BUNDLE_VALUE_DEFAULT,
        MemberNames::SELECTION_PRICE_VALUE    => ColumnKeys::BUNDLE_VALUE_PRICE,
        MemberNames::SELECTION_CAN_CHANGE_QTY => ColumnKeys::BUNDLE_VALUE_CAN_CHANGE_QTY
    );

    /**
     * Initialize the observer with the passed product bundle processor instance.
     *
     * @param \TechDivision\Import\Product\Bundle\Services\ProductBundleProcessorInterface $productBundleProcessor The product bundle processor instance
     * @param \TechDivision\Import\Observers\AttributeLoaderInterface|null                 $attributeLoader        The attribute loader instance
     * @param \TechDivision\Import\Observers\StateDetectorInterface|null                   $stateDetector          The state detector instance
     */
    public function __construct(
        ProductBundleProcessorInterface $productBundleProcessor,
        AttributeLoaderInterface $attributeLoader = null,
        StateDetectorInterface $stateDetector = null
    ) {

        // initialize the product bundle processor and the attribute loader instance
        $this->productBundleProcessor = $productBundleProcessor;
        $this->attributeLoader = $attributeLoader;

        // pass the state detector to the parent method
        parent::__construct($stateDetector);
    }

    /**
     * Return's the product bundle processor instance.
     *
     * @return \TechDivision\Import\Product\Bundle\Services\ProductBundleProcessorInterface The product bundle processor instance
     */
    protected function getProductBundleProcessor()
    {
        return $this->productBundleProcessor;
    }

    /**
     * Query whether or not a value for the column with the passed name exists.
     *
     * @param string $name The column name to query for a valid value
     *
     * @return boolean TRUE if the value is set, else FALSE
     * @todo https://github.com/techdivision/import/issues/179
     */
    public function hasValue($name)
    {
        return parent::hasValue(isset($this->virtualMapping[$name]) ? $this->virtualMapping[$name] : $name);
    }

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
        $productBundleSelection = $this->initializeBundleSelection($this->prepareDynamicAttributes());
        $selectionId = $this->persistProductBundleSelection($productBundleSelection);

        // add the mapping for the child SKU => selection ID
        $this->addChildSkuSelectionIdMapping($this->getValue(ColumnKeys::BUNDLE_VALUE_SKU), $selectionId);
    }

    /**
     * Appends the dynamic attributes to the static ones and returns them.
     *
     * @return array The array with all available attributes
     */
    protected function prepareDynamicAttributes() : array
    {
        return array_merge($this->prepareAttributes(), $this->attributeLoader ? $this->attributeLoader->load($this, $this->columns) : array());
    }

    /**
     * Prepare the attributes of the entity that has to be persisted.
     *
     * @return array The prepared attributes
     */
    protected function prepareAttributes()
    {

        // load the actual option ID
        $optionId = $this->getLastOptionId();

        try {
            // load and map the parent SKU
            $parentId = $this->mapSku($this->getValue(ColumnKeys::BUNDLE_PARENT_SKU));
        } catch (\Exception $e) {
            throw $this->wrapException(array(ColumnKeys::BUNDLE_PARENT_SKU), $e);
        }

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
        $selectionQty = $this->getValue(ColumnKeys::BUNDLE_VALUE_DEFAULT_QTY);

        // prepare the product bundle selection data
        return $this->initializeEntity(
            $this->loadRawEntity(
                array(
                    MemberNames::OPTION_ID            => $optionId,
                    MemberNames::PARENT_PRODUCT_ID    => $parentId,
                    MemberNames::PRODUCT_ID           => $childId,
                    MemberNames::SELECTION_QTY        => $selectionQty,
                    MemberNames::SELECTION_PRICE_TYPE => $selectionPriceType
                )
            )
        );
    }

    /**
     * Load's and return's a raw entity without primary key but the mandatory members only and nulled values.
     *
     * @param array $data An array with data that will be used to initialize the raw entity with
     *
     * @return array The initialized entity
     */
    protected function loadRawEntity(array $data = array())
    {
        return $this->getProductBundleProcessor()->loadRawEntity(EntityTypeCodes::CATALOG_PRODUCT_BUNDLE_SELECTION, $data);
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
     * @deprecated Since 22.0.0
     */
    protected function raisePositionCounter()
    {
        return $this->getSubject()->raisePositionCounter();
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
    protected function mapSku($sku)
    {
        return $this->getSubject()->mapSkuToEntityId($sku);
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
        return $this->getProductBundleProcessor()->persistProductBundleSelection($productBundleSelection);
    }
}
