<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Lina Ourima <2009@lotypo3.de>
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

require_once(PATH_tslib.'class.tslib_pibase.php');
/**
 * Plugin 'Google Map' for the 'lonewsaddress' extension.
 * Some code taken from Simple Google Map, Philipp Lutz <plutz@zellwerk.com>
 *
 * @author	Lina Wolf <2010@lotypo3.de>
 * @package	TYPO3
 * @subpackage	tx_lonewsaddress
 */

require_once(dirname(__FILE__). '/../lib/LoGoogleMapAPI.class.php');

class tx_lonewsaddress_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_lonewsaddress_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_lonewsaddress_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'lonewsaddress';	// The extension key.
	var $pi_checkCHash = true;
  var $lang_string_names=array('dir_to','to_button_value','to_button_type','dir_from','from_button_value','from_button_type','dir_text','dir_tohere','dir_fromhere');
  var $map_data = array();
  var $marker_data = array();
	var $flexform = null;						// Holds the flexform configuration for the plugin.
	/**
	 * The cObj
	 *
	 * @var tslib_cObj
	 */
	var $localcObj = '';
	
	function init(){
		$this->localcObj = t3lib_div::makeInstance('tslib_cObj');
	}

  /*
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content,$conf)	{
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
		
		// Init config for flexform
		$this->pi_initPIflexForm();
		$this->flexform = $this->cObj->data['pi_flexform'];

		$data= $this->cObj->data;
		$this->pi_loadLL();
		$this->init();
		//Get Values From Flexform
		$flex = $this->getFlexformData();
		//News to Diplay
		$this->marker_data = $this->getNewsToDisplay($flex);
		
		//Get Values from TypoScript if not set in flexform
		$this->map_data = array();
		$this->map_data['width'] = $flex['mapWidth']?$flex['mapWidth']:$this->getSingle($data, $this->conf['width'], $this->conf['width.']);
		$this->map_data['height'] = $flex['mapHeight']?$flex['mapHeight']:$this->getSingle($data, $this->conf['height'], $this->conf['height.']);
		$this->map_data['zoom'] = $flex['mapZoom']?$flex['mapZoom']:$this->getSingle($data, $this->conf['zoom'], $this->conf['zoom.']);
		$this->map_data['apikey'] = $flex['mapKey']?$flex['mapKey']:$this->getSingle($data, $this->conf['apikey'], $this->conf['apikey.']);
		$this->map_data['popMarkerWindow'] = $this->conf['popMarkerWindow'];
		
		


		$map = new LoGoogleMapAPI('lonewsaddress_div');
		// optional marker icon:
		if($this->map_data['marker']) {
			$map->setMarkerIcon($this->map_data['marker']);
		}

		
    // Load Language from locallang and overwrite strings in GoogleMapAPI-object
		foreach ($this->lang_string_names as $lang_string) {
			$translation=$this->pi_getLL($lang_string,'',1);
			if(!empty($translation))
				$map->driving_dir_text[$lang_string]=$translation;
		}

  
    // 2008 added Multi-Domain-API-Keys by eloh@zellwerk.com
		$APIkeys= array();
		$hostname= $_SERVER['HTTP_HOST'];
		$apikey= $this->map_data['apikey']; 
		//!empty($data['imagecaption']) ? $data['imagecaption'] : $this->conf['apikey'];
    $tmpkeys= preg_split('~\s*\r?\n\s*~', trim($apikey), -1, PREG_SPLIT_NO_EMPTY);
		if (1< count($tmpkeys)) {
			foreach ($tmpkeys as $row) {
				$M= preg_split('~\s*=\s*~', $row);
				if (count($M)== 2) {
					$APIkeys[$M[0]]= $M[1];
				} else {
					$APIkeys['*']= $M[0]; // last not marked String is default
				}
			}
		} 
		elseif (0< count($tmpkeys)) {
			$APIkeys[$hostname]= preg_replace('~[^=]+=\s*~', '', $tmpkeys[0]);
		} else {
			$APIkeys[$hostname]= '';
		}
		if (empty($APIkeys[$hostname])) {
			$hostname= '*';
		}
		if (empty($APIkeys[$hostname])) {
			return $this->pi_wrapInBaseClass("<div style=\"background:#FFFFFF; border:1px solid #CC0000; margin:4px; padding:4px;\" >No valid API key for <b >{$hostname}</b>.</div>");
		} else {
	    $map->setAPIKey($APIkeys[$hostname]);
	  }
 		// ----------------------------------------------------------------------------

		$map->setWidth($this->map_data['width'].'px');
		$map->setHeight($this->map_data['height'].'px');
		$map->setZoomLevel($this->map_data['zoom']);

    $countValidAddress = 0;
    for($i = 0; $i < sizeOf($this->marker_data); $i++)
    {   
			if (is_numeric($this->marker_data[$i]['lon'])&&is_numeric($this->marker_data[$i]['lat'])){
				$map->addMarkerByCoords($this->marker_data[$i]['lon'],$this->marker_data[$i]['lat'],$this->marker_data[$i]['alttext'],preg_replace("((\r\n)+)",'',$this->marker_data[$i]['text']));
    		$countValidAddress++;
			} else if($this->marker_data[$i]['address']) {
				$map->addMarkerByAddress($this->marker_data[$i]['address'],$this->marker_data[$i]['alttext'],preg_replace("((\r\n)+)",'',$this->marker_data[$i]['text']));
    		$countValidAddress++;
			}
		}
		if(!$countValidAddress) {
			return $this->pi_wrapInBaseClass($this->getSingle($data, $this->conf['ifNoAddresss'], $this->conf['ifNoAddresss.']));
		}
        
		// cache generated JavaScript
		$hash = '';
		for($i = 0; $i < sizeOf($this->marker_data); $i++) {
			$hash .= join($this->marker_data[$i]);
		}
		$hash .= join($this->map_data);
    $md5= substr(md5($hash), 0, 10);
    $filename= 'typo3temp/lonewsadress_'.$md5.'.js';
        
		// ewent: no_cache to renew the temporary js file
    if (!file_exists($filename) or intval(t3lib_div::_GP('no_cache')) == 1) {
			$fh=fopen($filename,'w');
			fputs($fh,preg_replace('/<\/?script[^>]*>/i','',$map->getMapJS()));
			fclose($fh);
    }

		$GLOBALS['TSFE']->register['gmap_script_head'] = $map->getHeaderJS() ."\n<script src='{$filename}' type='text/javascript' ></script>";
		$popit = '';
		//Show Window of first marker according to TypoScript Defination
		if($this->map_data['popMarkerWindow'] > 1 || ($countValidAddress == 1 && $this->map_data['popMarkerWindow'] ==1)) {
			$popit = '<script type="text/javascript" >
                  function lonewsaddress_popit() {
                    if(isArray(marker_html[0])) { markers[0].openInfoWindowTabsHtml(marker_html[0]); }
                    else { markers[0].openInfoWindowHtml(marker_html[0]); }
                  }
                  setTimeout("lonewsaddress_popit()",1000);
                  </script>';
      }

		$content .= '<div id="lonewsaddress_code">'. $map->getMap() . $map->getOnLoad().$popit.' </div>';

		return $this->pi_wrapInBaseClass($content);
	}
	
	/**
	 * Parses data through typoscript.
	 */
	function getSingle($data, $ts_type, $ts) {
		$this->localcObj->data = $data;
		return $this->localcObj->cObjGetSingle($ts_type, $ts);
	}
	
	function getFlexformData() {
		$flex = array();
		$flex['mapWidth'] = intval($this->pi_getFFvalue( $this->flexform, 'mapWidth', 's_map' ));
		$flex['mapHeight'] = intval($this->pi_getFFvalue( $this->flexform, 'mapHeight', 's_map' ));
		$flex['mapZoom'] = intval($this->pi_getFFvalue( $this->flexform, 'mapZoom', 's_map' ));
		$flex['mapKey'] = $this->pi_getFFvalue( $this->flexform, 'mapKey', 's_map' );
		$flex['what_to_display'] = $this->pi_getFFvalue( $this->flexform, 'what_to_display', 'sDEF' );
		$flex['singleNews'] = $this->pi_getFFvalue( $this->flexform, 'singleNews', 'sDEF' );
		$flex['newsstorage'] = $this->pi_getFFvalue( $this->flexform, 'newsstorage', 'sDEF' );
		$flex['limit'] = intval($this->pi_getFFvalue( $this->flexform, 'limit', 'sDEF'));
		$flex['categoryMode'] = intval($this->pi_getFFvalue( $this->flexform, 'categoryMode', 'sDEF'));
		$flex['categorySelection'] = intval($this->pi_getFFvalue( $this->flexform, 'categorySelection', 'sDEF'));
		return $flex;
	}
	
	function getNewsToDisplay($flex) {
		$this->conf['newsPlugin.']['pid_list'] = $flex['newsstorage']?$flex['newsstorage']:$this->conf['newsPlugin.']['pid_list'];
		$this->conf['newsPlugin.']['code'] = $flex['what_to_display']?$flex['what_to_display']:$this->conf['newsPlugin.']['code'];
		$this->conf['newsPlugin.']['limit'] = $flex['limit']?$flex['limit']:$this->conf['newsPlugin.']['limit'];
		$this->conf['newsPlugin.']['categoryMode'] =  $flex['categoryMode']?$flex['categoryMode']:$this->conf['newsPlugin.']['categoryMode'];
		$this->conf['newsPlugin.']['categorySelection'] =  $flex['categorySelection']?$flex['categorySelection']:$this->conf['newsPlugin.']['categorySelection'];
		
		if($flex['singleNews'])
		{
		  // Display single news as inserted in plugin 
			$GLOBALS['TSFE']->displayedNews = explode(',',($flex['singleNews']));
		} else {
			//Call tt_news Plugin once so that TSFE:displayedNews gets loaded
			$this->getSingle($data, $this->conf['newsPlugin'], $this->conf['newsPlugin.']);
		}
		//load news	
		$marker_data = array();
		if(is_array($GLOBALS['TSFE']->displayedNews)) {
			for($i = 0; $i < sizeOf($GLOBALS['TSFE']->displayedNews); $i++) {
				$singleWhere = 'tt_news.uid=' . intval($GLOBALS['TSFE']->displayedNews[$i]);
				$singleWhere .= ' AND type NOT IN(1,2)' . $this->cObj->enableFields('tt_news'); // only real news -> type=0
		
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
					'*',
					'tt_news',
					$singleWhere);
		
				$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
				if($row) {
					$marker = array();
					$marker['address'] = $this->getSingle($row, $this->conf['address'], $this->conf['address.']);
					// Zeilenumbrüche entfernen
					$marker['address'] = str_replace(array("\r","\n"), array('',''), $marker['address']);
					$marker['alttext'] = $this->getSingle($row, $this->conf['alttext'], $this->conf['alttext.']);
					$marker['alttext'] = str_replace(array("\r","\n"), array('<br />','<br />'), $marker['alttext']);
					$marker['lon'] = $this->getSingle($row, $this->conf['lon'], $this->conf['lon.']);
					$marker['lat'] = $this->getSingle($row, $this->conf['lat'], $this->conf['lat.']);
					$marker['marker'] =  $this->getSingle($row, $this->conf['marker'], $this->conf['marker.']);
					$marker['text'] =  $this->getSingle($row, $this->conf['text'], $this->conf['text.']);
					$marker_data[] = $marker;
				}
			}
		}
		return $marker_data;
	}

}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/lonewsaddress/pi1/class.tx_lonewsaddress_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/lonewsaddress/pi1/class.tx_lonewsaddress_pi1.php']);
}

?>