// overview of ckeditor textarea form

define(function(require) {
	var elgg = require('elgg');
	var $ = require('jquery');

	return {
		toolbar: [['Bold', 'Italic', 'Underline', 'RemoveFormat']/*, ['NumberedList', 'BulletedList', 'Undo', 'Redo', 'Blockquote', 'Paste', 'PasteFromWord', 'Maximize']*/],
		removeButtons: 'Subscript,Superscript', // To have Underline back
		allowedContent: true,
		baseHref: elgg.config.wwwroot,
		removePlugins: 'contextmenu,tabletools,resize',
		extraPlugins: 'blockimagepaste',
		defaultLanguage: 'en',
		language: elgg.config.language,
		skin: 'moono',
		uiColor: '#EEEEEE',
		contentsCss: elgg.get_simplecache_url('css', 'elgg/wysiwyg.css'),
		disableNativeSpellChecker: false,
		disableNativeTableHandles: false,
		removeDialogTabs: 'image:advanced;image:Link;link:advanced;link:target',
		autoGrow_maxHeight: $(window).height() - 100
	};
});
