<?php

/**
 * TechDivision\Import\Product\Bundle\Repositories\BundleSelectionPriceRepository
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

use TechDivision\Import\Dbal\Collection\Repositories\AbstractRepository;
use TechDivision\Import\Product\Bundle\Utils\MemberNames;
use TechDivision\Import\Product\Bundle\Utils\SqlStatementKeys;

/**
 * Repository implementation to load bundle selection price data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class BundleSelectionPriceRepository extends AbstractRepository implements BundleSelectionPriceRepositoryInterface
{

    /**
     * The prepared statement to load a existing bundle selection.
     *
     * @var \PDOStatement
     */
    protected $bundleSelectionPriceStmt;

    /**
     * Initializes the repository's prepared statements.
     *
     * @return void
     */
    public function init()
    {

        // initialize the prepared statements
        $this->bundleSelectionPriceStmt =
            $this->getConnection()->prepare($this->loadStatement(SqlStatementKeys::BUNDLE_SELECTION_PRICE));
    }

    /**
     * Load's the bundle selection price with the passed selection/parent product/website ID.
     *
     * @param integer $selectionId     The selection ID of the bundle selection price to be returned
     * @param integer $parentProductId The parent product ID of the bundle selection price to be returned
     * @param integer $websiteId       The website ID of the bundle selection price to be returned
     *
     * @return array The bundle selection price
     */
    public function findOneByOptionIdAndProductIdAndParentProductId($selectionId, $parentProductId, $websiteId)
    {

        // initialize the params
        $params = array(
            MemberNames::SELECTION_ID      => $selectionId,
            MemberNames::PARENT_PRODUCT_ID => $parentProductId,
            MemberNames::WEBSITE_ID        => $websiteId
        );

        // load and return the bundle option with the passed selection/website ID
        $this->bundleSelectionPriceStmt->execute($params);
        return $this->bundleSelectionPriceStmt->fetch(\PDO::FETCH_ASSOC);
    }
}
