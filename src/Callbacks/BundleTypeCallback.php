<?php

/**
 * TechDivision\Import\Product\Bundle\Callbacks\BundleTypeCallback
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Bundle\Callbacks;

use TechDivision\Import\Product\Callbacks\AbstractProductImportCallback;
use TechDivision\Import\Observers\AttributeCodeAndValueAwareObserverInterface;

/**
 * A SLSB that handles the process to import product bunches.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-bundle
 * @link      http://www.techdivision.com
 */
class BundleTypeCallback extends AbstractProductImportCallback
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
     * Will be invoked by a observer it has been registered for.
     *
     * @param \TechDivision\Import\Observers\AttributeCodeAndValueAwareObserverInterface|null $observer The observer
     *
     * @return mixed The modified value
     */
    public function handle(?AttributeCodeAndValueAwareObserverInterface $observer = null)
    {

        // set the observer
        $this->setObserver($observer);

        // replace the passed attribute value into the type ID
        return (boolean) $this->types[strtolower($observer->getAttributeValue())];
    }
}
