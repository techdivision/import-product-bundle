<?php

/**
 * TechDivision\Import\Product\Bundle\Repositories\BundleSelectionRepositoryInterface
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

use TechDivision\Import\Repositories\RepositoryInterface;

/**
 * Interface for repository implementations to load bundle selection data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
interface BundleSelectionRepositoryInterface extends RepositoryInterface
{

    /**
     * Load's the bundle selection value with the passed option/product/parent product ID.
     *
     * @param integer $optionId        The option ID of the bundle selection to be returned
     * @param integer $productId       The product ID of the bundle selection to be returned
     * @param integer $parentProductId The parent product ID of the bundle selection to be returned
     *
     * @return array The bundle selection
     */
    public function findOneByOptionIdAndProductIdAndParentProductId($optionId, $productId, $parentProductId);
}
