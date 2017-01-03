<?php

/**
 * TechDivision\Import\Product\Bundle\Utils\ColumnKeys
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

use TechDivision\Import\Product\Utils\ColumnKeys as FallbackColumnKeys;

/**
 * Utility class containing the CSV column names.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class ColumnKeys extends FallbackColumnKeys
{

    /**
     * Name for the column 'product_type'.
     *
     * @var string
     */
    const PRODUCT_TYPE = 'product_type';

    /**
     * Name for the column 'store_view_code'.
     *
     * @var string
     */
    const STORE_VIEW_CODE = 'store_view_code';

    /**
     * Name for the column 'bundle_values'.
     *
     * @var string
     */
    const BUNDLE_VALUES = 'bundle_values';

    /**
     * Name for the column 'bundle_price_type'.
     *
     * @var string
     */
    const BUNDLE_PRICE_TYPE = 'bundle_price_type';

    /**
     * Name for the column 'bundle_sku_type'.
     *
     * @var string
     */
    const BUNDLE_SKU_TYPE = 'bundle_sku_type';

    /**
     * Name for the column 'bundle_price_view'.
     *
     * @var string
     */
    const BUNDLE_PRICE_VIEW = 'bundle_price_view';

    /**
     * Name for the column 'bundle_weight_type'.
     *
     * @var string
     */
    const BUNDLE_WEIGHT_TYPE = 'bundle_weight_type';

    /**
     * Name for the column 'bundle_shipment_type'.
     *
     * @var string
     */
    const BUNDLE_SHIPMENT_TYPE = 'bundle_shipment_type';

    /**
     * Name for the column 'bundle_parent_sku'.
     *
     * @var string
     */
    const BUNDLE_PARENT_SKU = 'bundle_parent_sku';

    /**
     * Name for the column 'bundle_value_name'.
     *
     * @var string
     */
    const BUNDLE_VALUE_NAME = 'bundle_value_name';

    /**
     * Name for the column 'bundle_value_type'.
     *
     * @var string
     */
    const BUNDLE_VALUE_TYPE = 'bundle_value_type';

    /**
     * Name for the column 'bundle_value_required'.
     *
     * @var string
     */
    const BUNDLE_VALUE_REQUIRED = 'bundle_value_required';

    /**
     * Name for the column 'bundle_value_sku'.
     *
     * @var string
     */
    const BUNDLE_VALUE_SKU = 'bundle_value_sku';

    /**
     * Name for the column 'bundle_value_price'.
     *
     * @var string
     */
    const BUNDLE_VALUE_PRICE = 'bundle_value_price';

    /**
     * Name for the column 'bundle_value_default'.
     *
     * @var string
     */
    const BUNDLE_VALUE_DEFAULT = 'bundle_value_default';

    /**
     * Name for the column 'bundle_value_default_qty'.
     *
     * @var string
     */
    const BUNDLE_VALUE_DEFAULT_QTY = 'bundle_value_default_qty';

    /**
     * Name for the column 'bundle_price_type'.
     *
     * @var string
     */
    const BUNDLE_VALUE_PRICE_TYPE = 'bundle_value_price_type';
}
