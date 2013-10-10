<?php
/**
 * Sitewards GmbH
 *
 * This class is used to help the Sitewards_PriceCheck_Helper_Data class and requires the following functions to be created
 * getBestPrice,
 * getGroupCode,
 * getPriceFromCustomer,
 * getPackingInfo
 * 
 * @category	Mage
 * @package		Sitewards_StockCheck
 * @copyright	Copyright (c) 2011 Sitewards GmbH. (http://www.sitewards.com)
 * @license		OSL
 * @version		1.0.0
 */
class Sitewards_PriceCheck_Helper_Data extends Mage_Core_Helper_Abstract {
	/**
	 *
	 * @param int $intQuantity requested level of quantity for a product
	 * @param array $arrPrices an array of all avalible prices in the following format
	 *	$arrPrices[] = array(
	 *		'price'			=> '1.00',
	 *		'website_price'	=> '1.00',
	 *		'price_qty'		=> 12,
	 *		'cust_group'	=> 12
	 *	);
	 * @param float $fltProductPrice the current product price
	 * @return	float the best price from the quantity provided
	 */
	public function getBestPrice($intQuantity, $arrPrices, $fltProductPrice) {
		Mage::throwException('PriceCheck extension not correctly setup. Please complete the function getBestPrice in the helper '.get_class()); 
	}

	/**
	 *
	 * @param int $intGroupId the group id as stored in magento
	 * @return string which is a unique identifier for a customer group in your system
	 */
	public function getGroupCode($intGroupId) {
		Mage::throwException('PriceCheck extension not correctly setup. Please complete the function getGroupCode in the helper '.get_class()); 
	}

	/**
	 *
	 * @param string $strProductIdentifier unique identifier for a product - defaults to Magento ProductId
	 * @param int $intCustomerId unique identifier for a customer - defaults to Magento CustomerId
	 * @param boolean $bolArrayFormat to format the return in an array
	 * @return array|float an array of price information or a single price array should be fromated as
	 * $arrPrices[] = array(
	 *		'price'			=> '1.00',
	 *		'website_price'	=> '1.00',
	 *		'price_qty'		=> 12,
	 *		'cust_group'	=> 12
	 *	);
	 */
	public function getPriceFromCustomer($strProductIdentifier, $intCustomerId, $bolArrayFormat = false) {
		Mage::throwException('PriceCheck extension not correctly setup. Please complete the function getPriceFromCustomer in the helper '.get_class()); 
	}

	/**
	 *
	 * @param string $strProductIdentifier unique identifier for a product - defaults to Magento ProductId
	 * @param int $intCustomerId unique identifier for a customer - defaults to Magento CustomerId
	 * @param boolean $bolForDebug to return all information for debug display
	 * @return array an array of packing information should be fromated as
	 * $arrPackingInfo = array(
	 *		'price_per_piece'	=> price/pricing_unit,
	 *		'price_per_unit'	=> price,
	 *		'packing_unit'		=> number of items in a packing unit,
	 *		'pricing_unit'		=> number of items in a pricing unit
	 *	);
	 */
	public function getPackingInfo($strProductIdentifier, $intCustomerId, $bolForDebug = false) {
		Mage::throwException('PriceCheck extension not correctly setup. Please complete the function getPackingInfo in the helper '.get_class()); 
	}
}