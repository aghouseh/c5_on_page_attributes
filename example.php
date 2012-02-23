<?php

// define the helper
$opa = Loader::helper('on_page_attributes');

// i have this abstracted so you can pass an arg to this included file of an array of attribute handles
if(!isset($onPageAttributes)) {
	$onPageAttributes = array('remove_divider','show_page_title','alt_page_title','right_sidebar_type','right_sidebar_width');
}
echo Loader::helper('on_page_attributes')->getAttributeForm($onPageAttributes,'Page Settings');

?>
