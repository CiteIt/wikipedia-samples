(function() {
	tinymce.PluginManager.add('neotext_q', function( editor, url ) {
		editor.addButton('neotext_q', {
			text: 'CiteIt.net q',
			icon: false,
			onclick: function() {
                editor.windowManager.open( {
                    title: 'Insert inline-quote link',
                    body: [{
                        type: 'textbox',
                        name: 'cite',
                        label: 'Quote Source Link'
                    }],
                    onsubmit: function( e ) {
                        editor.insertContent( '<q cite="' + e.data.cite + '">' + editor.selection.getSel() + '</q>');
                    }
                });
			}
		});
	});

})();    