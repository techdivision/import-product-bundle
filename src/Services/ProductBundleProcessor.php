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

use TechDivision\Import\Connection\ConnectionInterface;
use TechDivision\Import\Product\Bundle\Repositories\BundleOptionRepository;
use TechDivision\Import\Product\Bundle\Repositories\BundleOptionValueRepository;
use TechDivision\Import\Product\Bundle\Repositories\BundleSelectionRepository;
use TechDivision\Import\Product\Bundle\Repositories\BundleSelectionPriceRepository;
use TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionAction;
use TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionValueAction;
use TechDivision\Import\Product\Bundle\Actions\ProductBundleSelectionAction;
use TechDivision\Import\Product\Bundle\Actions\ProductBundleSelectionPriceAction;
use TechDivision\Import\Product\Bundle\Repositories\BundleOptionRepositoryInterface;
use TechDivision\Import\Product\Bundle\Repositories\BundleOptionValueRepositoryInterface;
use TechDivision\Import\Product\Bundle\Repositories\BundleSelectionRepositoryInterface;
use TechDivision\Import\Product\Bundle\Repositories\BundleSelectionPriceRepositoryInterface;
use TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionActionInterface;
use TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionValueActionInterface;
use TechDivision\Import\Product\Bundle\Actions\ProductBundleSelectionActionInterface;
use TechDivision\Import\Product\Bundle\Actions\ProductBundleSelectionPriceActionInterface;
use TechDivision\Import\Product\Repositories\ProductRelationRepositoryInterface;
use TechDivision\Import\Product\Actions\ProductRelationActionInterface;

/**
 * The product bundle processor implementation.
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
     * @var \TechDivision\Import\Connection\ConnectionInterface
     */
    protected $connection;

    /**
     * The action for product bundle option CRUD methods.
     *
     * @var \TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionActionInterface
     */
    protected $productBundleOptionAction;

    /**
     * The action for product bundle option value CRUD methods.
     *
     * @var \TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionValueActionInterface
     */
    protected $productBundleOptionValueAction;

    /**
     * The action for product bundle selection CRUD methods.
     *
     * @var \TechDivision\Import\Product\Bundle\Actions\ProductBundleSelectionActionInterface
     */
    protected $productBundleSelectionAction;

    /**
     * The action for product bundle selection price CRUD methods.
     *
     * @var \TechDivision\Import\Product\Bundle\Actions\ProductBundleSelectionPriceActionInterface
     */
    protected $productBundleSelectionPriceAction;

    /**
     * The action for product relation CRUD methods.
     *
     * @var \TechDivision\Import\Product\Actions\ProductRelationActionInterface
     */
    protected $productRelationAction;

    /**
     * The repository to load bundle option data.
     *
     * @var \TechDivision\Import\Product\Bundle\Repositories\BundleOptionRepositoryInterface
     */
    protected $bundleOptionRespository;

    /**
     * The repository to load bundle option value data.
     *
     * @var \TechDivision\Import\Product\Bundle\Repositories\BundleOptionValueRepositoryInterface
     */
    protected $bundleOptionValueRespository;

    /**
     * The repository to load bundle selection data.
     *
     * @var \TechDivision\Import\Product\Bundle\Repositories\BundleSelectionRepositoryInterface
     */
    protected $bundleSelectionRespository;

    /**
     * The repository to load bundle selection price data.
     *
     * @var \TechDivision\Import\Product\Bundle\Repositories\BundleSelectionPriceRepositoryInterface
     */
    protected $bundleSelectionPriceRespository;

    /**
     * The repository to access product relations.
     *
     * @var \TechDivision\Import\Product\Repositories\ProductRelationRepositoryInterface
     */
    protected $productRelationRepository;

    /**
     * Initialize the processor with the necessary assembler and repository instances.
     *
     * @param \TechDivision\Import\Connection\ConnectionInterface                                      $connection                        The connection to use
     * @param \TechDivision\Import\Product\Bundle\Repositories\BundleOptionRepositoryInterface         $bundleOptionRepository            The bundle option repository to use
     * @param \TechDivision\Import\Product\Bundle\Repositories\BundleOptionValueRepositoryInterface    $bundleOptionValueRepository       The bundle option value repository to use
     * @param \TechDivision\Import\Product\Bundle\Repositories\BundleSelectionRepositoryInterface      $bundleSelectionRepository         The bundle selection repository to use
     * @param \TechDivision\Import\Product\Bundle\Repositories\BundleSelectionPriceRepositoryInterface $bundleSelectionPriceRepository    The bundle selection price repository to use
     * @param \TechDivision\Import\Product\Repositories\ProductRelationRepositoryInterface             $productRelationRepository         The product relation repository to use
     * @param \TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionActionInterface           $productBundleOptionAction         The product bundle option action to use
     * @param \TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionValueActionInterface      $productBundleOptionValueAction    The product bundle option value action to use
     * @param \TechDivision\Import\Product\Bundle\Actions\ProductBundleSelectionActionInterface        $productBundleSelectionAction      The product bundle selection action to use
     * @param \TechDivision\Import\Product\Bundle\Actions\ProductBundleSelectionPriceActionInterface   $productBundleSelectionPriceAction The product bundle selection price action to use
     * @param \TechDivision\Import\Product\Actions\ProductRelationActionInterface                      $productRelationAction             The product relation action to use
     */
    public function __construct(
        ConnectionInterface $connection,
        BundleOptionRepositoryInterface $bundleOptionRepository,
        BundleOptionValueRepositoryInterface $bundleOptionValueRepository,
        BundleSelectionRepositoryInterface $bundleSelectionRepository,
        BundleSelectionPriceRepositoryInterface $bundleSelectionPriceRepository,
        ProductRelationRepositoryInterface $productRelationRepository,
        ProductBundleOptionActionInterface $productBundleOptionAction,
        ProductBundleOptionValueActionInterface $productBundleOptionValueAction,
        ProductBundleSelectionActionInterface $productBundleSelectionAction,
        ProductBundleSelectionPriceActionInterface $productBundleSelectionPriceAction,
        ProductRelationActionInterface $productRelationAction
    ) {
        $this->setConnection($connection);
        $this->setBundleOptionRepository($bundleOptionRepository);
        $this->setBundleOptionValueRepository($bundleOptionValueRepository);
        $this->setBundleSelectionRepository($bundleSelectionRepository);
        $this->setBundleSelectionPriceRepository($bundleSelectionPriceRepository);
        $this->setProductRelationRepository($productRelationRepository);
        $this->setProductBundleOptionAction($productBundleOptionAction);
        $this->setProductBundleOptionValueAction($productBundleOptionValueAction);
        $this->setProductBundleSelectionAction($productBundleSelectionAction);
        $this->setProductBundleSelectionPriceAction($productBundleSelectionPriceAction);
        $this->setProductRelationAction($productRelationAction);
    }

    /**
     * Set's the passed connection.
     *
     * @param \TechDivision\Import\Connection\ConnectionInterface $connection The connection to set
     *
     * @return void
     */
    public function setConnection(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Return's the connection.
     *
     * @return \TechDivision\Import\Connection\ConnectionInterface The connection instance
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
     * Set's the action with the product bundle option CRUD methods.
     *
     * @param \TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionActionInterface $productBundleOptionAction The action with the product bundle option CRUD methods
     *
     * @return void
     */
    public function setProductBundleOptionAction(ProductBundleOptionActionInterface $productBundleOptionAction)
    {
        $this->productBundleOptionAction = $productBundleOptionAction;
    }

    /**
     * Return's the action with the product bundle option CRUD methods.
     *
     * @return \TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionActionInterface The action instance
     */
    public function getProductBundleOptionAction()
    {
        return $this->productBundleOptionAction;
    }

    /**
     * Set's the action with the product bundle option value CRUD methods.
     *
     * @param \TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionValueActionInterface $productBundleOptionValueAction The action with the product bundle option value CRUD methods
     *
     * @return void
     */
    public function setProductBundleOptionValueAction(ProductBundleOptionValueActionInterface $productBundleOptionValueAction)
    {
        $this->productBundleOptionValueAction = $productBundleOptionValueAction;
    }

    /**
     * Return's the action with the product bundle option value CRUD methods.
     *
     * @return \TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionValueActionInterface The action instance
     */
    public function getProductBundleOptionValueAction()
    {
        return $this->productBundleOptionValueAction;
    }

    /**
     * Set's the action with the product bundle selection CRUD methods.
     *
     * @param \TechDivision\Import\Product\Bundle\Actions\ProductBundleSelectionActionInterface $productBundleSelectionAction The action with the product bundle selection CRUD methods
     *
     * @return void
     */
    public function setProductBundleSelectionAction(ProductBundleSelectionActionInterface $productBundleSelectionAction)
    {
        $this->productBundleSelectionAction = $productBundleSelectionAction;
    }

    /**
     * Return's the action with the product bundle selection CRUD methods.
     *
     * @return \TechDivision\Import\Product\Bundle\Actions\ProductBundleSelectionActionInterface The action instance
     */
    public function getProductBundleSelectionAction()
    {
        return $this->productBundleSelectionAction;
    }

    /**
     * Set's the action with the product bundle selection price CRUD methods.
     *
     * @param \TechDivision\Import\Product\Bundle\Actions\ProductBundleSelectionPriceActionInterface $productBundleSelectionPriceAction The action with the product bundle selection price CRUD methods
     *
     * @return void
     */
    public function setProductBundleSelectionPriceAction(ProductBundleSelectionPriceActionInterface $productBundleSelectionPriceAction)
    {
        $this->productBundleSelectionPriceAction = $productBundleSelectionPriceAction;
    }

    /**
     * Return's the action with the product bundle selection price CRUD methods.
     *
     * @return \TechDivision\Import\Product\Bundle\Actions\ProductBundleSelectionPriceActionInterface The action instance
     */
    public function getProductBundleSelectionPriceAction()
    {
        return $this->productBundleSelectionPriceAction;
    }

    /**
     * Set's the action with the product relation CRUD methods.
     *
     * @param \TechDivision\Import\Product\Actions\ProductRelationActionInterface $productRelationAction The action with the product relation CRUD methods
     *
     * @return void
     */
    public function setProductRelationAction(ProductRelationActionInterface $productRelationAction)
    {
        $this->productRelationAction = $productRelationAction;
    }

    /**
     * Return's the action with the product relation CRUD methods.
     *
     * @return \TechDivision\Import\Product\Actions\ProductRelationActionInterface The action instance
     */
    public function getProductRelationAction()
    {
        return $this->productRelationAction;
    }

    /**
     * Set's the repository to load bundle option data.
     *
     * @param \TechDivision\Import\Product\Bundle\Repositories\BundleOptionRepositoryInterface $bundleOptionRespository The repository instance
     *
     * @return void
     */
    public function setBundleOptionRepository(BundleOptionRepositoryInterface $bundleOptionRespository)
    {
        $this->bundleOptionRespository = $bundleOptionRespository;
    }

    /**
     * Return's the respository to load bundle option data.
     *
     * @return \TechDivision\Import\Product\Bundle\Repositories\BundleOptionRepositoryInterface The repository instance
     */
    public function getBundleOptionRepository()
    {
        return $this->bundleOptionRespository;
    }

    /**
     * Set's the repository to load bundle option value data.
     *
     * @param \TechDivision\Import\Product\Bundle\Repositories\BundleOptionValueRepositoryInterface $bundleOptionValueRespository The repository instance
     *
     * @return void
     */
    public function setBundleOptionValueRepository(BundleOptionValueRepositoryInterface $bundleOptionValueRespository)
    {
        $this->bundleOptionValueRespository = $bundleOptionValueRespository;
    }

    /**
     * Return's the respository to load bundle option value data.
     *
     * @return \TechDivision\Import\Product\Bundle\Repositories\BundleOptionValueRepositoryInterface The repository instance
     */
    public function getBundleOptionValueRepository()
    {
        return $this->bundleOptionValueRespository;
    }

    /**
     * Set's the repository to load bundle selection data.
     *
     * @param \TechDivision\Import\Product\Bundle\Repositories\BundleSelectionRepositoryInterface $bundleSelectionRespository The repository instance
     *
     * @return void
     */
    public function setBundleSelectionRepository(BundleSelectionRepositoryInterface $bundleSelectionRespository)
    {
        $this->bundleSelectionRespository = $bundleSelectionRespository;
    }

    /**
     * Return's the respository to load bundle selection data.
     *
     * @return \TechDivision\Import\Product\Bundle\Repositories\BundleSelectionRepositoryInterface The repository instance
     */
    public function getBundleSelectionRepository()
    {
        return $this->bundleSelectionRespository;
    }

    /**
     * Set's the repository to load bundle selection price data.
     *
     * @param \TechDivision\Import\Product\Bundle\Repositories\BundleSelectionPriceRepositoryInterface $bundleSelectionPriceRespository The repository instance
     *
     * @return void
     */
    public function setBundleSelectionPriceRepository(BundleSelectionPriceRepositoryInterface $bundleSelectionPriceRespository)
    {
        $this->bundleSelectionPriceRespository = $bundleSelectionPriceRespository;
    }

    /**
     * Return's the respository to load bundle selection price data.
     *
     * @return \TechDivision\Import\Product\Bundle\Repositories\BundleSelectionPriceRepositoryInterface The repository instance
     */
    public function getBundleSelectionPriceRepository()
    {
        return $this->bundleSelectionPriceRespository;
    }

    /**
     * Set's the repository to access product relations.
     *
     * @param \TechDivision\Import\Product\Repositories\ProductRelationRepositoryInterface $productRelationRepository The repository instance
     *
     * @return void
     */
    public function setProductRelationRepository(ProductRelationRepositoryInterface $productRelationRepository)
    {
        $this->productRelationRepository = $productRelationRepository;
    }

    /**
     * Return's the repository to access product relations.
     *
     * @return \TechDivision\Import\Product\Repositories\ProductRelationRepositoryInterface The repository instance
     */
    public function getProductRelationRepository()
    {
        return $this->productRelationRepository;
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
     * Load's the bundle selection price with the passed selection/parent product/website ID.
     *
     * @param integer $selectionId     The selection ID of the bundle selection price to be returned
     * @param integer $parentProductId The parent product ID of the bundle selection price to be returned
     * @param integer $websiteId       The website ID of the bundle selection price to be returned
     *
     * @return array The bundle selection price
     */
    public function loadBundleSelectionPrice($selectionId, $parentProductId, $websiteId)
    {
        return $this->getBundleSelectionPriceRepository()->findOneByOptionIdAndProductIdAndParentProductId($selectionId, $parentProductId, $websiteId);
    }

    /**
     * Load's the product relation with the passed parent/child ID.
     *
     * @param integer $parentId The entity ID of the product relation's parent product
     * @param integer $childId  The entity ID of the product relation's child product
     *
     * @return array The product relation
     */
    public function loadProductRelation($parentId, $childId)
    {
        return $this->getProductRelationRepository()->findOneByParentIdAndChildId($parentId, $childId);
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

    /**
     * Persist's the passed product relation data and return's the ID.
     *
     * @param array       $productRelation The product relation data to persist
     * @param string|null $name            The name of the prepared statement that has to be executed
     *
     * @return void
     */
    public function persistProductRelation($productRelation, $name = null)
    {
        return $this->getProductRelationAction()->persist($productRelation, $name);
    }
}
