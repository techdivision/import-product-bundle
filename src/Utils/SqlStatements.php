<?php

/**
 * TechDivision\Import\Product\Bundle\Utils\SqlStatements
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

namespace TechDivision\Import\Product\Bundle\Utils;

/**
 * A SSB providing process registry functionality.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class SqlStatements
{

    /**
     * This is a utility class, so protect it against direct
     * instantiation.
     */
    private function __construct()
    {
    }

    /**
     * This is a utility class, so protect it against cloning.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * The SQL statement to create a new product bundle option.
     *
     * @var string
     */
    const CREATE_PRODUCT_BUNDLE_OPTION = 'INSERT
                                            INTO catalog_product_bundle_option (
                                                     parent_id,
                                                     required,
                                                     position,
                                                     type
                                                   )
                                            VALUES (?, ?, ?, ?)';

    /**
     * The SQL statement to create a new product bundle option value.
     *
     * @var string
     */
    const CREATE_PRODUCT_BUNDLE_OPTION_VALUE = 'INSERT
                                                  INTO catalog_product_bundle_option_value (
                                                           option_id,
                                                           store_id,
                                                           title
                                                       )
                                                VALUES (?, ?, ?)';

    /**
     * The SQL statement to create a new product bundle selection.
     *
     * @var string
     */
    const CREATE_PRODUCT_BUNDLE_SELECTION = 'INSERT
                                               INTO catalog_product_bundle_selection (
                                                        option_id,
                                                        parent_product_id,
                                                        product_id,
                                                        position,
                                                        is_default,
                                                        selection_price_type,
                                                        selection_price_value,
                                                        selection_qty,
                                                        selection_can_change_qty
                                                    )
                                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';

    /**
     * The SQL statement to create a new product bundle selection price.
     *
     * @var string
     */
    const CREATE_PRODUCT_BUNDLE_SELECTION_PRICE = 'INSERT
                                                     INTO catalog_product_bundle_selection_price (
                                                              selection_id,
                                                              website_id,
                                                              selection_price_type,
                                                              selection_price_value
                                                          )
                                                   VALUES (?, ?, ?, ?)';
}