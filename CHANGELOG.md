# Version 25.0.0

## Bugfixes

* None

## Features

* Refactoring deprecated classes. see https://github.com/techdivision/import-cli-simple/blob/master/UPGRADE-4.0.0.md
* Add #PAC-294: Integration strict mode

# Version 24.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import-product 24.* version as dependency

# Version 23.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import-product 23.* version as dependency

# Version 22.0.0

## Bugfixes

* None

## Features

* Add #PAC-47

# Version 21.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import-product 21.* version as dependency

# Version 20.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import-product 20.* version as dependency

# Version 19.0.1

## Bugfixes

* None

## Features

* Extract dev autoloading

# Version 19.0.0

## Bugfixes

* Fixed issue with missing bundle options

## Features

* None

# Version 18.0.0

## Bugfixes

* None

## Features

* Remove deprecated classes and methods
* Add techdivision/import#162
* Add techdivision/import-cli-simple#216
* Switch to latest techdivision/import-product 19.* version as dependency

# Version 17.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import-product 18.* version as dependency

# Version 16.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import-product 17.* version as dependency

# Version 15.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import-product 16.* version as dependency

# Version 14.0.0

## Bugfixes

* None

## Features

* Add utility class with price types
* Switch to latest techdivision/import-product 15.* version as dependency
* Extract bundle subject functionality to a trait to allow usage in EE library also
* Move library specific DI identifiers from techdivision/import-product to thhis library

# Version 13.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import-product 13.* version as dependency

# Version 12.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import-product 12.* version as dependency

# Version 11.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import-product 11.0.* version as dependency

# Version 10.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import-product 10.0.* version as dependency

# Version 9.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import-product 9.0.* version as dependency

# Version 8.0.0

## Bugfixes

* None

## Features

* Add composite observers to minimize configuration complexity
* Switch to latest techdivision/import-product 8.0.* version as dependency
* Make Actions and ActionInterfaces deprecated, replace DI configuration with GenericAction + GenericIdentifierAction

# Version 7.0.0

## Bugfixes

* None

## Features

* Added techdivision/import-cli-simple#198

# Version 6.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import-product 6.0.* version as dependency

# Version 5.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import-product 5.0.* version as dependency

# Version 4.0.0

## Bugfixes

* None

## Features

* Switch to latest techdivision/import 5.0.* version as dependency

# Version 3.0.0

## Bugfixes

* None

## Features

* Compatibility for Magento 2.3.x

# Version 2.0.0

## Bugfixes

* None

## Features

* Compatibility for Magento 2.2.x

# Version 1.0.0

## Bugfixes

* None

## Features

* Move PHPUnit test from tests to tests/unit folder for integration test compatibility reasons

# Version 1.0.0-beta10

## Bugfixes

* None

## Features

* Add missing interfaces for actions and repositories
* Replace class type hints for ProductBundleProcessor with interfaces

# Version 1.0.0-beta9

## Bugfixes

* None

## Features

* Configure DI to pass event emitter to subjects constructor

# Version 1.0.0-beta8

## Bugfixes

* None

## Features

* Refactored DI + switch to new SqlStatementRepositories instead of SqlStatements

# Version 1.0.0-beta7

## Bugfixes

* None

## Features

* Add additional symofony packages for development purposes

# Version 1.0.0-beta6

## Bugfixes

* None

## Features

* Minor refactoring

# Version 1.0.0-beta5

## Bugfixes

* None

## Features

* Refactor to optimize DI integration

# Version 1.0.0-beta4

## Bugfixes

* None

## Features

* Switch to new plugin + subject factory implementations

# Version 1.0.0-beta3

## Bugfixes

* None

## Features

* Use Robo for Travis-CI build process 
* Refactoring for new ConnectionInterface + SqlStatementsInterface

# Version 1.0.0-beta2

## Bugfixes

* None

## Features

* Refactoring Symfony DI integration

# Version 1.0.0-beta1

## Bugfixes

* None

## Features

* Integrate Symfony DI functionality

# Version 1.0.0-alpha10

## Bugfixes

* None

## Features

* Refactoring for DI integration

# Version 1.0.0-alpha9

## Bugfixes

* None

## Features

* Switch to latest callback interface and optimise error messages

# Version 1.0.0-alpha8

## Bugfixes

* None

## Features

* Remove unnecessary handle() method from observers

# Version 1.0.0-alpha7

## Bugfixes

* None

## Features

* Add BundleShipmentTypeCallback and ShipmentTypes utility class

# Version 1.0.0-alpha6

## Bugfixes

* None

## Features

* Refactoring ProductBundleObserver for generalization purposes

# Version 1.0.0-alpha5

## Bugfixes

* None

## Features

* Implement add/update operation

# Version 1.0.0-alpha4

## Bugfixes

* None

## Features

* Switch to new create/delete naming convention

# Version 1.0.0-alpha3

## Bugfixes

* None

## Features

* Refactoring store view code handling
* BundleSubject now extends AbstractProductSubject
* Add Robo.li composer dependeny + task configuration
* ProductBundleProcessorInterface now extends ProductProcessorInterface

# Version 1.0.0-alpha2

## Bugfixes

* None

## Features

* Refactoring to allow multiple prepared statements per CRUD processor instance

# Version 1.0.0-alpha1

## Bugfixes

* None

## Features

* Refactoring + Documentation to prepare for Github release
