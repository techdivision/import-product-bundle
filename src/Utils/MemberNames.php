<?php

/**
 * TechDivision\Import\Product\Bundle\Utils\MemberNames
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
 * Utility class containing the entities member names.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class MemberNames extends \TechDivision\Import\Product\Utils\MemberNames
{

    /**
     * Name for the member 'parent_id'.
     *
     * @var string
     */
    const PARENT_ID = 'parent_id';

    /**
     * Name for the member 'parent_product_id'.
     *
     * @var string
     */
    const PARENT_PRODUCT_ID = 'parent_product_id';

    /**
     * Name for the member 'required'.
     *
     * @var string
     */
    const REQUIRED = 'required';

    /**
     * Name for the member 'position'.
     *
     * @var string
     */
    const POSITION = 'position';

    /**
     * Name for the member 'type'.
     *
     * @var string
     */
    const TYPE = 'type';

    /**
     * Name for the member 'title'.
     *
     * @var string
     */
    const TITLE = 'title';

    /**
     * Name for the member 'child_id'.
     *
     * @var string
     */
    const CHILD_ID = 'child_id';

    /**
     * Name for the member 'is_default'.
     *
     * @var string
     */
    const IS_DEFAULT = 'is_default';

    /**
     * Name for the member 'selection_id'.
     *
     * @var string
     */
    const SELECTION_ID = 'selection_id';

    /**
     * Name for the member 'selection_price_type'.
     *
     * @var string
     */
    const SELECTION_PRICE_TYPE = 'selection_price_type';

    /**
     * Name for the member 'selection_price_value'.
     *
     * @var string
     */
    const SELECTION_PRICE_VALUE = 'selection_price_value';

    /**
     * Name for the member 'selection_qty'.
     *
     * @var string
     */
    const SELECTION_QTY = 'selection_qty';

    /**
     * Name for the member 'selection_can_change_qty'.
     *
     * @var string
     */
    const SELECTION_CAN_CHANGE_QTY = 'selection_can_change_qty';
}
