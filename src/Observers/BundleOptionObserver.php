<?php

/**
 * TechDivision\Import\Product\Bundle\Observers\BundleOptionObserver
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

use TechDivision\Import\Dbal\Utils\EntityStatus;
use TechDivision\Import\Utils\StoreViewCodes;
use TechDivision\Import\Utils\BackendTypeKeys;
use TechDivision\Import\Observers\StateDetectorInterface;
use TechDivision\Import\Observers\AttributeLoaderInterface;
use TechDivision\Import\Observers\DynamicAttributeObserverInterface;
use TechDivision\Import\Observers\EntityMergers\EntityMergerInterface;
use TechDivision\Import\Product\Observers\AbstractProductImportObserver;
use TechDivision\Import\Product\Bundle\Utils\ColumnKeys;
use TechDivision\Import\Product\Bundle\Utils\MemberNames;
use TechDivision\Import\Product\Bundle\Utils\EntityTypeCodes;
use TechDivision\Import\Product\Bundle\Services\ProductBundleProcessorInterface;

/**
 * Oberserver that provides functionality for the bundle option replace operation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class BundleOptionObserver extends AbstractProductImportObserver implements DynamicAttributeObserverInterface
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
     * The entity merger instance.
     *
     * @var \TechDivision\Import\Observers\EntityMergers\EntityMergerInterface
     */
    protected $entityMerger;

    /**
     * Initialize the "dymanmic" columns.
     *
     * @var array
     */
    protected $columns = array(MemberNames::POSITION => array(ColumnKeys::BUNDLE_VALUE_POSITION, BackendTypeKeys::BACKEND_TYPE_INT));

    /**
     * Initialize the observer with the passed product bundle processor instance.
     *
     * @param \TechDivision\Import\Product\Bundle\Services\ProductBundleProcessorInterface $productBundleProcessor The product bundle processor instance
     * @param \TechDivision\Import\Observers\AttributeLoaderInterface|null                 $attributeLoader        The attribute loader instance
     * @param \TechDivision\Import\Observers\EntityMergers\EntityMergerInterface           $entityMerger           The entity merger instance
     * @param \TechDivision\Import\Observers\StateDetectorInterface|null                   $stateDetector          The state detector instance
     */
    public function __construct(
        ProductBundleProcessorInterface $productBundleProcessor,
        AttributeLoaderInterface $attributeLoader = null,
        EntityMergerInterface $entityMerger = null,
        StateDetectorInterface $stateDetector = null
    ) {

        // initialize the product bundle processor, the attribute loader and entity merger instance
        $this->productBundleProcessor = $productBundleProcessor;
        $this->attributeLoader = $attributeLoader;
        $this->entityMerger = $entityMerger;

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

        // load the name and the parent SKU
        $name = $this->getValue(ColumnKeys::BUNDLE_VALUE_NAME);
        $parentSku = $this->getValue(ColumnKeys::BUNDLE_PARENT_SKU);

        // query whether or not the option has already been created for the parent SKU
        if ($this->exists($parentSku, $name)) {
            return;
        }

        // load and persist the product bundle option
        $optionId = $this->persistProductBundleOption($this->initializeBundleOption($this->prepareDynamicAttributes()));

        // store the parent SKU => name mapping
        $this->addParentSkuNameMapping(array($parentSku => array($name => $optionId)));
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

        try {
            // load and map the parent SKU
            $parentId = $this->mapSku($this->getValue(ColumnKeys::BUNDLE_PARENT_SKU));
        } catch (\Exception $e) {
            throw $this->wrapException(array(ColumnKeys::BUNDLE_PARENT_SKU), $e);
        }

        // extract the parent/child ID as well as type and position
        $type = $this->getValue(ColumnKeys::BUNDLE_VALUE_TYPE);
        $required = $this->getValue(ColumnKeys::BUNDLE_VALUE_REQUIRED);

        // return the prepared product
        return $this->initializeEntity(
            $this->loadRawEntity(
                array(
                    MemberNames::PARENT_ID => $parentId,
                    MemberNames::REQUIRED  => $required,
                    MemberNames::TYPE      => $type
                )
            )
        );
    }

    /**
     * Merge's and return's the entity with the passed attributes and set's the
     * passed status.
     *
     * @param array       $entity        The entity to merge the attributes into
     * @param array       $attr          The attributes to be merged
     * @param string|null $changeSetName The change set name to use
     *
     * @return array The merged entity
     * @todo https://github.com/techdivision/import/issues/179
     */
    protected function mergeEntity(array $entity, array $attr, $changeSetName = null)
    {
        return array_merge(
            $entity,
            $this->entityMerger ? $this->entityMerger->merge($this, $entity, $attr) : $attr,
            array(EntityStatus::MEMBER_NAME => $this->detectState($entity, $attr, $changeSetName))
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
        return $this->getProductBundleProcessor()->loadRawEntity(EntityTypeCodes::CATALOG_PRODUCT_BUNDLE_OPTION, $data);
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
     * @deprecated Since 22.0.0
     */
    protected function resetPositionCounter()
    {
        $this->getSubject()->resetPositionCounter();
    }

    /**
     * Add's the passed mapping to the subject.
     *
     * @param array $mapping The mapping to add
     *
     * @return void
     */
    protected function addParentSkuNameMapping($mapping = array())
    {
        $this->getSubject()->addParentSkuNameMapping($mapping);
    }

    /**
     * Query whether or not the option for the passed parent SKU and name has already been created.
     *
     * @param string $parentSku The parent SKU to query for
     * @param string $name      The option name to query for
     *
     * @return boolean TRUE if the option already exists, else FALSE
     */
    protected function exists($parentSku, $name)
    {
        return $this->getSubject()->exists($parentSku, $name);
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
        return $this->getProductBundleProcessor()->persistProductBundleOption($productBundleOption);
    }
}
