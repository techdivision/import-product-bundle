<?php

/**
 * TechDivision\Import\Product\Bundle\Observers\ProductBundleObserver
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

use TechDivision\Import\Utils\ProductTypes;
use TechDivision\Import\Product\Bundle\Utils\ColumnKeys;
use TechDivision\Import\Product\Observers\AbstractProductImportObserver;

/**
 * A SLSB that handles the process to import product bunches.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class ProductBundleObserver extends AbstractProductImportObserver
{

    /**
     * The artefact type.
     *
     * @var string
     */
    const ARTEFACT_TYPE = 'bundles';

    /**
     *
     * @var array
     */
    protected $columns = array(
        'name'           => array(ColumnKeys::BUNDLE_VALUE_NAME => ColumnKeys::BUNDLE_VALUES),
        'type'           => array(ColumnKeys::BUNDLE_VALUE_TYPE => ColumnKeys::BUNDLE_VALUES),
        'required'       => array(ColumnKeys::BUNDLE_VALUE_REQUIRED => ColumnKeys::BUNDLE_VALUES),
        'sku'            => array(ColumnKeys::BUNDLE_VALUE_SKU => ColumnKeys::BUNDLE_VALUES),
        'price'          => array(ColumnKeys::BUNDLE_VALUE_PRICE => ColumnKeys::BUNDLE_VALUES),
        'default'        => array(ColumnKeys::BUNDLE_VALUE_DEFAULT => ColumnKeys::BUNDLE_VALUES),
        'default_qty'    => array(ColumnKeys::BUNDLE_VALUE_DEFAULT_QTY => ColumnKeys::BUNDLE_VALUES),
        'price_type'     => array(ColumnKeys::BUNDLE_VALUE_PRICE_TYPE => ColumnKeys::BUNDLE_VALUES),
        'can_change_qty' => array(ColumnKeys::BUNDLE_VALUE_CAN_CHANGE_QTY => ColumnKeys::BUNDLE_VALUES),
        'position'       => array(ColumnKeys::BUNDLE_VALUE_POSITION => ColumnKeys::BUNDLE_VALUES)
    );

    /**
     * Process the observer's business logic.
     *
     * @return array The processed row
     */
    protected function process()
    {

        // query whether or not we've found a bundle product
        if ($this->getValue(ColumnKeys::PRODUCT_TYPE) !== ProductTypes::BUNDLE) {
            return;
        }

        // query whether or not, we've a bundle
        if ($bundleValues = $this->getValue(ColumnKeys::BUNDLE_VALUES)) {
            // initialize the array for the product bundles
            $artefacts = array();

            // load the parent SKU from the row
            $parentSku = $this->getValue(ColumnKeys::SKU);

            // initialize the bundle with the found values
            foreach ($this->explode($bundleValues, '|') as $bundleValue) {
                // initialize the product bundle itself
                $bundle = $this->newArtefact(
                    array(
                        ColumnKeys::BUNDLE_PARENT_SKU    => $parentSku,
                        ColumnKeys::STORE_VIEW_CODE      => $this->getValue(ColumnKeys::STORE_VIEW_CODE),
                        ColumnKeys::BUNDLE_SKU_TYPE      => $this->getValue(ColumnKeys::BUNDLE_SKU_TYPE),
                        ColumnKeys::BUNDLE_PRICE_TYPE    => $this->getValue(ColumnKeys::BUNDLE_PRICE_TYPE),
                        ColumnKeys::BUNDLE_PRICE_VIEW    => $this->getValue(ColumnKeys::BUNDLE_PRICE_VIEW),
                        ColumnKeys::BUNDLE_WEIGHT_TYPE   => $this->getValue(ColumnKeys::BUNDLE_WEIGHT_TYPE),
                        ColumnKeys::BUNDLE_SHIPMENT_TYPE => $this->getValue(ColumnKeys::BUNDLE_SHIPMENT_TYPE),
                    ),
                    array(
                        ColumnKeys::BUNDLE_PARENT_SKU           => ColumnKeys::SKU,
                        ColumnKeys::STORE_VIEW_CODE             => ColumnKeys::STORE_VIEW_CODE,
                        ColumnKeys::BUNDLE_SKU_TYPE             => ColumnKeys::BUNDLE_SKU_TYPE,
                        ColumnKeys::BUNDLE_PRICE_TYPE           => ColumnKeys::BUNDLE_PRICE_TYPE,
                        ColumnKeys::BUNDLE_PRICE_VIEW           => ColumnKeys::BUNDLE_PRICE_VIEW,
                        ColumnKeys::BUNDLE_WEIGHT_TYPE          => ColumnKeys::BUNDLE_WEIGHT_TYPE,
                        ColumnKeys::BUNDLE_SHIPMENT_TYPE        => ColumnKeys::BUNDLE_SHIPMENT_TYPE
                    )
                );

                // initialize the columns
                foreach ($this->columns as $columnKey => $originalColumName) {
                    $bundle[$columnKey] = null;
                }

                // set the values
                $values = array();
                foreach (explode(',', $bundleValue) as $values) {
                    // extract the column key => value pair
                    list ($key, $value) = explode('=', $values);
                    // query whether or not we've to append the column
                    if (isset($this->columns[$key])) {
                        $bundle[$this->columns[$key]] = $value;
                    }
                }

                // prepare and append the bundle data
                $artefacts[] = $bundle;
            }

            // append the bundles to the subject
            $this->addArtefacts($artefacts);
        }
    }

    /**
     * Create's and return's a new empty artefact entity.
     *
     * @param array $columns             The array with the column data
     * @param array $originalColumnNames The array with a mapping from the old to the new column names
     *
     * @return array The new artefact entity
     */
    protected function newArtefact(array $columns, array $originalColumnNames)
    {
        return $this->getSubject()->newArtefact($columns, $originalColumnNames);
    }

    /**
     * Add the passed product type artefacts to the product with the
     * last entity ID.
     *
     * @param array $artefacts The product type artefacts
     *
     * @return void
     * @uses \TechDivision\Import\Product\Bundle\Subjects\BunchSubject::getLastEntityId()
     */
    protected function addArtefacts(array $artefacts)
    {
        $this->getSubject()->addArtefacts(ProductBundleObserver::ARTEFACT_TYPE, $artefacts);
    }
}
