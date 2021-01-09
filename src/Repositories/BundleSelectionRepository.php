<?php

/**
 * TechDivision\Import\Product\Bundle\Repositories\BundleSelectionRepository
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

use TechDivision\Import\Dbal\Collection\Repositories\AbstractRepository;
use TechDivision\Import\Product\Bundle\Utils\MemberNames;
use TechDivision\Import\Product\Bundle\Utils\SqlStatementKeys;

/**
 * Repository implementation to load bundle selection data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class BundleSelectionRepository extends AbstractRepository implements BundleSelectionRepositoryInterface
{

    /**
     * The prepared statement to load a existing bundle selection.
     *
     * @var \PDOStatement
     */
    protected $bundleSelectionStmt;

    /**
     * Initializes the repository's prepared statements.
     *
     * @return void
     */
    public function init()
    {

        // initialize the prepared statements
        $this->bundleSelectionStmt =
            $this->getConnection()->prepare($this->loadStatement(SqlStatementKeys::BUNDLE_SELECTION));
    }

    /**
     * Load's the bundle selection value with the passed option/product/parent product ID.
     *
     * @param integer $optionId        The option ID of the bundle selection to be returned
     * @param integer $productId       The product ID of the bundle selection to be returned
     * @param integer $parentProductId The parent product ID of the bundle selection to be returned
     *
     * @return array The bundle selection
     */
    public function findOneByOptionIdAndProductIdAndParentProductId($optionId, $productId, $parentProductId)
    {

        // initialize the params
        $params = array(
            MemberNames::OPTION_ID         => $optionId,
            MemberNames::PARENT_PRODUCT_ID => $parentProductId,
            MemberNames::PRODUCT_ID        => $productId
        );

        // load and return the bundle option with the passed option/product/parent product ID
        $this->bundleSelectionStmt->execute($params);
        return $this->bundleSelectionStmt->fetch(\PDO::FETCH_ASSOC);
    }
}
