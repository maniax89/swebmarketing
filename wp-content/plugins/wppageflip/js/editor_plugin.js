(function() {
	tinymce.create('tinymce.plugins.PageflipBook', {
		init : function(ed, url) {
			ed.addButton('pageflipbook', {
				title : 'Add your PageflipBook HTML code',
				image : url+'/../images/book_open.png',
				onclick : function() {
					ed.execCommand('mceInsertContent', false, '[PageFlipBook_Lite]');
				}
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
				longname : "Add your PageflipBook HTML code",
				author : 'Informatique de France',
				authorurl : 'http://www.informatiquedefrance.com/',
				infourl : 'http://www.informatiquedefrance.com/',
				version : "0.1"
			};
		}
	});
	tinymce.PluginManager.add('pageflipbook', tinymce.plugins.PageflipBook);
})();