/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
   config.filebrowserBrowseUrl = 'editors/kcfinder/browse.php?type=files';
   config.filebrowserImageBrowseUrl = 'editors/kcfinder/browse.php?type=content';
   config.filebrowserFlashBrowseUrl = 'editors/kcfinder/browse.php?type=flash';
   config.filebrowserUploadUrl = 'editors/kcfinder/upload.php?type=files';
   config.filebrowserImageUploadUrl = 'editors/kcfinder/upload.php?type=content';
   config.filebrowserFlashUploadUrl = 'editors/kcfinder/upload.php?type=flash'; 
	
	   CKEDITOR.config.toolbar_BODY=[     ['Source','-','NewPage','Preview','Templates'],
    ['Cut','Copy','Paste','PasteText','PasteFromWord'],
    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],['Image','Flash','Table','HorizontalRule','SpecialChar','PageBreak'],
    '/',
    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
    ['BidiLtr', 'BidiRtl'],
    ['Link','Unlink','Anchor'],
    
    '/',
    ['Styles','Format','Font','FontSize'],
    ['TextColor','BGColor'],
    ['Maximize', 'ShowBlocks'] ];
	
	CKEDITOR.config.toolbar_BLURB=[ ['Styles', 'Format'],
            ['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link','Unlink'] ];
	
};
