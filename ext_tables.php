<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}
$tempColumns = array (
	'tx_lonewsaddress_mapname' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:lonewsaddress/locallang_db.xml:tt_news.tx_lonewsaddress_mapname',		
		'config' => array (
			'type' => 'input',	
			'size' => '30',
		)
	),
	'tx_lonewsaddress_address' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:lonewsaddress/locallang_db.xml:tt_news.tx_lonewsaddress_address',		
		'config' => array (
			'type' => 'input',	
			'size' => '30',
		)
	),
	'tx_lonewsaddress_zip' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:lonewsaddress/locallang_db.xml:tt_news.tx_lonewsaddress_zip',		
		'config' => array (
			'type' => 'input',	
			'size' => '5',
		)
	),
	'tx_lonewsaddress_city' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:lonewsaddress/locallang_db.xml:tt_news.tx_lonewsaddress_city',		
		'config' => array (
			'type' => 'input',	
			'size' => '10',
		)
	),
	'tx_lonewsaddress_country' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:lonewsaddress/locallang_db.xml:tt_news.tx_lonewsaddress_country',		
		'config' => array (
			'type' => 'input',	
			'size' => '30',
		)
	),
	'tx_lonewsaddress_url' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:lonewsaddress/locallang_db.xml:tt_news.tx_lonewsaddress_url',		
		'config' => array (
			'type'     => 'input',
			'size'     => '15',
			'max'      => '255',
			'checkbox' => '',
			'eval'     => 'trim',
			'wizards'  => array(
				'_PADDING' => 2,
				'link'     => array(
					'type'         => 'popup',
					'title'        => 'Link',
					'icon'         => 'link_popup.gif',
					'script'       => 'browse_links.php?mode=wizard',
					'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
				)
			)
		)
	),
	'tx_lonewsaddress_phone' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:lonewsaddress/locallang_db.xml:tt_news.tx_lonewsaddress_phone',		
		'config' => array (
			'type' => 'input',	
			'size' => '15',
		)
	),
	'tx_lonewsaddress_email' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:lonewsaddress/locallang_db.xml:tt_news.tx_lonewsaddress_email',		
		'config' => array (
			'type'     => 'input',
			'size'     => '15',
			'max'      => '255',
			'checkbox' => '',
			'eval'     => 'trim',
			'wizards'  => array(
				'_PADDING' => 2,
				'link'     => array(
					'type'         => 'popup',
					'title'        => 'Link',
					'icon'         => 'link_popup.gif',
					'script'       => 'browse_links.php?mode=wizard',
					'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
				)
			)
		)
	),
	'tx_lonewsaddress_hours' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:lonewsaddress/locallang_db.xml:tt_news.tx_lonewsaddress_hours',		
		'config' => array (
			'type' => 'text',
			'cols' => '40',
			'rows' => '3'
		)
	),
	'tx_lonewsaddress_logo' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:lonewsaddress/locallang_db.xml:tt_news.tx_lonewsaddress_logo',		
		'config' => array (
			'type' => 'group',
			'internal_type' => 'file',
			'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],	
			'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],	
			'uploadfolder' => 'uploads/tx_lonewsaddress',
			'show_thumbs' => 1,	
			'size' => 1,	
			'minitems' => 0,
			'maxitems' => 1,
		)
	),
	'tx_lonewsaddress_lng' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:lonewsaddress/locallang_db.xml:tt_news.tx_lonewsaddress_lng',		
		'config' => array (
			'type' => 'input',	
			'size' => '30',
		)
	),
	'tx_lonewsaddress_lat' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:lonewsaddress/locallang_db.xml:tt_news.tx_lonewsaddress_lat',		
		'config' => array (
			'type' => 'input',	
			'size' => '30',
		)
	),
	'tx_lonewsaddress_cat' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:lonewsaddress/locallang_db.xml:tt_news.tx_lonewsaddress_cat',		
		'config' => array (
			'type' => 'input',	
			'size' => '30',
		)
	),
);


t3lib_div::loadTCA('tt_news');
t3lib_extMgm::addTCAcolumns('tt_news',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('tt_news','--div--;Address, tx_lonewsaddress_mapname;;;;1-1-1, tx_lonewsaddress_address, tx_lonewsaddress_zip, tx_lonewsaddress_city, tx_lonewsaddress_country, tx_lonewsaddress_url, tx_lonewsaddress_phone, tx_lonewsaddress_email, tx_lonewsaddress_hours, tx_lonewsaddress_logo,--div--;Geocoding, tx_lonewsaddress_lng, tx_lonewsaddress_lat, tx_lonewsaddress_cat');


t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1'] = 'pi_flexform';

t3lib_extMgm::addPlugin(array('LLL:EXT:lonewsaddress/locallang_db.xml:tt_content.list_type_pi1', $_EXTKEY.'_pi1'),'list_type');
t3lib_extMgm::addPiFlexFormValue( $_EXTKEY . '_pi1', 'FILE:EXT:lonewsaddress/flexform_ds.xml' );

t3lib_extMgm::addStaticFile($_EXTKEY,'static/lonewsaddress_must_have/', 'LO Newsaddress Markers');
t3lib_extMgm::addStaticFile($_EXTKEY,"pi1/static/","LO Newsaddress Plugin Googlemaps");

if (TYPO3_MODE=="BE")	$TBE_MODULES_EXT["xMOD_db_new_content_el"]["addElClasses"]["tx_lonewsaddress_pi1_wizicon"] = t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_lonewsaddress_pi1_wizicon.php';
?>