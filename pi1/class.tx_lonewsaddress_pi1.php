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
 * @author	Lina Ourima <2009@lotypo3.de>
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

		$data= $this->cObj->data;
		$this->pi_loadLL();
		$this->init();
		//Call tt_news Plugin once so that TSFE:displayedNews gets loaded
		$this->getSingle($data, $this->conf['newsPlugin'], $this->conf['newsPlugin.']);
		$data = array();
		$this->map_data['width'] = $this->getSingle($data, $this->conf['width'], $this->conf['width.']);
		$this->map_data['height'] = $this->getSingle($data, $this->conf['height'], $this->conf['height.']);
		$this->map_data['zoom'] = $this->getSingle($data, $this->conf['zoom'], $this->conf['zoom.']);
		//load news
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
					$marker['alttext'] = $this->getSingle($row, $this->conf['alttext'], $this->conf['alttext.']);
					$marker['lon'] = $this->getSingle($row, $this->conf['lon'], $this->conf['lon.']);
					$marker['lat'] = $this->getSingle($row, $this->conf['lat'], $this->conf['lat.']);
					$marker['marker'] =  $this->getSingle($row, $this->conf['marker'], $this->conf['marker.']);
					$this->marker_data[] = $marker;
				}
			}
		}
		


		$map = new LoGoogleMapAPI('lonewsaddress_div');
		// optional marker icon:
		if($this->map_data['marker']) {
			$map->setMarkerIcon($this->map_data['marker']);
		}

		
      //    Load Language from locallang and overwrite strings in GoogleMapAPI-object
        

		foreach ($this->lang_string_names as $lang_string)
		{
			$translation=$this->pi_getLL($lang_string,'',1);
			if(!empty($translation))
				$map->driving_dir_text[$lang_string]=$translation;
		}

  
    // 2008 added Multi-Domain-API-Keys by eloh@zellwerk.com
    

		$APIkeys= array();
		$hostname= $_SERVER['HTTP_HOST'];
		
		$apikey= 'ABQIAAAAx0zE4ZviawOYjwRPkAqzKRS9dLbFTjWje0lbpy7ODaOrADAoBhSbpFErjfQHUKm80B5ImCBbfWWT-w';
		//!empty($data['imagecaption']) ? $data['imagecaption'] : $this->conf['apikey'];
        $tmpkeys= preg_split('~\s*\r?\n\s*~', trim($apikey), -1, PREG_SPLIT_NO_EMPTY);
		if (1< count($tmpkeys))
		{
			foreach ($tmpkeys as $row)
			{
				$M= preg_split('~\s*=\s*~', $row);
				if (count($M)== 2)
					$APIkeys[$M[0]]= $M[1];
				else
					$APIkeys['*']= $M[0]; // last not marked String is default
			}
		}
		elseif (0< count($tmpkeys))
		{
			$APIkeys[$hostname]= preg_replace('~[^=]+=\s*~', '', $tmpkeys[0]);
		}
		else
			$APIkeys[$hostname]= '';

		if (empty($APIkeys[$hostname]))
			$hostname= '*';

		if (empty($APIkeys[$hostname]))
		{
			return $this->pi_wrapInBaseClass("<div style=\"background:#FFFFFF; border:1px solid #CC0000; margin:4px; padding:4px;\" >No valid API key for <b >{$hostname}</b>.</div>");
		}
		else
	    	$map->setAPIKey($APIkeys[$hostname]);
  
 // ----------------------------------------------------------------------------


        $map->setWidth($this->map_data['width'].'px');

        $map->setHeight($this->map_data['height'].'px');

        $map->setZoomLevel($this->map_data['zoom']);

        //ze$map->disableSidebar();
        //$alttext=empty($data['tx_lonewsaddress_showaddress'])?$address:$data['tx_lonewsaddress_showaddress'];
	     for($i = 0; $i < sizeOf($this->marker_data); $i++)
	     {   
				//plutz: add support for coords in address - used to work as is - change in google-api ?
				if (is_numeric($this->marker_data[$i]['lon'])&&is_numeric($this->marker_data[$i]['lat']))
					{
						$map->addMarkerByCoords($this->marker_data[$i]['lon'],$this->marker_data[$i]['lat'],$this->marker_data[$i]['alttext'],preg_replace("((\r\n)+)",'',$this->marker_data[$i]['text']));
						}
					else
					{
				$map->addMarkerByAddress($this->marker_data[$i]['address'],$this->marker_data[$i]['alttext'],preg_replace("((\r\n)+)",'',$this->marker_data[$i]['text']));
				}
			}
        
		//	cache generated JavaScript
		
        $md5= substr(md5(join($this->map_data,$this->marker_data)), 0, 10);
        $filename="typo3temp/gmap_{$md5}.js";
        
		// ewent: no_cache to renew the temporary js file
        if (!file_exists($filename) or intval($_GET['no_cache']) == 1) {
			$fh=fopen($filename,'w');
			fputs($fh,preg_replace('/<\/?script[^>]*>/i','',$map->getMapJS()));
			fclose($fh);
        }

		$GLOBALS["TSFE"]->register["gmap_script_head"] = $map->getHeaderJS() ."\n<script src='{$filename}' type='text/javascript' ></script>";

        $sidebar_dummy = '<div id="sidebar_lonewsaddress_div" style="display:none"></div>';

		// ewent: workaround for HTTPS-sites 
		// depends on proper configuration (screenshotForSSL, onlyForIE8)
		$screenalttext = $this->pi_getLL('screen_alttext','',1);
		if($this->conf['screenshotForSSL']) { # and ($_SERVER['HTTPS'] == 'on' or $_SERVER['SERVER_PORT'] == 443)) {
			if(!file_exists($this->conf['screenshotForSSL'])) {
				$img = '<b>'. $data['tx_lonewsaddress_gaddress']. '</b>: '. $screenalttext;
			} else {
				$img = '<img src="'. $this->conf['screenshotForSSL'] .'" border="0">';
			}

			$screenshot = '<div><a href="http://maps.google.com/maps?q='. urlencode($data['tx_lonewsaddress_gaddress']) .'&z=15" target="amapwin" alt="'. $screenalttext .'" title="'. $screenalttext .'">'. $img .'</a></div>';
		} else {
			$screenshot = '';
		}

		$content .= '<div id="lonewsaddress_code">'. $map->getMap() . $map->getOnLoad() 
                    .'<script type="text/javascript" >
                  function lonewsaddress_popit() {
                    if(isArray(marker_html[0])) { markers[0].openInfoWindowTabsHtml(marker_html[0]); }
                    else { markers[0].openInfoWindowHtml(marker_html[0]); }
                  }
                  setTimeout("lonewsaddress_popit()",1000);
                  </script>'. $sidebar_dummy
				  . '</div>';

		if($screenshot) {
			if($this->conf['onlyForIE8']) {
				$content .= $this->cObj->wrap($screenshot, '<!--[if gte IE 8]> | 
						<style>
						#lonewsaddress_code	{ display:none; }
						</style>
						<![endif]-->'); 
			} 
		}

		return $this->pi_wrapInBaseClass($content);
	}
	
	/**
	 * Parses data through typoscript.
	 */
	function getSingle($data, $ts_type, $ts) {
		$this->localcObj->data = $data;
		return $this->localcObj->cObjGetSingle($ts_type, $ts);
	}

}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/lonewsaddress/pi1/class.tx_lonewsaddress_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/lonewsaddress/pi1/class.tx_lonewsaddress_pi1.php']);
}

?>
