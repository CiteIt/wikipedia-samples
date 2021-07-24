(function() {
	tinymce.PluginManager.add('citeit_q', function( editor, url ) {
		editor.addButton('citeit_q', {
			text: 'CiteIt.net inline popup',
			icon: false,
			onclick: function() {
                editor.windowManager.open( {
                    title: 'Insert inline popup',
                    body: [{
                        type: 'textbox',
                        name: 'cite',
                        label: 'Quote Source Link'
                    }],
                    onsubmit: function( e ) {
                        editor.insertContent( '<q cite="' + e.data.cite + '">' + editor.selection.getContent({format : 'html'}) + '</q>');
                    }
                });
			}
		});
	});

})();    
