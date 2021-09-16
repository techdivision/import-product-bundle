<?php

/**
 * TechDivision\Import\Product\Bundle\Observers\ProductBundleObserver
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

use TechDivision\Import\Utils\ProductTypes;
use TechDivision\Import\Product\Bundle\Utils\ColumnKeys;
use TechDivision\Import\Product\Observers\AbstractProductImportObserver;

/**
 * A SLSB that handles the process to import product bunches.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
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
        'name'               => array(ColumnKeys::BUNDLE_VALUE_NAME, ColumnKeys::BUNDLE_VALUES),
        'type'               => array(ColumnKeys::BUNDLE_VALUE_TYPE, ColumnKeys::BUNDLE_VALUES),
        'required'           => array(ColumnKeys::BUNDLE_VALUE_REQUIRED, ColumnKeys::BUNDLE_VALUES),
        'sku'                => array(ColumnKeys::BUNDLE_VALUE_SKU, ColumnKeys::BUNDLE_VALUES),
        'price'              => array(ColumnKeys::BUNDLE_VALUE_PRICE, ColumnKeys::BUNDLE_VALUES),
        'default'            => array(ColumnKeys::BUNDLE_VALUE_DEFAULT, ColumnKeys::BUNDLE_VALUES),
        'default_qty'        => array(ColumnKeys::BUNDLE_VALUE_DEFAULT_QTY, ColumnKeys::BUNDLE_VALUES),
        'price_type'         => array(ColumnKeys::BUNDLE_VALUE_PRICE_TYPE, ColumnKeys::BUNDLE_VALUES),
        'can_change_qty'     => array(ColumnKeys::BUNDLE_VALUE_CAN_CHANGE_QTY, ColumnKeys::BUNDLE_VALUES),
        'position'           => array(ColumnKeys::BUNDLE_VALUE_POSITION, ColumnKeys::BUNDLE_VALUES),
        'selection_position' => array(ColumnKeys::BUNDLE_VALUE_SELECTION_POSITION, ColumnKeys::BUNDLE_VALUES)
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

            // explode the positions of the bundle values
            $bundleValuesPosition = $this->getValue(ColumnKeys::BUNDLE_VALUES_POSITION, array(), function ($value) {
                return $this->explode($value, $this->getMultipleFieldDelimiter());
            });

            // initialize the bundle with the found values
            foreach ($this->explode($bundleValues, $this->getMultipleValueDelimiter()) as $bundleValue) {
                // initialize the array with the columns
                $columns = array(
                    ColumnKeys::BUNDLE_PARENT_SKU    => $parentSku,
                    ColumnKeys::STORE_VIEW_CODE      => $this->getValue(ColumnKeys::STORE_VIEW_CODE),
                    ColumnKeys::BUNDLE_SKU_TYPE      => $this->getValue(ColumnKeys::BUNDLE_SKU_TYPE),
                    ColumnKeys::BUNDLE_PRICE_TYPE    => $this->getValue(ColumnKeys::BUNDLE_PRICE_TYPE),
                    ColumnKeys::BUNDLE_PRICE_VIEW    => $this->getValue(ColumnKeys::BUNDLE_PRICE_VIEW),
                    ColumnKeys::BUNDLE_WEIGHT_TYPE   => $this->getValue(ColumnKeys::BUNDLE_WEIGHT_TYPE),
                    ColumnKeys::BUNDLE_SHIPMENT_TYPE => $this->getValue(ColumnKeys::BUNDLE_SHIPMENT_TYPE),
                );

                // initialize the array with the original column names
                $originalColumNames = array(
                    ColumnKeys::BUNDLE_PARENT_SKU    => ColumnKeys::SKU,
                    ColumnKeys::STORE_VIEW_CODE      => ColumnKeys::STORE_VIEW_CODE,
                    ColumnKeys::BUNDLE_SKU_TYPE      => ColumnKeys::BUNDLE_SKU_TYPE,
                    ColumnKeys::BUNDLE_PRICE_TYPE    => ColumnKeys::BUNDLE_PRICE_TYPE,
                    ColumnKeys::BUNDLE_PRICE_VIEW    => ColumnKeys::BUNDLE_PRICE_VIEW,
                    ColumnKeys::BUNDLE_WEIGHT_TYPE   => ColumnKeys::BUNDLE_WEIGHT_TYPE,
                    ColumnKeys::BUNDLE_SHIPMENT_TYPE => ColumnKeys::BUNDLE_SHIPMENT_TYPE
                );

                // initialize the columns
                foreach ($this->columns as $column) {
                    // initialize column/original column name
                    $columnName = $originalColumName = null;
                    // explode the column and originl column name
                    if (sizeof($column) > 1) {
                        list ($columnName, $originalColumName) = $column;
                    }
                    // initialize the column
                    $columns[$columnName] = null;
                    // initialize the original column name
                    $originalColumNames[$columnName] = $originalColumName;
                }

                // explode the values
                $explodedValues = $this->explode($bundleValue, $this->getMultipleFieldDelimiter());

                // iterate over the given values and append them to the columns
                foreach ($explodedValues as $values) {
                    // initialize key/value
                    $key = $value = null;
                    // extract the column key => value pair
                    if (strpos($values, '=')) {
                        list ($key, $value) = $this->explode($values, '=');
                    }
                    // query whether or not we've to append the column
                    if (isset($this->columns[$key])) {
                        // explode the column name
                        list ($columnName, ) = $this->columns[$key];
                        // add the value
                        $columns[$columnName] = $value;
                    }
                }

                // iterate over the positions
                foreach ($bundleValuesPosition as $position) {
                    // initialize name/value
                    $name = $value = null;
                    // extract the column name => value pair
                    if (strpos($position, '=')) {
                        list ($name, $value) = $this->explode($position, '=');
                    }
                    // query whether or not we've to append the column
                    if (isset($columns[ColumnKeys::BUNDLE_VALUE_NAME]) && $columns[ColumnKeys::BUNDLE_VALUE_NAME] === $name) {
                        $columns[ColumnKeys::BUNDLE_VALUE_POSITION] = $value;
                    }
                }

                //  initialize the product bundle itself and append it to the artefacts
                $artefacts[] = $this->newArtefact($columns, $originalColumNames);
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
