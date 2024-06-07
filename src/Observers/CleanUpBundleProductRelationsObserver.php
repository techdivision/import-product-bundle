<?php
/**
 * Copyright (c) 2023 TechDivision GmbH
 * All rights reserved
 *
 * This product includes proprietary software developed at TechDivision GmbH, Germany
 * For more information see https://www.techdivision.com/
 *
 * To obtain a valid license for using this software please contact us at
 * license@techdivision.com
 */
declare(strict_types=1);

namespace TechDivision\Import\Product\Bundle\Observers;

use Exception;
use TechDivision\Import\Observers\StateDetectorInterface;
use TechDivision\Import\Product\Bundle\Services\ProductBundleProcessorInterface;
use TechDivision\Import\Product\Observers\AbstractProductImportObserver;
use TechDivision\Import\Product\Bundle\Observers\ProductBundleObserver;
use TechDivision\Import\Product\Bundle\Utils\ColumnKeys;
use TechDivision\Import\Product\Bundle\Utils\ConfigurationKeys;
use TechDivision\Import\Utils\ProductTypes;

/**
 * @copyright Copyright (c) 2024 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * @link      https://www.techdivision.com/
 * @author    MET <met@techdivision.com>
 */
class CleanUpBundleProductRelationsObserver extends AbstractProductImportObserver
{
    /**
     * @var ProductBundleProcessorInterface
     */
    protected $productBundleProcessor;

    /**
     * @param ProductBundleProcessorInterface $productBundleProcessor
     * @param StateDetectorInterface|null $stateDetector
     */
    public function __construct(
        ProductBundleProcessorInterface $productBundleProcessor,
        StateDetectorInterface $stateDetector = null
    ) {
        parent::__construct($stateDetector);
        $this->productBundleProcessor = $productBundleProcessor;
    }

    /**
     * @return ProductBundleProcessorInterface
     */
    protected function getProductBundleProcessor()
    {
        return $this->productBundleProcessor;
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function process()
    {

        // query whether we've found a configurable product or not
        if ($this->getValue(ColumnKeys::PRODUCT_TYPE) !== ProductTypes::BUNDLE) {
            return;
        }

        // query whether the media gallery has to be cleaned up or not
        if ($this->getSubject()->getConfiguration()->hasParam(ConfigurationKeys::CLEAN_UP_BUNDLE) &&
            $this->getSubject()->getConfiguration()->getParam(ConfigurationKeys::CLEAN_UP_BUNDLE)
        ) {

            $this->cleanUpBundles();

            $this->getSubject()
                ->getSystemLogger()
                ->debug(
                    $this->getSubject()->appendExceptionSuffix(
                        sprintf(
                            'Successfully clean up variants for product with SKU "%s"',
                            $this->getValue(ColumnKeys::SKU)
                        )
                    )
                );
        }
    }

    /**
     * @return void
     * @throws Exception Is thrown, if either the variant children und attributes can not be deleted
     */
    protected function cleanUpBundles()
    {
        // TODO herausfinden wie
    }

    /**
     * Delete not exists import variants from database.
     *
     * @param int $parentProductId The ID of the parent product
     * @param array $childData The array of variants
     *
     * @return void
     * @throws Exception
     */
    protected function cleanUpVariantChildren($parentProductId, array $childData)
    {
        if (empty($childData)) {
            return;
        }

        $parentSku = $this->getValue(ColumnKeys::SKU);

        // TODO remove the old links
    }

    /**
     * Return's the PK to create the product => variant relation.
     *
     * @return integer The PK to create the relation with
     */
    protected function getLastPrimaryKey()
    {
        return $this->getLastEntityId();
    }
}
