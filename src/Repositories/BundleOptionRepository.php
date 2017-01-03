<?php

/**
 * TechDivision\Import\Product\Bundle\Repositories\BundleOptionRepository
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

use TechDivision\Import\Repositories\AbstractRepository;
use TechDivision\Import\Product\Bundle\Utils\MemberNames;

/**
 * Repository implementation to load bundle option data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class BundleOptionRepository extends AbstractRepository
{

    /**
     * The prepared statement to load a existing bundle option.
     *
     * @var \PDOStatement
     */
    protected $bundleOptionStmt;

    /**
     * Initializes the repository's prepared statements.
     *
     * @return void
     */
    public function init()
    {

        // load the utility class name
        $utilityClassName = $this->getUtilityClassName();

        // initialize the prepared statements
        $this->bundleOptionStmt = $this->getConnection()->prepare($utilityClassName::BUNDLE_OPTION);
    }

    /**
     * Load's the bundle option with the passed name, store + parent ID.
     *
     * @param string  $title    The title of the bundle option to be returned
     * @param integer $storeId  The store ID of the bundle option to be returned
     * @param integer $parentId The entity of the product the bundle option is related with
     *
     * @return array The bundle option
     */
    public function findOneByNameAndStoreIdAndParentId($title, $storeId, $parentId)
    {

        // initialize the params
        $params = array(
            MemberNames::PARENT_ID => $parentId,
            MemberNames::TITLE     => $title,
            MemberNames::STORE_ID  => $storeId
        );

        // load and return the bundle option with the passed name/store/parent ID
        $this->bundleOptionStmt->execute($params);
        return $this->bundleOptionStmt->fetch(\PDO::FETCH_ASSOC);
    }
}
