PriceCheck
==========

This extension provides an easy to use framework for overriding the standard Magento Product price model.
It is aimed at developers who want to load the price of products in realtime from another source.
* Allows for custom product and customer identifiers,
* Allows you to specify a custom helper class outside of the StockCheck extension,

Functions Provided
------------------

The following skeleton functions are provided in the Sitewards_PriceCheck_Helper_Data to allow you to extend and use in your Magento instance:
* getBestPrice
	* This function is called in the Product object _getPricesForGroup call,
	* The aim of this function is to return a float value to represent the best price for the quantity provided,
* getGroupCode
	* This function is called in the Product object _getPriceForGroup and _getPricesForGroup calls,
	* The aim of this function is to return a string value to represent the unique identifier for a customer group in your system,
* getPriceFromCustomer
	* This function is called in the Product object _getPriceForGroup and _getPricesForGroup calls,
	* The aim of this function is to return a array or float value of price information of a product for the given identifier and customer,
	* There is a parameter for this function that should specify if the return should be an array or float,
* getPackingInfo
	* This function is called in the Product object getPackingUnitInfo and getDebugInfo calls,
	* The aim of this function is to return and array of packing information,
	* Information returned can be used to specify price per piece, price per unit, number of items in a packing unit and the number of items in a pricing unit,

Please note that this is a framework and requires development to fit into Magento.

author: David Manners, 12/2012
contact: http://www.sitewards.com