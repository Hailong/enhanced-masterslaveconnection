# Enhanced MasterSlaveConnection

Add common enhancements to the Doctrine default MasterSlaveConnection implementation.

## Enhancements

* Force all the SELECT queries go to slave databases.

* Use a better random function `random_int` to balance the load among slave databases.

## Adoption in Symfony Application

Install the package and assign this enhanced version of MasterSlaveConnection to the `wrapper_class` property of `doctrine` configuration.
