// default
// theme_advanced_buttons1 : "styleselect,code,formatselect,removeformat,separator,cut,copy,paste,undo,redo,separator,justifyleft,justifycenter,justifyright,justifyfull,numlist,bullist,indent,outdent",
// theme_advanced_buttons2 : "cleanup,fontselect,fontsizeselect,bold,italic,underline,strikethrough,sub,sup,forecolor,backcolor,link,unlink,pasteword",

tinyMCE.init({
  language : "en",
  mode : "specific_textareas",
  editor_selector : "HTML-editor-default",
  theme : "advanced",
  entity_encoding : "raw",
  cleanup : true,
  invalid_elements : "p|div",
  force_p_newlines : false,
  force_br_newlines : true,
  relative_urls : false,
  convert_urls : false,
  forced_root_block : false,
  content_css : "/themes/default/css/mce.css",
  plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
  theme_advanced_buttons1 : "code,separator,image,separator,cut,copy,paste,undo,redo,separator,justifyleft,justifycenter,justifyright,separator,styleselect",
  theme_advanced_buttons2 : "bold,italic,underline,strikethrough,sub,sup,forecolor,backcolor,link,unlink,pasteword",
  theme_advanced_buttons3 : "",
  paste_create_paragraphs : false,
  paste_create_linebreaks : false,
  paste_use_dialog : true,
  paste_auto_cleanup_on_paste : true,
  paste_convert_middot_lists : false,
  paste_unindented_list_class : "unindentedList",
  paste_convert_headers_to_strong : true,
  paste_insert_word_content_callback : "convertWord",
  theme_advanced_resizing : true,
  theme_advanced_more_colors : true,
  theme_advanced_resize_horizontal : false,
  theme_advanced_resizing_use_cookie : true,
  theme_advanced_toolbar_location : "top",
  theme_advanced_toolbar_align : "left",
  theme_advanced_path_location : "bottom",
  onchange_callback : 'enableSubmitButtons'
});
// file_browser_callback : 'browseFiles'
// For pasting content from MS Word

function convertWord(type, content) {
  switch (type) {
    // Gets executed before the built in logic performes it's cleanups
    case "before":
      //content = content.toLowerCase(); // Some dummy logic
      break;

    // Gets executed after the built in logic performes it's cleanups
    case "after":
      //content = content.toLowerCase(); // Some dummy logic
      break;
  }
  return content;
}

//  plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",