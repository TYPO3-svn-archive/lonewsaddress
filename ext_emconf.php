<?php

########################################################################
# Extension Manager/Repository config file for ext: "lonewsaddress"
#
# Auto generated 27-10-2009 10:18
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
	'version' => '1.0.0',
	'constraints' => array(
		'depends' => array(
			'tt_news' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:23:{s:9:"ChangeLog";s:4:"8187";s:10:"README.txt";s:4:"ee2d";s:31:"class.tx_lonewsaddress_hook.php";s:4:"fa79";s:12:"ext_icon.gif";s:4:"1bdc";s:17:"ext_localconf.php";s:4:"2cb3";s:14:"ext_tables.php";s:4:"6749";s:14:"ext_tables.sql";s:4:"3b03";s:16:"locallang_db.xml";s:4:"93e4";s:14:"doc/manual.sxw";s:4:"4fb6";s:19:"doc/wizard_form.dat";s:4:"a524";s:20:"doc/wizard_form.html";s:4:"152b";s:28:"lib/LoGoogleMapAPI.class.php";s:4:"4ba9";s:34:"pi1/class.tx_lonewsaddress_pi1.php";s:4:"821f";s:17:"pi1/locallang.xml";s:4:"f24d";s:24:"pi1/static/editorcfg.txt";s:4:"a0d6";s:20:"pi1/static/setup.txt";s:4:"d41d";s:18:"res/mapHeader.html";s:4:"612d";s:22:"res/mapListHeader.html";s:4:"1a2d";s:45:"static/lonewsadresses_must_have/constants.txt";s:4:"bc86";s:41:"static/lonewsadresses_must_have/setup.txt";s:4:"cec2";s:50:"static/lonewsadresses_standarddesign/constants.txt";s:4:"d41d";s:46:"static/lonewsadresses_standarddesign/setup.txt";s:4:"d41d";s:34:"sv1/class.tx_lonewsaddress_sv1.php";s:4:"ebb6";}',
	'suggests' => array(
	),
);

?>