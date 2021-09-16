<?php

/**
 * TechDivision\Import\Product\Bundle\Observers\BundleOptionValueObserver
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
use TechDivision\Import\Product\Bundle\Utils\MemberNames;
use TechDivision\Import\Product\Observers\AbstractProductImportObserver;
use TechDivision\Import\Product\Bundle\Services\ProductBundleProcessorInterface;

/**
 * Oberserver that provides functionality for the bundle option value replace operation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class BundleOptionValueObserver extends AbstractProductImportObserver
{

    /**
     * The option value store mapping.
     *
     * @var array
     */
    protected $optionValueStoreMapping = array();

    /**
     * The product bundle processor instance.
     *
     * @var \TechDivision\Import\Product\Bundle\Services\ProductBundleProcessorInterface
     */
    protected $productBundleProcessor;

    /**
     * Initialize the observer with the passed product bundle processor instance.
     *
     * @param \TechDivision\Import\Product\Bundle\Services\ProductBundleProcessorInterface $productBundleProcessor The product bundle processor instance
     */
    public function __construct(ProductBundleProcessorInterface $productBundleProcessor)
    {
        $this->productBundleProcessor = $productBundleProcessor;
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
     * Process the observer's business logic.
     *
     * @return array The processed row
     */
    protected function process()
    {

        // prepare the store view code
        $this->prepareStoreViewCode($this->getRow());

        // prepare the attributes
        $attr = $this->prepareAttributes();

        // load store/option ID
        $storeId = $attr[MemberNames::STORE_ID];
        $optionId = $attr[MemberNames::OPTION_ID];

        // if the store has already been mapped, return immediately
        if ($this->isMapped($optionId, $storeId)) {
            return;
        }

        // initialize and save the product bundle option value
        if ($bundleOption = $this->initializeBundleOptionValue($attr)) {
            $this->persistProductBundleOptionValue($bundleOption);
        }

        // add the store => option mapping
        $this->addOptionValueStoreMapping($optionId, $storeId);
    }

    /**
     * Prepare the attributes of the entity that has to be persisted.
     *
     * @return array The prepared attributes
     */
    protected function prepareAttributes()
    {

        // load the product bundle option name
        $name = $this->getValue(ColumnKeys::BUNDLE_VALUE_NAME);

        // load the actual option ID
        $optionId = $this->getLastOptionId();

        try {
            // load and map the parent SKU
            $parentProductId = $this->mapSku($this->getValue(ColumnKeys::BUNDLE_PARENT_SKU));
        } catch (\Exception $e) {
            throw $this->wrapException(array(ColumnKeys::BUNDLE_PARENT_SKU), $e);
        }

        // load the store/website ID
        $store = $this->getStoreByStoreCode($this->getStoreViewCode(StoreViewCodes::ADMIN));
        $storeId = $store[MemberNames::STORE_ID];

        // return the prepared product
        return $this->initializeEntity(
            array(
                MemberNames::OPTION_ID         => $optionId,
                MemberNames::PARENT_PRODUCT_ID => $parentProductId,
                MemberNames::STORE_ID          => $storeId,
                MemberNames::TITLE             => $name
            )
        );
    }

    /**
     * Initialize the bundle option value with the passed attributes and returns an instance.
     *
     * @param array $attr The bundle option value attributes
     *
     * @return array The initialized bundle option value
     */
    protected function initializeBundleOptionValue(array $attr)
    {
        return $attr;
    }

    /**
     * Add the store => option mapping.
     *
     * @param integer $optionId The option ID to map
     * @param integer $storeId  The store ID to map
     *
     * @return void
     */
    protected function addOptionValueStoreMapping($optionId, $storeId)
    {
        $this->optionValueStoreMapping[$optionId][] = $storeId;
    }

    /**
     * Query whether or not the passed option/store ID combination has already been mapped.
     *
     * @param integer $optionId The option ID to map
     * @param integer $storeId  The store ID to map
     *
     * @return boolean TRUE if the combination has already been mapped, else FALSE
     */
    protected function isMapped($optionId, $storeId)
    {
        return (isset($this->optionValueStoreMapping[$optionId]) && in_array($storeId, $this->optionValueStoreMapping[$optionId]));
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
     * Persist's the passed product bundle option value data.
     *
     * @param array $productBundleOptionValue The product bundle option value data to persist
     *
     * @return void
     */
    protected function persistProductBundleOptionValue($productBundleOptionValue)
    {
        $this->getProductBundleProcessor()->persistProductBundleOptionValue($productBundleOptionValue);
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
}
