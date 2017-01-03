<?php

/**
 * TechDivision\Import\Product\Bundle\Services\ProductProcessor
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

/**
 * A SLSB providing methods to load product data using a PDO connection.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class ProductBundleProcessor implements ProductBundleProcessorInterface
{

    /**
     * A PDO connection initialized with the values from the Doctrine EntityManager.
     *
     * @var \PDO
     */
    protected $connection;

    /**
     * The repository to access EAV attribute option values.
     *
     * @var \TechDivision\Import\Repositories\EavAttributeOptionValueRepository
     */
    protected $eavAttributeOptionValueRepository;

    /**
     * The action for product bundle option CRUD methods.
     *
     * @var \TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionAction
     */
    protected $productBundleOptionAction;

    /**
     * The action for product bundle option value CRUD methods.
     *
     * @var \TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionValueAction
     */
    protected $productBundleOptionValueAction;

    /**
     * The action for product bundle selection CRUD methods.
     *
     * @var \TechDivision\Import\Product\Bundle\Actions\ProductBundleSelectionAction
     */
    protected $productBundleSelectionAction;

    /**
     * The action for product bundle selection price CRUD methods.
     *
     * @var \TechDivision\Import\Product\Bundle\Actions\ProductBundleSelectionPriceAction
     */
    protected $productBundleSelectionPriceAction;

    /**
     * The repository to load bundle option data.
     *
     * @var \TechDivision\Import\Product\Bundle\Respository\BundleOptionRepository
     */
    protected $bundleOptionRespository;

    /**
     * The repository to load bundle option value data.
     *
     * @var \TechDivision\Import\Product\Bundle\Respository\BundleOptionValueRepository
     */
    protected $bundleOptionValueRespository;

    /**
     * The repository to load bundle selection data.
     *
     * @var \TechDivision\Import\Product\Bundle\Respository\BundleSelectionRepository
     */
    protected $bundleSelectionRespository;

    /**
     * The repository to load bundle selection price data.
     *
     * @var \TechDivision\Import\Product\Bundle\Respository\BundleSelectionPriceRepository
     */
    protected $bundleSelectionPriceRespository;

    /**
     * Set's the passed connection.
     *
     * @param \PDO $connection The connection to set
     *
     * @return void
     */
    public function setConnection(\PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Return's the connection.
     *
     * @return \PDO The connection instance
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Turns off autocommit mode. While autocommit mode is turned off, changes made to the database via the PDO
     * object instance are not committed until you end the transaction by calling ProductProcessor::commit().
     * Calling ProductProcessor::rollBack() will roll back all changes to the database and return the connection
     * to autocommit mode.
     *
     * @return boolean Returns TRUE on success or FALSE on failure
     * @link http://php.net/manual/en/pdo.begintransaction.php
     */
    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }

    /**
     * Commits a transaction, returning the database connection to autocommit mode until the next call to
     * ProductProcessor::beginTransaction() starts a new transaction.
     *
     * @return boolean Returns TRUE on success or FALSE on failure
     * @link http://php.net/manual/en/pdo.commit.php
     */
    public function commit()
    {
        return $this->connection->commit();
    }

    /**
     * Rolls back the current transaction, as initiated by ProductProcessor::beginTransaction().
     *
     * If the database was set to autocommit mode, this function will restore autocommit mode after it has
     * rolled back the transaction.
     *
     * Some databases, including MySQL, automatically issue an implicit COMMIT when a database definition
     * language (DDL) statement such as DROP TABLE or CREATE TABLE is issued within a transaction. The implicit
     * COMMIT will prevent you from rolling back any other changes within the transaction boundary.
     *
     * @return boolean Returns TRUE on success or FALSE on failure
     * @link http://php.net/manual/en/pdo.rollback.php
     */
    public function rollBack()
    {
        return $this->connection->rollBack();
    }

    /**
     * Set's the repository to access EAV attribute option values.
     *
     * @param \TechDivision\Import\Repositories\EavAttributeOptionValueRepository $eavAttributeOptionValueRepository The repository to access EAV attribute option values
     *
     * @return void
     */
    public function setEavAttributeOptionValueRepository($eavAttributeOptionValueRepository)
    {
        $this->eavAttributeOptionValueRepository = $eavAttributeOptionValueRepository;
    }

    /**
     * Return's the repository to access EAV attribute option values.
     *
     * @return \TechDivision\Import\Repositories\EavAttributeOptionValueRepository The repository instance
     */
    public function getEavAttributeOptionValueRepository()
    {
        return $this->eavAttributeOptionValueRepository;
    }

    /**
     * Set's the action with the product bundle option CRUD methods.
     *
     * @param \TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionAction $productBundleOptionAction The action with the product bundle option CRUD methods
     *
     * @return void
     */
    public function setProductBundleOptionAction($productBundleOptionAction)
    {
        $this->productBundleOptionAction = $productBundleOptionAction;
    }

    /**
     * Return's the action with the product bundle option CRUD methods.
     *
     * @return \TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionAction The action instance
     */
    public function getProductBundleOptionAction()
    {
        return $this->productBundleOptionAction;
    }

    /**
     * Set's the action with the product bundle option value CRUD methods.
     *
     * @param \TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionValueAction $productBundleOptionValueAction The action with the product bundle option value CRUD methods
     *
     * @return void
     */
    public function setProductBundleOptionValueAction($productBundleOptionValueAction)
    {
        $this->productBundleOptionValueAction = $productBundleOptionValueAction;
    }

    /**
     * Return's the action with the product bundle option value CRUD methods.
     *
     * @return \TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionValueAction The action instance
     */
    public function getProductBundleOptionValueAction()
    {
        return $this->productBundleOptionValueAction;
    }

    /**
     * Set's the action with the product bundle selection CRUD methods.
     *
     * @param \TechDivision\Import\Product\Bundle\Actions\ProductBundleSelectionAction $productBundleSelectionAction The action with the product bundle selection CRUD methods
     *
     * @return void
     */
    public function setProductBundleSelectionAction($productBundleSelectionAction)
    {
        $this->productBundleSelectionAction = $productBundleSelectionAction;
    }

    /**
     * Return's the action with the product bundle selection CRUD methods.
     *
     * @return \TechDivision\Import\Product\Bundle\Actions\ProductBundleSelectionAction The action instance
     */
    public function getProductBundleSelectionAction()
    {
        return $this->productBundleSelectionAction;
    }

    /**
     * Set's the action with the product bundle selection price CRUD methods.
     *
     * @param \TechDivision\Import\Product\Bundle\Actions\ProductBundleSelectionPriceAction $productBundleSelectionPriceAction The action with the product bundle selection price CRUD methods
     *
     * @return void
     */
    public function setProductBundleSelectionPriceAction($productBundleSelectionPriceAction)
    {
        $this->productBundleSelectionPriceAction = $productBundleSelectionPriceAction;
    }

    /**
     * Return's the action with the product bundle selection price CRUD methods.
     *
     * @return \TechDivision\Import\Product\Bundle\Actions\ProductBundleSelectionPriceAction The action instance
     */
    public function getProductBundleSelectionPriceAction()
    {
        return $this->productBundleSelectionPriceAction;
    }

    /**
     * Set's the repository to load bundle option data.
     *
     * @param \TechDivision\Import\Product\Bundle\Repository\BundleOptionRespository $bundleOptionRespository The repository instance
     *
     * @return void
     */
    public function setBundleOptionRepository($bundleOptionRespository)
    {
        $this->bundleOptionRespository = $bundleOptionRespository;
    }

    /**
     * Return's the respository to load bundle option data.
     *
     * @return \TechDivision\Import\Product\Bundle\Repository\BundleOptionRespository The repository instance
     */
    public function getBundleOptionRepository()
    {
        return $this->bundleOptionRespository;
    }

    /**
     * Set's the repository to load bundle option value data.
     *
     * @param \TechDivision\Import\Product\Bundle\Repository\BundleOptionValueRespository $bundleOptionValueRespository The repository instance
     *
     * @return void
     */
    public function setBundleOptionValueRepository($bundleOptionValueRespository)
    {
        $this->bundleOptionValueRespository = $bundleOptionValueRespository;
    }

    /**
     * Return's the respository to load bundle option value data.
     *
     * @return \TechDivision\Import\Product\Bundle\Repository\BundleOptionValueRespository The repository instance
     */
    public function getBundleOptionValueRepository()
    {
        return $this->bundleOptionValueRespository;
    }

    /**
     * Set's the repository to load bundle selection data.
     *
     * @param \TechDivision\Import\Product\Bundle\Repository\BundleSelectionRespository $bundleSelectionRespository The repository instance
     *
     * @return void
     */
    public function setBundleSelectionRepository($bundleSelectionRespository)
    {
        $this->bundleSelectionRespository = $bundleSelectionRespository;
    }

    /**
     * Return's the respository to load bundle selection data.
     *
     * @return \TechDivision\Import\Product\Bundle\Repository\BundleSelectionRespository The repository instance
     */
    public function getBundleSelectionRepository()
    {
        return $this->bundleSelectionRespository;
    }

    /**
     * Set's the repository to load bundle selection price data.
     *
     * @param \TechDivision\Import\Product\Bundle\Repository\BundleSelectionPriceRespository $bundleSelectionPriceRespository The repository instance
     *
     * @return void
     */
    public function setBundleSelectionPriceRepository($bundleSelectionPriceRespository)
    {
        $this->bundleSelectionPriceRespository = $bundleSelectionPriceRespository;
    }

    /**
     * Return's the respository to load bundle selection price data.
     *
     * @return \TechDivision\Import\Product\Bundle\Repository\BundleSelectionPriceRespository The repository instance
     */
    public function getBundleSelectionPriceRepository()
    {
        return $this->bundleSelectionPriceRespository;
    }

    /**
     * Return's the attribute option value with the passed value and store ID.
     *
     * @param mixed   $value   The option value
     * @param integer $storeId The ID of the store
     *
     * @return array|boolean The attribute option value instance
     */
    public function getEavAttributeOptionValueByOptionValueAndStoreId($value, $storeId)
    {
        return $this->getEavAttributeOptionValueRepository()->findEavAttributeOptionValueByOptionValueAndStoreId($value, $storeId);
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
        return $this->getBundleOptionRepository()->findOneByNameAndStoreIdAndParentId($title, $storeId, $parentId);
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
        return $this->getBundleOptionValueRepository()->findOneByNameAndStoreIdAndParentId($title, $storeId, $parentId);
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
        return $this->getBundleSelectionRepository()->findOneByOptionIdAndProductIdAndParentProductId($optionId, $productId, $parentProductId);
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
        return $this->getBundleSelectionPriceRepository()->findOneByOptionIdAndProductIdAndParentProductId($selectionId, $websiteId);
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
        return $this->getProductBundleOptionAction()->persist($productBundleOption);
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
        $this->getProductBundleOptionValueAction()->persist($productBundleOptionValue);
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
        return $this->getProductBundleSelectionAction()->persist($productBundleSelection);
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
        return $this->getProductBundleSelectionPriceAction()->persist($productBundleSelectionPrice);
    }
}
