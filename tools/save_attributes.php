<?php defined('C5_EXECUTE') or die("Access Denied.");

Loader::model("attribute/categories/collection");
$page = Page::getByID($_POST['cID']);
if (is_object($page) && $_POST['akID']) {

	foreach ($_POST['akID'] as $akey => $aval){
		$ak = CollectionAttributeKey::getByID($akey);
		if (is_object($ak)) {
			$ak->saveAttributeForm($page);
		} else {
			$error[] = 'Attribute not found.';
		}
	}
	
} elseif (!$_POST['akID']) {
	$error[] = 'Page not found.';
} else {
	$error[] = 'Attribute not passed.';
}

if (!$error) {
	echo 'Settings saved.';
} else {
	echo implode(' / ',$error);
}