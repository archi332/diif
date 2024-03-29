 /* ===================================================
 * ZOOfilter Search Default layout script
 * https://zoolanders.com/extensions/zoofilter
 * ===================================================
 * Copyright (C) JOOlanders SL 
 * http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 * ========================================================== */

(function($) {

	/* Placeholder
	 * placeholder attribute fallback
	 *
	 * using jQuery Placehold plugin by Viget Inspire(http://www.viget.com/inspire/)
	 * http://www.viget.com/inspire/a-jquery-placeholder-enabling-plugin/
	 */
	var initPlaceholder = function() {
		$('input[placeholder]').placehold();
	};
	if(!Modernizr.input.placeholder) {
		$(document).ready(initPlaceholder);
	};

})(jQuery);
(function ($) {
	var Plugin = function(){};
	Plugin.prototype = $.extend(Plugin.prototype, {
		name: 'ZOOfilterSearchDefault',
		options: {
			button: ''
		},
		initialize: function(module, options) {
			this.options = $.extend({}, this.options, options);
			var $this = this;

			$($this.options.button, module).click(function(){
				$this.resetForm(module);
			});
		},
		resetForm: function(module){
			$(':text, :password, :file', module).val('');
			$(':input, select option', module).removeAttr('checked').removeAttr('selected');
			if ($('select').attr('multiple')) {
				$('select option:first', module).attr('selected', false);					
			} else {
				$('select option:first', module).attr('selected', true);					
			}
		}
	});
	// Don't touch
	$.fn[Plugin.prototype.name] = function() {
		var args   = arguments;
		var method = args[0] ? args[0] : null;
		return this.each(function() {
			var element = $(this);
			if (Plugin.prototype[method] && element.data(Plugin.prototype.name) && method != 'initialize') {
				element.data(Plugin.prototype.name)[method].apply(element.data(Plugin.prototype.name), Array.prototype.slice.call(args, 1));
			} else if (!method || $.isPlainObject(method)) {
				var plugin = new Plugin();
				if (Plugin.prototype['initialize']) {
					plugin.initialize.apply(plugin, $.merge([element], args));
				}
				element.data(Plugin.prototype.name, plugin);
			} else {
				$.error('Method ' +  method + ' does not exist on jQuery.' + Plugin.name);
			}
		});
	};
})(jQuery);