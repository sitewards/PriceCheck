<?php
/**
 *
 * @category	Mage
 * @package		Sitewards_PriceCheck
 * @copyright	Copyright (c) 2011 Sitewards GmbH. (http://www.sitewards.com)
 * @license		OSL
 * @version		1.0.0
 * @author		David Manners <david.manners@sitewards.com>
 *
 * This class is used to disable Magento´s default Pricing calculations
 */
class Sitewards_PriceCheck_Model_Price extends Mage_Catalog_Model_Product_Type_Price
{
	protected $strProductIdentifierName;
	protected $strCustomerIdentifierName;
	protected $strHelperName;
	protected $bolExtensionActive;

	public function __construct() {
		$this->strProductIdentifierName		= Mage::getStoreConfig('pricecheck_config/pricecheck_group/product_identifier_name');
		$this->strCustomerIdentifierName	= Mage::getStoreConfig('pricecheck_config/pricecheck_group/customer_identifier_name');
		$this->strHelperName				= Mage::getStoreConfig('pricecheck_config/pricecheck_group/helper_name');
		$this->bolExtensionActive			= Mage::getStoreConfig('pricecheck_config/pricecheck_group/disable_ext');
	}

	/**
	 *
	 * @param object $objProduct
	 * @return float custom price or magento stored price
	 */
	public function getPrice($objProduct) {
		if($this->bolExtensionActive == true) {
			$intCustomerGroupId = $this->_getCustomerGroupId($objProduct);
			if($intCustomerGroupId != 0) {
				$objCustomer = Mage::getSingleton('customer/session')->getCustomer();
				$intCustomerId = $objCustomer->getData($this->strCustomerIdentifierName);

				$fltCustomPrice = $this->_getPriceForGroup($intCustomerId, $intCustomerGroupId, $objProduct);
				if(!is_null($fltCustomPrice)) {
					return $fltCustomPrice;
				} else {
					return parent::getPrice($objProduct);
				}
			} else {
				return parent::getPrice($objProduct);
			}
		} else {
			return parent::getPrice($objProduct);
		}
	}

	/**
	 *
	 * @param int $intQuantity
	 * @param object $objProduct
	 * @return array of tier price information
	 */
	public function getTierPrice($intQuantity = null, $objProduct) {
		if($this->bolExtensionActive == true) {
			$intCustomerGroupId = $this->_getCustomerGroupId($objProduct);
			if($intCustomerGroupId != 0) {
				$objCustomer = Mage::getSingleton('customer/session')->getCustomer();
				$intCustomerId = $objCustomer->getData($this->strCustomerIdentifierName);

				$arrPrices = $this->_getPricesForGroup($intQuantity, $intCustomerId, $intCustomerGroupId, $objProduct);
				if(!is_null($arrPrices)) {
					return $arrPrices;
				} else {
					return parent::getTierPrice($intQuantity, $objProduct);
				}
			} else {
				return parent::getTierPrice($intQuantity, $objProduct);
			}
		} else {
			return parent::getTierPrice($intQuantity, $objProduct);
		}
	}

	/**
	 *
	 * @param int $intCustomerId
	 * @param int $intGroupId
	 * @param object $objProduct
	 * @return float find a price for a product from another source
	 */
	protected function _getPriceForGroup($intCustomerId, $intGroupId, $objProduct) {
		if(method_exists(Mage::helper($this->strHelperName), 'getPriceFromCustomer') == false) {
			Mage::throwException('StockCheck extension not correctly setup. Please create the function getPriceFromCustomer in the helper '.$this->strHelperName);
		} elseif(method_exists(Mage::helper($this->strHelperName), 'getGroupCode') == false) {
			Mage::throwException('StockCheck extension not correctly setup. Please create the function getGroupCode in the helper '.$this->strHelperName);
		} else {
			$strProductIdentifierValue = $objProduct->getData($this->strProductIdentifierName);

			if(is_null($strProductIdentifierValue)) {
				$intProductId = $objProduct->getId();
				$objProduct = $objProduct->load($intProductId);
				$strProductIdentifierValue = $objProduct->getData($this->strProductIdentifierName);
			}

			/*
			 * First check to see if customer has a custom price
			 */
			$intCustomerPrice = Mage::helper($this->strHelperName)->getPriceFromCustomer($strProductIdentifierValue, $intCustomerId);
			if (!is_null($intCustomerPrice)) {
				return $intCustomerPrice;
			}

			/*
			 * If not check to see if customer group has a custom price
			 */
			$strGroupCode = Mage::helper($this->strHelperName)->getGroupCode($intGroupId);

			$intGroupPrice = Mage::helper($this->strHelperName)->getPriceFromCustomer($strProductIdentifierValue, $strGroupCode);
			if (!is_null($intGroupPrice)) {
				return $intGroupPrice;
			}
		}
	}

	/**
	 *
	 * @param int $intQuantity
	 * @param int $intCustomerId
	 * @param int $intGroupId
	 * @param object $objProduct
	 * @return array of prices for a product from another source
	 */
	protected function _getPricesForGroup($intQuantity, $intCustomerId, $intGroupId, $objProduct) {
		if(method_exists(Mage::helper($this->strHelperName), 'getPriceFromCustomer') == false) {
			Mage::throwException('StockCheck extension not correctly setup. Please create the function getPriceFromCustomer in the helper '.$this->strHelperName);
		} elseif(method_exists(Mage::helper($this->strHelperName), 'getBestPrice') == false) {
			Mage::throwException('StockCheck extension not correctly setup. Please create the function getBestPrice in the helper '.$this->strHelperName);
		} else {
			$fltProductPrice = $objProduct->getPrice();
			$strProductIdentifierValue = $objProduct->getData($this->strProductIdentifierName);
			if(is_null($strProductIdentifierValue)) {
				$intProductId = $objProduct->getId();
				$objProduct = $objProduct->load($intProductId);
				$strProductIdentifierValue = $objProduct->getData($this->strProductIdentifierName);
			}

			/*
			 * First check to see if customer has a custom price
			 */
			$arrPrices = Mage::helper($this->strHelperName)->getPriceFromCustomer($strProductIdentifierValue, $intCustomerId, true);
			if(!is_null($arrPrices)) {
				if(!is_null($intQuantity)){
					return Mage::helper($this->strHelperName)->getBestPrice($intQuantity, $arrPrices, $fltProductPrice);
				} else {
					return $arrPrices;
				}
			}

			/*
			 * If not check to see if customer group has a custom price
			 */
			$strGroupCode = Mage::helper($this->strHelperName)->getGroupCode($intGroupId);

			$arrPrices = Mage::helper($this->strHelperName)->getPriceFromCustomer($strProductIdentifierValue, $strGroupCode, true);
			if(!is_null($arrPrices)) {
				if(!is_null($intQuantity)){
					return Mage::helper($this->strHelperName)->getBestPrice($intQuantity, $arrPrices, $fltProductPrice);
				} else {
					return $arrPrices;
				}
			}
		}
	}

	/**
	 *
	 * @param object $objProduct
	 * @return array of packing unit information for a product based on customer id or group
	 */
	public function getPackingUnitInfo($objProduct) {
		if($this->bolExtensionActive == true) {
			if(method_exists(Mage::helper($this->strHelperName), 'getGroupCode') == false) {
				Mage::throwException('StockCheck extension not correctly setup. Please create the function getGroupCode in the helper '.$this->strHelperName);
			} elseif(method_exists(Mage::helper($this->strHelperName), 'getPackingInfo') == false) {
				Mage::throwException('StockCheck extension not correctly setup. Please create the function getPackingInfo in the helper '.$this->strHelperName);
			} else {
				$strProductIdentifierValue = $objProduct->getData($this->strProductIdentifierName);

				$objCustomer = Mage::getSingleton('customer/session')->getCustomer();
				$intCustomerId = $objCustomer->getData($this->strCustomerIdentifierName);
				$intCustomerGroupId = $objCustomer->getGroupId();

				/*
				 * First check to see if customer has a custom price
				 */
				$arrPackingInfo = Mage::helper($this->strHelperName)->getPackingInfo($strProductIdentifierValue, $intCustomerId);
				if(!is_null($arrPackingInfo)) {
					return $arrPackingInfo;
				}

				/*
				 * If not check to see if customer group has a custom price
				 */
				$strGroupCode = Mage::helper($this->strHelperName)->getGroupCode($intCustomerGroupId);

				$arrPackingInfo = Mage::helper($this->strHelperName)->getPackingInfo($strProductIdentifierValue, $strGroupCode);
				if(!is_null($arrPackingInfo)) {
					return $arrPackingInfo;
				}
			}
		}
	}

	/**
	 *
	 * @param object $objProduct
	 * @return array of pricing debug information for a product based on customer id or group
	 */
	public function getDebugInfo($objProduct) {
		if($this->bolExtensionActive == true) {
			if(method_exists(Mage::helper($this->strHelperName), 'getGroupCode') == false) {
				Mage::throwException('StockCheck extension not correctly setup. Please create the function getGroupCode in the helper '.$this->strHelperName);
			} elseif(method_exists(Mage::helper($this->strHelperName), 'getPackingInfo') == false) {
				Mage::throwException('StockCheck extension not correctly setup. Please create the function getPackingInfo in the helper '.$this->strHelperName);
			} else {
				$strProductIdentifierValue = $objProduct->getData($this->strProductIdentifierName);

				$objCustomer = Mage::getSingleton('customer/session')->getCustomer();
				$intCustomerId = $objCustomer->getData($this->strCustomerIdentifierName);
				$intCustomerGroupId = $objCustomer->getGroupId();

				/*
				 * First check to see if customer has a custom price
				 */
				$arrPackingInfo = Mage::helper($this->strHelperName)->getPackingInfo($strProductIdentifierValue, $intCustomerId, true);
				if(!is_null($arrPackingInfo)) {
					return $arrPackingInfo;
				}

				/*
				 * If not check to see if customer group has a custom price
				 */
				$strGroupCode = Mage::helper($this->strHelperName)->getGroupCode($intCustomerGroupId);

				$arrPackingInfo = Mage::helper($this->strHelperName)->getPackingInfo($strProductIdentifierValue, $strGroupCode, true);
				if(!is_null($arrPackingInfo)) {
					return $arrPackingInfo;
				}
			}
		}
	}
}