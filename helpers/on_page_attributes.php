<?php   defined('C5_EXECUTE') or die("Access Denied.");

/**
 * Class that is used to display page settings inline on the page.
 * Meant to function independent of edit mode & only when proper permissions apply
 * 
 * @author Andrew Householder <andrew@artesiandesigninc.om>
 * @category Helpers
 * @copyright  Copyright (c) 2011 Artesian Design, Inc.
 */

class OnPageAttributesHelper {

	public function getAttributeForm($attributes, $title) {
	
		if (is_array($attributes) && count($attributes) > 0) {
			$fh = Loader::helper('form');
			$ih = Loader::helper('concrete/interface');
			$page = Page::getCurrentPage();
			$id = rand();
	
			$form = '<div class="toolbar ccm-ui">
				<div class="ccm-pane-body">
				<h4 class="collapsed"><span></span>'.$title.'</h4>
				<form class="attributes" action="' . BASE_URL . DIR_REL . '/' . DIRNAME_TOOLS . '/save_attributes" method="POST" id="'. $id .'" style="display:none;">
				<table>';
				
			foreach ($attributes as $a) { 
				$form .= OnPageAttributesHelper::getAttribute($a);
			}

	    	$form .= '</table>';
			$form .= $fh->hidden('cID',$page->cID);
			$form .= $ih->button(t('Save'), 'save', 'left', 'primary', array('data-loading-text'=>'Saving...','id'=>'import-submit'));
	    	//$form .= $fh->submit('Save','Save',array('class'=>'btn primary'));
	
	    	$form .= '
	    			<span class="help-inline"></span>
	    			</div>
	    		</form>
	    		<script>
	    		$(function(){
	    		
	    			$("#import-submit").click(function(e){
	    				e.preventDefault();
	    				$(this).closest("form").submit();
	    			});
	    			
	   				$("#'.$id.'").submit(function(e){
	    				e.preventDefault();
						$.post($(this).attr("action"), $(this).serialize(), function(data) {
							$("#'.$id.'").children(".help-inline").html(data).show().delay(2000).fadeOut(1000);
							var checkInUrl = "'.BASE_URL.DIR_REL.'/index.php?cID='.$page->cID.'&ctask=check-in&ccm_token=" + CCM_SECURITY_TOKEN;
							if (!CCM_EDIT_MODE) { 
								$.post(checkInUrl);
							} else {
								window.location.href = checkInUrl;
							}
						});
					});
					$(".toolbar h4").click(function(){
						$(this).toggleClass("collapsed");
						$(this).siblings("form").slideToggle(200);
					});
	    		});
	    		</script>
			</div>';
	    		
	    	return $form;
    	
    	} else {
    	
    		return false;
    		
    	}
    	
    }
    
    public function getAttribute($handle) {

		Loader::model('attribute/categories/collection');
		$ao = CollectionAttributeKey::getByHandle($handle);
		
		if (is_object($ao)) {
			ob_start();
				$ao->render('form',Page::getCurrentPage()->getAttributeValueObject($ao));
	  			$element = ob_get_contents();
	  		ob_end_clean();
	
			// checkbox workaround
			if ($ao->atID == 3){ 
				$element = '<input type="hidden" name="akID['.$ao->akID.'][value]" value="0" />'.$element;
			}
			
	  		return '<tr><td class="attribute '.$ao->atHandle.'"><label for="akID['.$ao->akID.'][value]">'. $ao->akName . '</label></td><td>' . $element . '</td></tr>';
		}

    }
    
}