jQuery(document).ready(function($){
	tinymce.create('tinymce.plugins.pridecal_plugin_plugin', {
		init: function(ed, url){
			ed.addCommand('pridecal_plugin_insert_shortcode', function(){
				selected = tinyMCE.activeEditor.selection.getContent();
				if(selected){
					// our shortcode doesnt handle content
					content = selected + '[pridecal_list]';
				} else {
					content = '[pridecal_list]';
				}
				tinymce.execCommand('mceInsertContent', false, content);
			});
			ed.addButton('pridecal_plugin_button', {
				title: 'Füge Shortcode für Pride Kalender ein',
				cmd: 'pridecal_plugin_insert_shortcode',
				image: url + '/pridecal_button.png'
			});
		},
	});
	tinymce.PluginManager.add('pridecal_plugin_button', tinymce.plugins.pridecal_plugin_plugin);
});
