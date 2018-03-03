<?php

/**
 * TechDivision\Import\Product\Bundle\Utils\SqlStatementKeys
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
 * Utility class with the SQL statements to use.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class SqlStatementKeys extends \TechDivision\Import\Product\Utils\SqlStatementKeys
{

    /**
     * The SQL statement to load the bundle option with the passed name/parent/store ID.
     *
     * @var string
     */
    const BUNDLE_OPTION = 'bundle_option';

    /**
     * The SQL statement to load the bundle option value with the passed name/parent/store ID.
     *
     * @var string
     */
    const BUNDLE_OPTION_VALUE = 'bundle_option_value';

    /**
     * The SQL statement to load the bundle selection with the passed option/parent product/product ID.
     *
     * @var string
     */
    const BUNDLE_SELECTION = 'bundle_selection';

    /**
     * The SQL statement to load the bundle selection price with the passed selection/website ID.
     *
     * @var string
     */
    const BUNDLE_SELECTION_PRICE = 'bundle_selection_price';

    /**
     * The SQL statement to create a new product bundle option.
     *
     * @var string
     */
    const CREATE_PRODUCT_BUNDLE_OPTION = 'create.product_bundle_option';

    /**
     * The SQL statement to update an existion product bundle option.
     *
     * @var string
     */
    const UPDATE_PRODUCT_BUNDLE_OPTION = 'update.product_bundle_option';

    /**
     * The SQL statement to create a new product bundle option value.
     *
     * @var string
     */
    const CREATE_PRODUCT_BUNDLE_OPTION_VALUE = 'create.product_bundle_option_value';

    /**
     * The SQL statement to create a new product bundle selection.
     *
     * @var string
     */
    const CREATE_PRODUCT_BUNDLE_SELECTION = 'create.product_bundle_selection';

    /**
     * The SQL statement to update an existion product bundle selection.
     *
     * @var string
     */
    const UPDATE_PRODUCT_BUNDLE_SELECTION = 'update.product_bundle_selection';

    /**
     * The SQL statement to create a new product bundle selection price.
     *
     * @var string
     */
    const CREATE_PRODUCT_BUNDLE_SELECTION_PRICE = 'insert.product_bundle_selection_price';

    /**
     * The SQL statement to update an existion product bundle selection price.
     *
     * @var string
     */
    const UPDATE_PRODUCT_BUNDLE_SELECTION_PRICE = 'update.product_bundle_selection_price';
}
