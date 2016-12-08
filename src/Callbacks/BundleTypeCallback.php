<?php

/**
 * TechDivision\Import\Product\Bundle\Callbacks\BundleTypeCallback
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

namespace TechDivision\Import\Product\Bundle\Callbacks;

use TechDivision\Import\Callbacks\AbstractCallback;

/**
 * A SLSB that handles the process to import product bunches.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class BundleTypeCallback extends AbstractCallback
{

    /**
     * Array with the string => boolean mapping.
     *
     * @var array
     */
    protected $types = array(
        'fixed'  => 1,
        'dynamic' => 0
    );

    /**
     * {@inheritDoc}
     * @see \TechDivision\Import\Product\Bundle\Callbacks\Product\ImportCallbackInterface::handle()
     */
    public function handle($value)
    {
        return (boolean) $this->types[strtolower($value)];
    }
}
