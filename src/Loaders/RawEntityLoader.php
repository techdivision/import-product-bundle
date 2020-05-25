<?php

/**
 * TechDivision\Import\Product\Bundle\Loaders\RawEntityLoader
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
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Bundle\Loaders;

use TechDivision\Import\Loaders\LoaderInterface;
use TechDivision\Import\Connection\ConnectionInterface;
use TechDivision\Import\Product\Bundle\Utils\EntityTypeCodes;

/**
 * Loader for raw entities.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2020 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class RawEntityLoader implements LoaderInterface
{

    /**
     * The connection instance.
     *
     * @var \TechDivision\Import\Connection\ConnectionInterface
     */
    protected $connection;

    /**
     * The column metadata loader instance.
     *
     * @var \TechDivision\Import\Loaders\LoaderInterface
     */
    protected $rawEntities = array();

    /**
     * The loader instance for the raw EAV entities.
     *
     * @var \TechDivision\Import\Loaders\LoaderInterface
     */
    protected $eavRawEntityLoader;

    /**
     * The array with the attribute specific entity types.
     *
     * @var array
     */
    protected $entityTypes = array(EntityTypeCodes::CATALOG_PRODUCT_BUNDLE_OPTION, EntityTypeCodes::CATALOG_PRODUCT_BUNDLE_SELECTION);

    /**
     * Construct a new instance.
     *
     * @param \TechDivision\Import\Connection\ConnectionInterface $connection           The DB connection instance used to load the table metadata
     * @param \TechDivision\Import\Loaders\LoaderInterface        $columnMetadataLoader The column metadata loader instance
     * @param \TechDivision\Import\Loaders\LoaderInterface        $eavRawEntityLoader   The loader instance for the raw EAV entities
     */
    public function __construct(
        ConnectionInterface $connection,
        LoaderInterface $columnMetadataLoader,
        LoaderInterface $eavRawEntityLoader
    ) {

        // set the connection and the raw EAV entity loader
        $this->connection = $connection;
        $this->eavRawEntityLoader = $eavRawEntityLoader;

        // iterate over the entity types and create the raw entities
        foreach ($this->entityTypes as $entityType) {
            // load the columns from the metadata
            $columns = array_filter(
                $columnMetadataLoader->load($entityType),
                function ($value) {
                    return $value['Key'] !== 'PRI' && $value['Null'] === 'NO' ;
                }
            );
            // initialize the raw entities and their default values, if available
            foreach ($columns as $column) {
                $this->rawEntities[$entityType][$column['Field']] = $this->loadDefaultValue($column);
            }
        }
    }

    /**
     * Return's the default value for the passed column.
     *
     * @param array $column The column to return the default value for
     *
     * @return string|null The default value for the passed column
     */
    protected function loadDefaultValue(array $column)
    {

        // load the default value
        $default = $column['Default'];

        // if a default value has been found
        if ($default === null) {
            return;
        }

        try {
            // try to load it resolve it by executing a select statement (assuming it is an MySQL expression)
            $row = $this->connection->query(sprintf('SELECT %s()', $default))->fetch(\PDO::FETCH_ASSOC);
            return reset($row);
        } catch (\PDOException $pdoe) {
        }

        // return the default value
        return $default;
    }

    /**
     * Loads and returns data.
     *
     * @param string|null $entityTypeCode The table name to return the list for
     * @param array       $data           An array with data that will be used to initialize the raw entity with
     *
     * @return \ArrayAccess The array with the raw data
     */
    public function load($entityTypeCode = null, array $data = array())
    {
        return isset($this->rawEntities[$entityTypeCode]) ? array_merge($this->rawEntities[$entityTypeCode], $data) : $this->eavRawEntityLoader->load($entityTypeCode, $data);
    }
}
