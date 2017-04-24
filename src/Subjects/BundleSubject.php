<?php

/**
 * TechDivision\Import\Product\Bundle\Subjects\BundleSubject
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

namespace TechDivision\Import\Product\Bundle\Subjects;

use TechDivision\Import\Utils\RegistryKeys;
use TechDivision\Import\Product\Subjects\AbstractProductSubject;

/**
 * A SLSB that handles the process to import product variants.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class BundleSubject extends AbstractProductSubject
{

    /**
     * The value for the price type 'fixed'.
     *
     * @var integer
     */
    const PRICE_TYPE_FIXED = 0;

    /**
     * The value for the price type 'percent'.
     *
     * @var integer
     */
    const PRICE_TYPE_PERCENT = 1;

    /**
     * The mapping for the SKUs to the created entity IDs.
     *
     * @var array
     */
    protected $skuEntityIdMapping = array();

    /**
     * The option name => option ID mapping.
     *
     * @var array
     */
    protected $nameOptionIdMapping = array();

    /**
     * The ID of the last created selection.
     *
     * @var integer
     */
    protected $childSkuSelectionIdMapping = array();

    /**
     * The position counter, if no position for the bundle selection has been specified.
     *
     * @var integer
     */
    protected $positionCounter = 1;

    /**
     * The mapping for the price type.
     *
     * @var array
     */
    protected $priceTypeMapping = array(
        'fixed'   => BundleSubject::PRICE_TYPE_FIXED,
        'percent' => BundleSubject::PRICE_TYPE_PERCENT
    );

    /**
     * Intializes the previously loaded global data for exactly one variants.
     *
     * @param string $serial The serial of the actual import
     *
     * @return void
     * @see \Importer\Csv\Actions\ProductImportAction::prepare()
     */
    public function setUp($serial)
    {

        // invoke the parent method
        parent::setUp($serial);

        // load the entity manager and the registry processor
        $registryProcessor = $this->getRegistryProcessor();

        // load the status of the actual import process
        $status = $registryProcessor->getAttribute($serial);

        // load the attribute set we've prepared intially
        $this->skuEntityIdMapping = $status[RegistryKeys::SKU_ENTITY_ID_MAPPING];
    }

    /**
     * Reset the position counter to 1.
     *
     * @return void
     */
    public function resetPositionCounter()
    {
        $this->positionCounter = 1;
    }

    /**
     * Returns the acutal value of the position counter and raise's it by one.
     *
     * @return integer The actual value of the position counter
     */
    public function raisePositionCounter()
    {
        return $this->positionCounter++;
    }

    /**
     * Save's the mapping of the child SKU and the selection ID.
     *
     * @param string  $childSku    The child SKU of the selection
     * @param integer $selectionId The selection ID to save
     *
     * @return void
     */
    public function addChildSkuSelectionIdMapping($childSku, $selectionId)
    {
        $this->childSkuSelectionIdMapping[$childSku] = $selectionId;
    }

    /**
     * Return's the selection ID for the passed child SKU.
     *
     * @param string $childSku The child SKU to return the selection ID for
     *
     * @return integer The last created selection ID
     * @throws \Exception Is thrown if the SKU is not mapped yet
     */
    public function getChildSkuSelectionMapping($childSku)
    {

        // query whether or not a child SKU selection ID mapping is available
        if (isset($this->childSkuSelectionIdMapping[$childSku])) {
            return $this->childSkuSelectionIdMapping[$childSku];
        }

        // throw an exception if the SKU has not been mapped yet
        throw new \Exception(
            $this->appendExceptionSuffix(
                sprintf('Found not mapped selection ID mapping for SKU %s', $childSku)
            )
        );
    }

    /**
     * Return the entity ID for the passed SKU.
     *
     * @param string $sku The SKU to return the entity ID for
     *
     * @return integer The mapped entity ID
     * @throws \Exception Is thrown if the SKU is not mapped yet
     */
    public function mapSkuToEntityId($sku)
    {

        // query weather or not the SKU has been mapped
        if (isset($this->skuEntityIdMapping[$sku])) {
            return $this->skuEntityIdMapping[$sku];
        }

        // throw an exception if the SKU has not been mapped yet
        throw new \Exception(
            $this->appendExceptionSuffix(
                sprintf('Found not mapped entity ID mapping for SKU %s', $sku)
            )
        );
    }

    /**
     * Return's the mapping for the passed price type.
     *
     * @param string $priceType The price type to map
     *
     * @return integer The mapped price type
     * @throws \Exception Is thrown, if the passed price type can't be mapped
     */
    public function mapPriceType($priceType)
    {

        // query whether or not the passed price type is available
        if (isset($this->priceTypeMapping[$priceType])) {
            return $this->priceTypeMapping[$priceType];
        }

        // throw an exception, if not
        throw new \Exception(
            $this->appendExceptionSuffix(
                sprintf('Can\'t find mapping for price type %s', $priceType)
            )
        );
    }

    /**
     * Return's the store for the passed store code.
     *
     * @param string $storeCode The store code to return the store for
     *
     * @return array The requested store
     * @throws \Exception Is thrown, if the requested store is not available
     */
    public function getStoreByStoreCode($storeCode)
    {

        // query whether or not the store with the passed store code exists
        if (isset($this->stores[$storeCode])) {
            return $this->stores[$storeCode];
        }

        // throw an exception, if not
        throw new \Exception(
            $this->appendExceptionSuffix(
                sprintf('Found invalid store code %s', $storeCode)
            )
        );
    }

    /**
     * Return's the option ID for the passed name.
     *
     * @param string $name The name to return the option ID for
     *
     * @return integer The option ID for the passed name
     * @throws \Exception Is thrown, if no option ID for the passed name is available
     */
    public function getOptionIdForName($name)
    {

        // query whether or not an option ID for the passed name is available
        if (isset($this->nameOptionIdMapping[$name])) {
            return $this->nameOptionIdMapping[$name];
        }

        // throw an exception, if not
        throw new \Exception(
            $this->appendExceptionSuffix(
                sprintf('Can\'t find option ID for name %s', $name)
            )
        );
    }

    /**
     * Add's the mapping for the passed name => option ID.
     *
     * @param string  $name     The name of the option
     * @param integer $optionId The created option ID
     *
     * @return void
     */
    public function addNameOptionIdMapping($name, $optionId)
    {
        $this->nameOptionIdMapping[$name] = $optionId;
    }

    /**
     * Query whether or not the option with the passed name has already been created.
     *
     * @param string $name The option name to query for
     *
     * @return boolean TRUE if the option already exists, else FALSE
     */
    public function exists($name)
    {
        return isset($this->nameOptionIdMapping[$name]);
    }

    /**
     * Return's the last created option ID.
     *
     * @return integer $optionId The last created option ID
     */
    public function getLastOptionId()
    {
        return end($this->nameOptionIdMapping);
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
    public function loadBundleOption($title, $storeId, $parentId)
    {
        return $this->getProductProcessor()->loadBundleOption($title, $storeId, $parentId);
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
    public function loadBundleOptionValue($title, $storeId, $parentId)
    {
        return $this->getProductProcessor()->loadBundleOptionValue($title, $storeId, $parentId);
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
    public function loadBundleSelection($optionId, $productId, $parentProductId)
    {
        return $this->getProductProcessor()->loadBundleSelection($optionId, $productId, $parentProductId);
    }

    /**
     * Load's the bundle selection price with the passed selection/website ID.
     *
     * @param integer $selectionId The selection ID of the bundle selection price to be returned
     * @param integer $websiteId   The website ID of the bundle selection price to be returned
     *
     * @return array The bundle selection price
     */
    public function loadBundleSelectionPrice($selectionId, $websiteId)
    {
        return $this->getProductProcessor()->loadBundleSelectionPrice($selectionId, $websiteId);
    }

    /**
     * Persist's the passed product bundle option data and return's the ID.
     *
     * @param array $productBundleOption The product bundle option data to persist
     *
     * @return string The ID of the persisted entity
     */
    public function persistProductBundleOption($productBundleOption)
    {
        return $this->getProductProcessor()->persistProductBundleOption($productBundleOption);
    }

    /**
     * Persist's the passed product bundle option value data.
     *
     * @param array $productBundleOptionValue The product bundle option value data to persist
     *
     * @return void
     */
    public function persistProductBundleOptionValue($productBundleOptionValue)
    {
        return $this->getProductProcessor()->persistProductBundleOptionValue($productBundleOptionValue);
    }

    /**
     * Persist's the passed product bundle selection data and return's the ID.
     *
     * @param array $productBundleSelection The product bundle selection data to persist
     *
     * @return string The ID of the persisted entity
     */
    public function persistProductBundleSelection($productBundleSelection)
    {
        return $this->getProductProcessor()->persistProductBundleSelection($productBundleSelection);
    }

    /**
     * Persist's the passed product bundle selection price data and return's the ID.
     *
     * @param array $productBundleSelectionPrice The product bundle selection price data to persist
     *
     * @return void
     */
    public function persistProductBundleSelectionPrice($productBundleSelectionPrice)
    {
        $this->getProductProcessor()->persistProductBundleSelectionPrice($productBundleSelectionPrice);
    }
}
