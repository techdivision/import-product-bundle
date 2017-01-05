# M2IF - Bundle Product Import

[![Latest Stable Version](https://img.shields.io/packagist/v/techdivision/import-product-bundle.svg?style=flat-square)](https://packagist.org/packages/techdivision/import-product-bundle) 
 [![Total Downloads](https://img.shields.io/packagist/dt/techdivision/import-product-bundle.svg?style=flat-square)](https://packagist.org/packages/techdivision/import-product-bundle)
 [![License](https://img.shields.io/packagist/l/techdivision/import-product-bundle.svg?style=flat-square)](https://packagist.org/packages/techdivision/import-product-bundle)
 [![Build Status](https://img.shields.io/travis/techdivision/import-product-bundle/master.svg?style=flat-square)](http://travis-ci.org/techdivision/import-product-bundle)
 [![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/techdivision/import-product-bundle/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/techdivision/import-product-bundle/?branch=master) [![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/techdivision/import-product-bundle/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/techdivision/import-product-bundle/?branch=master)

  ## Introduction

This module provides the functionality to import the product bundles defined in the CSV file.

## Configuration

In case that the [M2IF - Simple Console Tool](https://github.com/techdivision/import-cli-simple) 
is used, the funcationality can be enabled by adding the following snippets to the configuration 
file

```json
{
  "magento-edition": "CE",
  "magento-version": "2.1.2",
  "operation-name" : "replace",
  "installation-dir" : "/var/www/magento",
  "utility-class-name" : "TechDivision\\Import\\Utils\\SqlStatements",
  "database": { ... },
  "operations" : [
    {
      "name" : "replace",
      "subjects": [
        { ... },
        {
          "class-name": "TechDivision\\Import\\Product\\Bundle\\Subjects\\BundleSubject",
          "processor-factory" : "TechDivision\\Import\\Cli\\Services\\ProductBundleProcessorFactory",
          "utility-class-name" : "TechDivision\\Import\\Product\\Bundle\\Utils\\SqlStatements",
          "prefix": "bundles",
          "source-dir": "projects/sample-data/tmp",
          "target-dir": "projects/sample-data/tmp",
          "observers": [
            {
              "import": [
                "TechDivision\\Import\\Product\\Bundle\\Observers\\BundleOptionObserver",
                "TechDivision\\Import\\Product\\Bundle\\Observers\\BundleOptionValueObserver",
                "TechDivision\\Import\\Product\\Bundle\\Observers\\BundleSelectionObserver",
                "TechDivision\\Import\\Product\\Bundle\\Observers\\BundleSelectionPriceObserver"
              ]
            }
          ]
        }
      ]
    },
    {
      "name" : "add-update",
      "subjects": [
        { ... },
        {
          "class-name": "TechDivision\\Import\\Product\\Bundle\\Subjects\\BundleSubject",
          "processor-factory" : "TechDivision\\Import\\Cli\\Services\\ProductBundleProcessorFactory",
          "utility-class-name" : "TechDivision\\Import\\Product\\Bundle\\Utils\\SqlStatements",
          "prefix": "bundles",
          "source-dir": "projects/sample-data/tmp",
          "target-dir": "projects/sample-data/tmp",
          "observers": [
            {
              "import": [
                "TechDivision\\Import\\Product\\Bundle\\Observers\\BundleOptionUpdateObserver",
                "TechDivision\\Import\\Product\\Bundle\\Observers\\BundleOptionValueUpdateObserver",
                "TechDivision\\Import\\Product\\Bundle\\Observers\\BundleSelectionUpdateObserver",
                "TechDivision\\Import\\Product\\Bundle\\Observers\\BundleSelectionPriceUpdateObserver"
              ]
            }
          ]
        }
      ]
    }
  ]
}
```