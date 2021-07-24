// Credit: Tomasz Dziuda: https://www.gavick.com/blog/wordpress-tinymce-custom-buttons

(function() {
	tinymce.PluginManager.add('neotext_blockquote', function( editor, url ) {
		editor.addButton('neotext_blockquote', {
			text: 'CiteIt.net blockquote',
			icon: false,
			onclick: function() {
                editor.windowManager.open( {
                    title: 'Insert blockquote link',
                    body: [{
                        type: 'textbox',
                        name: 'cite',
                        label: 'Quote Source Link'
                    }],
                    onsubmit: function( e ) {
                        editor.insertContent( '<blockquote cite="' + e.data.cite + '">' + editor.selection.getSel() + '</blockquote>');
                    }
                });
			}
		});
	});

})();    