<?php

########################################################################
# Extension Manager/Repository config file for ext: "lonewsaddress"
#
# Auto generated 21-11-2009 10:24
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Add adresses and RgGooglemap to TT News',
	'description' => 'Enables TT_News for RgGooglemap, adds additional markers for adress output.',
	'category' => 'fe',
	'author' => 'Lina Ourima',
	'author_email' => '2009@lotypo3.de',
	'shy' => '',
	'dependencies' => 'tt_news',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author_company' => '',
	'version' => '1.1.1',
	'constraints' => array(
		'depends' => array(
			'tt_news' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:28:{s:9:"ChangeLog";s:4:"8187";s:10:"README.txt";s:4:"ee2d";s:31:"class.tx_lonewsaddress_hook.php";s:4:"d17e";s:12:"ext_icon.gif";s:4:"ebee";s:17:"ext_localconf.php";s:4:"2cb3";s:14:"ext_tables.php";s:4:"070a";s:14:"ext_tables.sql";s:4:"3b03";s:15:"flexform_ds.xml";s:4:"c425";s:13:"locallang.xml";s:4:"93fb";s:16:"locallang_db.xml";s:4:"93e4";s:17:"locallang_tca.xml";s:4:"0215";s:28:"lib/LoGoogleMapAPI.class.php";s:4:"4ba9";s:35:"res/templ_lonewsadress_example.html";s:4:"7c05";s:30:"res/rggooglemaps/template.html";s:4:"4f29";s:14:"pi1/ce_wiz.gif";s:4:"4a8c";s:14:"pi1/ce_wiz.png";s:4:"7211";s:34:"pi1/class.tx_lonewsaddress_pi1.php";s:4:"652e";s:42:"pi1/class.tx_lonewsaddress_pi1_wizicon.php";s:4:"add8";s:17:"pi1/locallang.xml";s:4:"f24d";s:24:"pi1/static/constants.txt";s:4:"a158";s:24:"pi1/static/editorcfg.txt";s:4:"a0d6";s:20:"pi1/static/setup.txt";s:4:"7b47";s:14:"doc/manual.sxw";s:4:"2b1d";s:19:"doc/wizard_form.dat";s:4:"a524";s:20:"doc/wizard_form.html";s:4:"152b";s:34:"sv1/class.tx_lonewsaddress_sv1.php";s:4:"ebb6";s:44:"static/lonewsaddress_must_have/constants.txt";s:4:"d41d";s:40:"static/lonewsaddress_must_have/setup.txt";s:4:"9f9f";}',
	'suggests' => array(
	),
);

?>