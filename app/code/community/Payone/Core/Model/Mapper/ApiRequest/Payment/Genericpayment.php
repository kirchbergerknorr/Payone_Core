<?php
/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License (GPL 3)
 * that is bundled with this package in the file LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Payone_Core to newer
 * versions in the future. If you wish to customize Payone_Core for your
 * needs please refer to http://www.payone.de for more information.
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Matthias Walter <info@noovias.com>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */

/**
 *
 * @category        Payone
 * @package         Payone_Core_Model
 * @subpackage      Mapper
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Core_Model_Mapper_ApiRequest_Payment_Genericpayment
    extends Payone_Core_Model_Mapper_ApiRequest_Payment_Abstract
{
    const EVENT_TYPE = 'genericpayment';

    /**
     * @return Payone_Api_Request_Genericpayment
     */
    public function getRequest()
    {
        return $this->getFactory()->getRequestPaymentGenericpayment();
    }

    /**
     * @param Mage_Sales_Model_Order_Payment $payment
     * @return Payone_Api_Request_Genericpayment
     */
    public function mapFromPayment(Mage_Sales_Model_Order_Payment $payment)
    {
        $this->init($payment);

        $request = $this->getRequest();

        $this->mapDefaultParameters($request);

//        $this->mapDefaultDebitParameters($request);
//        $business = $this->mapBusinessParameters();
//        $request->setBusiness($business);

        /** Set Invoiceing-Parameter only if enabled in Config */
//        if ($this->mustTransmitInvoiceData()) {
//            $invoicing = $this->mapInvoicingParameters();
//            $request->setInvoicing($invoicing);
//        }

        $this->dispatchEvent($this->getEventName(), array('request' => $request));
        $this->dispatchEvent($this->getEventPrefix() . '_all', array('request' => $request));
        return $request;
    }

    /**
     * @param Mage_Sales_Model_Quote $quote
     */
    public function mapExpressCheckoutParameters($quote, $workOrderId = null)
    {
        $request = $this->getRequest();
        $this->mapDefaultParameters($request);
        $paydata = new Payone_Api_Request_Parameter_Paydata_Paydata();
        if(null === $workOrderId) {
            $paydata->addItem(new Payone_Api_Request_Parameter_Paydata_DataItem(
                array('key' => 'action', 'data' => Payone_Api_Enum_GenericpaymentAction::PAYPAL_ECS_SET_EXPRESSCHECKOUT)
            ));
        } else {
            $paydata->addItem(new Payone_Api_Request_Parameter_Paydata_DataItem(
                array('key' => 'action', 'data' => Payone_Api_Enum_GenericpaymentAction::PAYPAL_ECS_GET_EXPRESSCHECKOUTDETAILS)
            ));
            $request->setWorkorderId($workOrderId);
        }
        $request->setPaydata($paydata);
        $request->setAid($this->getConfigPayment()->getAid());
        $request->setClearingtype(Payone_Enum_ClearingType::WALLET);
        $request->setAmount($quote->getGrandTotal());
        $request->setCurrency($quote->getQuoteCurrencyCode());
        $request->setWallet(new Payone_Api_Request_Parameter_Authorization_PaymentMethod_Wallet(array(
            'wallettype' => Payone_Api_Enum_WalletType::PAYPAL_EXPRESS,
            'successurl' => Mage::helper('payone_core/url')->getMagentoUrl('*/*/return'),
            'errorurl' => Mage::helper('payone_core/url')->getMagentoUrl('*/*/error'),
            'backurl' => Mage::helper('payone_core/url')->getMagentoUrl('*/*/cancel')
        )));
        return $request;
    }

    /**
     * @return string
     */
    public function getEventType()
    {
        return self::EVENT_TYPE;
    }
}