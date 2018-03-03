<?php

/**
 * TechDivision\Import\Product\Bundle\Repositories\SqlStatementKeys
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

namespace TechDivision\Import\Product\Bundle\Repositories;

use TechDivision\Import\Product\Bundle\Utils\SqlStatementKeys;

/**
 * Repository class with the SQL statements to use.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class SqlStatementRepository extends \TechDivision\Import\Product\Repositories\SqlStatementRepository
{

    /**
     * The SQL statements.
     *
     * @var array
     */
    private $statements = array(
        SqlStatementKeys::BUNDLE_OPTION =>
            'SELECT t0.*
               FROM catalog_product_bundle_option t0
         INNER JOIN catalog_product_bundle_option_value t1
                 ON t0.parent_id = :parent_id
                AND t0.option_id = t1.option_id
                AND t1.title = :title
                AND t1.store_id = :store_id',
        SqlStatementKeys::BUNDLE_OPTION_VALUE =>
            'SELECT t0.*
               FROM catalog_product_bundle_option_value t0
         INNER JOIN catalog_product_bundle_option t1
                 ON t1.parent_id = :parent_id
                AND t0.option_id = t1.option_id
                AND t0.title = :title
                AND t0.store_id = :store_id',
        SqlStatementKeys::BUNDLE_SELECTION =>
            'SELECT *
               FROM catalog_product_bundle_selection
              WHERE option_id = :option_id
                AND parent_product_id = :parent_product_id
                AND product_id = :product_id',
        SqlStatementKeys::BUNDLE_SELECTION_PRICE =>
            'SELECT *
               FROM catalog_product_bundle_selection_price
              WHERE selection_id = :selection_id
                AND website_id = :website_id',
        SqlStatementKeys::CREATE_PRODUCT_BUNDLE_OPTION =>
            'INSERT
               INTO catalog_product_bundle_option
                    (parent_id,
                     required,
                     position,
                     type)
             VALUES (:parent_id,
                     :required,
                     :position,
                     :type)',
        SqlStatementKeys::UPDATE_PRODUCT_BUNDLE_OPTION =>
            'UPDATE catalog_product_bundle_option
                SET parent_id = :parent_id,
                    required = :required,
                    position = :position,
                    type = :type
              WHERE option_id = :option_id',
        SqlStatementKeys::CREATE_PRODUCT_BUNDLE_OPTION_VALUE =>
            'INSERT
               INTO catalog_product_bundle_option_value
                    (option_id,
                     store_id,
                     title)
             VALUES (:option_id,
                     :store_id,
                     :title)',
        SqlStatementKeys::CREATE_PRODUCT_BUNDLE_SELECTION =>
            'INSERT
               INTO catalog_product_bundle_selection
                    (option_id,
                     parent_product_id,
                     product_id,
                     position,
                     is_default,
                     selection_price_type,
                     selection_price_value,
                     selection_qty,
                     selection_can_change_qty)
             VALUES (:option_id,
                     :parent_product_id,
                     :product_id,
                     :position,
                     :is_default,
                     :selection_price_type,
                     :selection_price_value,
                     :selection_qty,
                     :selection_can_change_qty)',
        SqlStatementKeys::UPDATE_PRODUCT_BUNDLE_SELECTION =>
            'UPDATE catalog_product_bundle_selection
                SET option_id = :option_id,
                    parent_product_id = :parent_product_id,
                    product_id = :product_id,
                    position = :position,
                    is_default = :is_default,
                    selection_price_type = :selection_price_type,
                    selection_price_value = :selection_price_value,
                    selection_qty = :selection_qty,
                    selection_can_change_qty = :selection_can_change_qty
              WHERE selection_id = :selection_id',
        SqlStatementKeys::CREATE_PRODUCT_BUNDLE_SELECTION_PRICE =>
            'INSERT
               INTO catalog_product_bundle_selection_price
                    (selection_id,
                     website_id,
                     selection_price_type,
                     selection_price_value)
             VALUES (:selection_id,
                     :website_id,
                     :selection_price_type,
                     :selection_price_value)',
        SqlStatementKeys::UPDATE_PRODUCT_BUNDLE_SELECTION_PRICE =>
            'UPDATE catalog_product_bundle_selection_price
                SET selection_price_type = :selection_price_type,
                    selection_price_value = :selection_price_value
              WHERE selection_id = :selection_id
                AND website_id = :website_id'
    );

    /**
     * Initialize the the SQL statements.
     */
    public function __construct()
    {

        // merge the class statements
        foreach ($this->statements as $key => $statement) {
            $this->preparedStatements[$key] = $statement;
        }
    }
}
