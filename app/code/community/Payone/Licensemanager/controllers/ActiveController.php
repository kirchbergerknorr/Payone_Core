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
 * @package         Payone_Licensemanager_controllers
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @author          Edward Mateja <edward.mateja@votum.de>
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.votum.de
 */

/**
 *
 * @category        Payone
 * @package         Payone_Licensemanager_controllers
 * @subpackage
 * @copyright       Copyright (c) 2012 <info@noovias.com> - www.noovias.com
 * @license         <http://www.gnu.org/licenses/> GNU General Public License (GPL 3)
 * @link            http://www.noovias.com
 */
class Payone_Licensemanager_ActiveController extends Mage_Core_Controller_Front_Action {

    public function indexAction()
    {
        $helper = Mage::helper('payone_licensemanager');
        $helper->setLicenseKey();

        $url = 'https://www.payone.de/kontakt/angebot-anfordern-redirect/';
        $setRedirect = base64_encode(Mage::helper("adminhtml")->getUrl('adminhtml'));
        $url = $url . '?setRedirect=' . $setRedirect;
        $this->_redirectUrl($url);
    }
}