<?php

/**
 * TechDivision\Import\Product\Bundle\Repositories\BundleOptionValueRepository
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
 * Repository implementation to load bundle option value data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class BundleOptionValueRepository extends AbstractRepository implements BundleOptionValueRepositoryInterface
{

    /**
     * The prepared statement to load a existing bundle option.
     *
     * @var \PDOStatement
     */
    protected $bundleOptionValueStmt;

    /**
     * Initializes the repository's prepared statements.
     *
     * @return void
     */
    public function init()
    {

        // initialize the prepared statements
        $this->bundleOptionValueStmt =
            $this->getConnection()->prepare($this->loadStatement(SqlStatementKeys::BUNDLE_OPTION_VALUE));
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

        // load and return the bundle option value with the passed name/store/parent ID
        $this->bundleOptionValueStmt->execute($params);
        return $this->bundleOptionValueStmt->fetch(\PDO::FETCH_ASSOC);
    }
}
