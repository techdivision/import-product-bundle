<?php

/**
 * TechDivision\Import\Product\Bundle\Repositories\BundleSelectionPriceRepositoryInterface
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Bundle\Repositories;

use TechDivision\Import\Dbal\Repositories\RepositoryInterface;

/**
 * Interface for repository implementations to load bundle selection price data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
interface BundleSelectionPriceRepositoryInterface extends RepositoryInterface
{

    /**
     * Load's the bundle selection price with the passed selection/parent product/website ID.
     *
     * @param integer $selectionId     The selection ID of the bundle selection price to be returned
     * @param integer $parentProductId The parent product ID of the bundle selection price to be returned
     * @param integer $websiteId       The website ID of the bundle selection price to be returned
     *
     * @return array The bundle selection price
     */
    public function findOneByOptionIdAndProductIdAndParentProductId($selectionId, $parentProductId, $websiteId);
}
