<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

t3lib_extMgm::addPItoST43($_EXTKEY,'pi1/class.tx_lonewsaddress_pi1.php','_pi1','list_type',1);

if (TYPO3_MODE == 'FE')    {
    require_once(t3lib_extMgm::extPath('lonewsaddress').'class.tx_lonewsaddress_hook.php');
}
$TYPO3_CONF_VARS['EXTCONF']['tt_news']['extraItemMarkerHook'][]   = 'tx_lonewsaddress_hook';

if(t3lib_extMgm::isLoaded('rggooglemap')){
	t3lib_extMgm::addService($_EXTKEY,  'rggmData' /* sv type */,  'tx_lonewsaddress_sv1' /* sv key */,
		array(

			'title' => 'RGGoogle Maps Service',
			'description' => '',

			'subtype' => 'tt_news',

			'available' => TRUE,
			'priority' => 50,
			'quality' => 50,

			'os' => '',
			'exec' => '',

			'classFile' => t3lib_extMgm::extPath($_EXTKEY).'sv1/class.tx_lonewsaddress_sv1.php',
			'className' => 'tx_lonewsaddress_sv1',
		)
	);
}
?>