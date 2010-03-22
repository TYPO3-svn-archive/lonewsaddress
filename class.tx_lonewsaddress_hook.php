<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2006 Georg Ringer <typo3@ringerge.org>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Hook 'lonewsaddress_hook' for the 'tt_news' extension.
 *
 * @author	Lina Ourima <typo3 et ringerge dot org>
 * @sponsorship typo3-blog.net (http://www.typo3-blog.net/) 
 */


require_once(PATH_tslib.'class.tslib_pibase.php');
require_once(PATH_t3lib.'class.t3lib_tcemain.php');


class tx_lonewsaddress_hook extends tslib_pibase {
	var $cObj; // The backReference to the mother cObj object set at call time
	// Default plugin variables:
	var $prefixId 		= 'tx_lonewsaddress';		// Same as class name
	var $scriptRelPath 	= 'class.tx_lonewsaddress_hook.php';	// Path to this script relative to the extension dir.
	var $extKey 		= 'lonewsaddress';	// The extension key.

	var $pObj;
	var $conf;
	var $markerArray;
	var $calledBy;


	/**
	 * connects into the tt_news to fill the 2 markers
	 *
	 * @param	array		an array of markers coming from the extension
	 * @param	array		the current record of the extension
	 * @param	array		the configuration coming from the extension
	 * @param	object		the parent object calling this method
	 * @return	array		processed marker array
	 */
	function extraItemMarkerProcessor($markerArray, $row, $lConf, &$pObj) {
		$this->cObj = t3lib_div::makeInstance('tslib_cObj'); // local cObj.	

		$this->pObj = &$pObj;
		$this2 = $pObj;
		$conf = $pObj->conf;
		$addressConf = $conf['lonewsaddress.'];
		$cObj = t3lib_div::makeInstance('tslib_cObj');
		$cObj->data = $row; 
			//	t3lib_div::debug($row);
		if(is_array($addressConf['marks.']))
		foreach($addressConf['marks.'] AS $marker => $cObject) {
			if(strpos($marker, '.') == 0) {
				$markerArray['###'.$marker.'###'] = $cObj->cObjGetSingle($cObject, $addressConf['marks.'][$marker.'.']);
			}
		}
	/*	$markerArray['###BERUFSFELD_SPEZIAL###']=$this->cObj->stdWrap($row['tx_lonewsaddress_bfeld'], $confLinks['bfeld.']);
		$markerArray['###AUSBILDUNGSREIFE###']=$this->cObj->stdWrap($row['tx_lonewsaddress_areife'], $confLinks['areife.']);
		$markerArray['###BERUFSAUSBILDUNG###']=$this->cObj->stdWrap($row['tx_lonewsaddress_baus'], $confLinks['baus.']);
		$markerArray['###WEITERBILDUNG###']=$this->cObj->stdWrap($row['tx_lonewsaddress_weiterb'], $confLinks['weiterb.']);
		$markerArray['###HOCHSCHULREIFE###']=$this->cObj->stdWrap($row['tx_lonewsaddress_abi'], $confLinks['abi.']);
		$markerArray['###ABSCHLUSS###']=$this->cObj->stdWrap($row['tx_lonewsaddress_abschluss'], $confLinks['abschluss.']);
		$markerArray['###BESONDERHEITEN###']=$this->cObj->stdWrap($row['tx_lonewsaddress_besondh'], $confLinks['besondh.']);
		$markerArray['###ZAHLEN###']=$this->cObj->stdWrap($row['tx_lonewsaddress_zahlen'], $confLinks['zahlen.']);*/
		return $markerArray;
	}

}
?>