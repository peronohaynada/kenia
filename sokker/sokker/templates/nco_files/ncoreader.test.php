<?php
//  http://localhost/sokker/templates/nco_files/ncoreader.test.php
require_once 'ncoHelper.class.php';
require_once '../../util/constant.definition.php';

$index = new NCOHelper(Constants::$nco_skeleton_template);
$links;
$links = '<li><a href="?update"><span>update</span></a></li>
<li><a href="?settings"><span>settings</span></a></li>
<li><a href="logout.php"><span>logout</span></a></li>';
$index->readNCOFile();
$index->addContentToBuffer(Constants::$li_buttons, $links);

echo $index->getBuffer();