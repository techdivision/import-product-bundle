<?php

/**
 * TechDivision\Import\Product\Bundle\Subjects\BundleSubjectTrait
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

namespace TechDivision\Import\Product\Bundle\Subjects;

use TechDivision\Import\Product\Bundle\Utils\PriceTypes;

/**
 * A trait implementation that provides functionality to handle the bunch import on subject level.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
trait BundleSubjectTrait
{

    /**
     * The option name => option ID mapping.
     *
     * @var array
     */
    protected $nameOptionIdMapping = array();

    /**
     * The ID of the last created selection.
     *
     * @var integer
     */
    protected $childSkuSelectionIdMapping = array();

    /**
     * The position counter, if no position for the bundle selection has been specified.
     *
     * @var integer
     */
    protected $positionCounter = 1;

    /**
     * The mapping for the price type.
     *
     * @var array
     */
    protected $priceTypeMapping = array(
        'fixed'   => PriceTypes::FIXED,
        'percent' => PriceTypes::PERCENT
    );

    /**
     * Reset the position counter to 1.
     *
     * @return void
     */
    public function resetPositionCounter()
    {
        $this->positionCounter = 1;
    }

    /**
     * Returns the acutal value of the position counter and raise's it by one.
     *
     * @return integer The actual value of the position counter
     */
    public function raisePositionCounter()
    {
        return $this->positionCounter++;
    }

    /**
     * Save's the mapping of the child SKU and the selection ID.
     *
     * @param string  $childSku    The child SKU of the selection
     * @param integer $selectionId The selection ID to save
     *
     * @return void
     */
    public function addChildSkuSelectionIdMapping($childSku, $selectionId)
    {
        $this->childSkuSelectionIdMapping[$childSku] = $selectionId;
    }

    /**
     * Return's the selection ID for the passed child SKU.
     *
     * @param string $childSku The child SKU to return the selection ID for
     *
     * @return integer The last created selection ID
     * @throws \Exception Is thrown if the SKU is not mapped yet
     */
    public function getChildSkuSelectionMapping($childSku)
    {

        // query whether or not a child SKU selection ID mapping is available
        if (isset($this->childSkuSelectionIdMapping[$childSku])) {
            return $this->childSkuSelectionIdMapping[$childSku];
        }

        // throw an exception if the SKU has not been mapped yet
        throw new \Exception(
            $this->appendExceptionSuffix(
                sprintf('Found not mapped selection ID mapping for SKU %s', $childSku)
            )
        );
    }

    /**
     * Return's the mapping for the passed price type.
     *
     * @param string $priceType The price type to map
     *
     * @return integer The mapped price type
     * @throws \Exception Is thrown, if the passed price type can't be mapped
     */
    public function mapPriceType($priceType)
    {

        // query whether or not the passed price type is available
        if (isset($this->priceTypeMapping[$priceType])) {
            return $this->priceTypeMapping[$priceType];
        }

        // throw an exception, if not
        throw new \Exception(
            $this->appendExceptionSuffix(
                sprintf('Can\'t find mapping for price type %s', $priceType)
            )
        );
    }

    /**
     * Return's the option ID for the passed name.
     *
     * @param string $name The name to return the option ID for
     *
     * @return integer The option ID for the passed name
     * @throws \Exception Is thrown, if no option ID for the passed name is available
     */
    public function getOptionIdForName($name)
    {

        // query whether or not an option ID for the passed name is available
        if (isset($this->nameOptionIdMapping[$name])) {
            return $this->nameOptionIdMapping[$name];
        }

        // throw an exception, if not
        throw new \Exception(
            $this->appendExceptionSuffix(
                sprintf('Can\'t find option ID for name %s', $name)
            )
        );
    }

    /**
     * Add's the mapping for the passed name => option ID.
     *
     * @param string  $name     The name of the option
     * @param integer $optionId The created option ID
     *
     * @return void
     */
    public function addNameOptionIdMapping($name, $optionId)
    {
        $this->nameOptionIdMapping[$name] = $optionId;
    }

    /**
     * Query whether or not the option with the passed name has already been created.
     *
     * @param string $name The option name to query for
     *
     * @return boolean TRUE if the option already exists, else FALSE
     */
    public function exists($name)
    {
        return isset($this->nameOptionIdMapping[$name]);
    }

    /**
     * Return's the last created option ID.
     *
     * @return integer $optionId The last created option ID
     */
    public function getLastOptionId()
    {
        return end($this->nameOptionIdMapping);
    }
}
