<?php

/**
 * TechDivision\Import\Product\Bundle\Repositories\ProductBundleSelectionPriceAction
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
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Bundle\Actions;

use TechDivision\Import\Actions\AbstractAction;

/**
 * An action implementation that provides functionality for product bundle selection price CRUD actions.
 *
 * @author     Tim Wagner <t.wagner@techdivision.com>
 * @copyright  2019 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/techdivision/import-product-bundle
 * @link       http://www.techdivision.com
 * @deprecated Since version 8.0.0 use \TechDivision\Import\Actions\GenericIdentifierAction instead
 */
class ProductBundleSelectionPriceAction extends AbstractAction implements ProductBundleSelectionPriceActionInterface
{

    /**
     * Creates's the entity with the passed attributes.
     *
     * @param array       $row  The attributes of the entity to create
     * @param string|null $name The name of the prepared statement that has to be executed
     *
     * @return string The last inserted ID
     */
    public function create($row, $name = null)
    {
        return $this->getCreateProcessor()->execute($row, $name);
    }
}
