<?php

/**
 * TechDivision\Import\Product\Bundle\Services\ProductBundleProcessorFactory
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/wagnert/csv-import
 * @link      http://www.appserver.io
 */

namespace TechDivision\Import\Product\Bundle\Services;

use TechDivision\Import\ConfigurationInterface;
use TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionAction;
use TechDivision\Import\Product\Bundle\Actions\ProductBundleOptionValueAction;
use TechDivision\Import\Product\Bundle\Actions\ProductBundleSelectionAction;
use TechDivision\Import\Product\Bundle\Actions\ProductBundleSelectionPriceAction;
use TechDivision\Import\Product\Bundle\Actions\Processors\ProductBundleOptionPersistProcessor;
use TechDivision\Import\Product\Bundle\Actions\Processors\ProductBundleOptionValuePersistProcessor;
use TechDivision\Import\Product\Bundle\Actions\Processors\ProductBundleSelectionPersistProcessor;
use TechDivision\Import\Product\Bundle\Actions\Processors\ProductBundleSelectionPricePersistProcessor;

/**
 * A SLSB providing methods to load product data using a PDO connection.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/wagnert/csv-import
 * @link      http://www.appserver.io
 */
class ProductBundleProcessorFactory
{

    /**
     * Factory method to create a new product bundle processor instance.
     *
     * @param \PDO                                       $connection    The PDO connection to use
     * @param TechDivision\Import\ConfigurationInterface $configuration The subject configuration
     *
     * @return \TechDivision\Import\Product\Bundle\Services\ProductBundleProcessor The processor instance
     */
    public function factory(\PDO $connection, ConfigurationInterface $configuration)
    {

        // extract Magento edition/version
        $magentoEdition = $configuration->getMagentoEdition();
        $magentoVersion = $configuration->getMagentoVersion();

        // initialize the action that provides product bundle option CRUD functionality
        $productBundleOptionPersistProcessor = new ProductBundleOptionPersistProcessor();
        $productBundleOptionPersistProcessor->setMagentoEdition($magentoEdition);
        $productBundleOptionPersistProcessor->setMagentoVersion($magentoVersion);
        $productBundleOptionPersistProcessor->setConnection($connection);
        $productBundleOptionPersistProcessor->init();
        $productBundleOptionAction = new ProductBundleOptionAction();
        $productBundleOptionAction->setPersistProcessor($productBundleOptionPersistProcessor);

        // initialize the action that provides product bundle option CRUD functionality
        $productBundleOptionValuePersistProcessor = new ProductBundleOptionValuePersistProcessor();
        $productBundleOptionValuePersistProcessor->setMagentoEdition($magentoEdition);
        $productBundleOptionValuePersistProcessor->setMagentoVersion($magentoVersion);
        $productBundleOptionValuePersistProcessor->setConnection($connection);
        $productBundleOptionValuePersistProcessor->init();
        $productBundleOptionValueAction = new ProductBundleOptionValueAction();
        $productBundleOptionValueAction->setPersistProcessor($productBundleOptionValuePersistProcessor);

        // initialize the action that provides product bundle option CRUD functionality
        $productBundleSelectionPersistProcessor = new ProductBundleSelectionPersistProcessor();
        $productBundleSelectionPersistProcessor->setMagentoEdition($magentoEdition);
        $productBundleSelectionPersistProcessor->setMagentoVersion($magentoVersion);
        $productBundleSelectionPersistProcessor->setConnection($connection);
        $productBundleSelectionPersistProcessor->init();
        $productBundleSelectionAction = new ProductBundleSelectionAction();
        $productBundleSelectionAction->setPersistProcessor($productBundleSelectionPersistProcessor);

        // initialize the action that provides product bundle option CRUD functionality
        $productBundleSelectionPricePersistProcessor = new ProductBundleSelectionPricePersistProcessor();
        $productBundleSelectionPricePersistProcessor->setMagentoEdition($magentoEdition);
        $productBundleSelectionPricePersistProcessor->setMagentoVersion($magentoVersion);
        $productBundleSelectionPricePersistProcessor->setConnection($connection);
        $productBundleSelectionPricePersistProcessor->init();
        $productBundleSelectionPriceAction = new ProductBundleSelectionPriceAction();
        $productBundleSelectionPriceAction->setPersistProcessor($productBundleSelectionPricePersistProcessor);

        // initialize the product bundle processor
        $productBundleProcessor = new ProductBundleProcessor();
        $productBundleProcessor->setConnection($connection);
        $productBundleProcessor->setProductBundleOptionAction($productBundleOptionAction);
        $productBundleProcessor->setProductBundleOptionValueAction($productBundleOptionValueAction);
        $productBundleProcessor->setProductBundleSelectionAction($productBundleSelectionAction);
        $productBundleProcessor->setProductBundleSelectionPriceAction($productBundleSelectionPriceAction);

        // return the instance
        return $productBundleProcessor;
    }
}
