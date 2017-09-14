<?php

/**
 * TechDivision\Import\Product\Bundle\Services\ProductBundleProcessorInterface
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

namespace TechDivision\Import\Product\Bundle\Services;

use TechDivision\Import\Product\Services\ProductProcessorInterface;

/**
 * A SLSB providing methods to load product data using a PDO connection.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
interface ProductBundleProcessorInterface extends ProductProcessorInterface
{

    /**
     * Return's the action with the product bundle option CRUD methods.
     *
     * @return \TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionAction The action instance
     */
    public function getProductBundleOptionAction();

    /**
     * Return's the action with the product bundle option value CRUD methods.
     *
     * @return \TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionValueAction The action instance
     */
    public function getProductBundleOptionValueAction();

    /**
     * Return's the action with the product bundle selection CRUD methods.
     *
     * @return \TechDivision\Import\Product\Bundle\Actions\ProductBundleSelectionAction The action instance
     */
    public function getProductBundleSelectionAction();

    /**
     * Return's the action with the product bundle selection price CRUD methods.
     *
     * @return \TechDivision\Import\Product\Bundle\Actions\ProductBundleSelectionPriceAction The action instance
     */
    public function getProductBundleSelectionPriceAction();

    /**
     * Return's the respository to load bundle option data.
     *
     * @return \TechDivision\Import\Product\Bundle\Repositories\BundleOptionRepository The repository instance
     */
    public function getBundleOptionRepository();

    /**
     * Return's the respository to load bundle option value data.
     *
     * @return \TechDivision\Import\Product\Bundle\Repositories\BundleOptionValueRepository The repository instance
     */
    public function getBundleOptionValueRepository();

    /**
     * Return's the respository to load bundle selection data.
     *
     * @return \TechDivision\Import\Product\Bundle\Repositories\BundleSelectionRepository The repository instance
     */
    public function getBundleSelectionRepository();

    /**
     * Return's the respository to load bundle selection price data.
     *
     * @return \TechDivision\Import\Product\Bundle\Repositories\BundleSelectionPriceRepository The repository instance
     */
    public function getBundleSelectionPriceRepository();

    /**
     * Load's the bundle option with the passed name, store + parent ID.
     *
     * @param string  $title    The title of the bundle option to be returned
     * @param integer $storeId  The store ID of the bundle option to be returned
     * @param integer $parentId The entity of the product the bundle option is related with
     *
     * @return array The bundle option
     */
    public function loadBundleOption($title, $storeId, $parentId);

    /**
     * Load's the bundle option value with the passed name, store + parent ID.
     *
     * @param string  $title    The title of the bundle option value to be returned
     * @param integer $storeId  The store ID of the bundle option value to be returned
     * @param integer $parentId The entity of the product the bundle option value is related with
     *
     * @return array The bundle option
     */
    public function loadBundleOptionValue($title, $storeId, $parentId);

    /**
     * Load's the bundle selection value with the passed option/product/parent product ID.
     *
     * @param integer $optionId        The option ID of the bundle selection to be returned
     * @param integer $productId       The product ID of the bundle selection to be returned
     * @param integer $parentProductId The parent product ID of the bundle selection to be returned
     *
     * @return array The bundle selection
     */
    public function loadBundleSelection($optionId, $productId, $parentProductId);

    /**
     * Load's the bundle selection price with the passed selection/website ID.
     *
     * @param integer $selectionId The selection ID of the bundle selection price to be returned
     * @param integer $websiteId   The website ID of the bundle selection price to be returned
     *
     * @return array The bundle selection price
     */
    public function loadBundleSelectionPrice($selectionId, $websiteId);

    /**
     * Persist's the passed product bundle option data and return's the ID.
     *
     * @param array $productBundleOption The product bundle option data to persist
     *
     * @return string The ID of the persisted entity
     */
    public function persistProductBundleOption($productBundleOption);

    /**
     * Persist's the passed product bundle option value data.
     *
     * @param array $productBundleOptionValue The product bundle option value data to persist
     *
     * @return void
     */
    public function persistProductBundleOptionValue($productBundleOptionValue);

    /**
     * Persist's the passed product bundle selection data and return's the ID.
     *
     * @param array $productBundleSelection The product bundle selection data to persist
     *
     * @return string The ID of the persisted entity
     */
    public function persistProductBundleSelection($productBundleSelection);

    /**
     * Persist's the passed product bundle selection price data and return's the ID.
     *
     * @param array $productBundleSelectionPrice The product bundle selection price data to persist
     *
     * @return void
     */
    public function persistProductBundleSelectionPrice($productBundleSelectionPrice);
}
