/**
 * @package     ZOOlanders
 * @version     3.3.20
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

!function(t,e,i,n){"use strict";var a=function(e,i){var n=this;n.options=t.extend({},this.options,i),n.events={};var a=n.options.field;a="fields"+a.charAt(0).toUpperCase()+a.slice(1)+"Field",delete n.options.field,t(e).zlux(a,n.options)};t.zlux.fields=a}(jQuery,window,document),function(t,e,i,n){"use strict";var a=function(e,i){var n=this,o=t(e);o.data(a.prototype.name)||(n.element=t(e),n.options=t.extend({},a.prototype.options,i),this.events={},n.initialize(),n.element.data(a.prototype.name,n))};t.extend(a.prototype,t.zlux.Main.prototype,{name:"fieldsOptionsField",options:{},initialize:function(){var e=this;e.list=t("ul",e.element),e.hidden=t("li.hidden",e.list).detach(),e.element.addClass("zl-bootstrap"),e.element.on("click",".zlux-x-delete",function(){return t(this).closest("li").slideUp(400,function(){t(this).remove(),e.orderOptions()}),!1}).on("click",".zlux-x-add",function(){e.hidden.clone().removeClass("hidden").appendTo(e.list).slideDown(200).effect("highlight",{},1e3).find("input:first").focus(),e.orderOptions()}).on("blur",".zlux-x-name input",function(){var i=t(this).closest("li"),n=i.find(".panel input:text");if(""!==t(this).val()&&""===n.val()){var a="";e.getAlias(t(this).val(),function(t){a=t?t:"42",n.val(a),i.find("a.trigger").text(a)})}}).on("keydown",".panel input:text",function(i){i.stopPropagation(),13===i.which&&e.setOptionValue(t(this).closest("li")),27===i.which&&e.removeOptionPanel(t(this).closest("li"))}).on("click","input.accept",function(){return e.setOptionValue(t(this).closest("li")),!1}).on("click","a.cancel",function(){return e.removeOptionPanel(t(this).closest("li")),!1}).on("click","a.trigger",function(){return t(this).hide().closest("li").find("div.panel").addClass("active").find("input:text").focus(),!1}),this.list.sortable({handle:".zlux-x-sort",containment:this.list.parent().parent(),placeholder:"dragging",axis:"y",opacity:1,revert:75,delay:100,tolerance:"pointer",zIndex:99,start:function(t,i){i.placeholder.height(i.helper.height()),e.list.sortable("refreshPositions")},stop:function(){e.orderOptions()}})},setOptionValue:function(t){var e=this,i=t.find("div.panel input:text"),n=i.val();""===n&&(n=t.find("div.zlux-x-name input").val()),this.getAlias(n,function(a){n=a?a:"42",i.val(n),t.find("a.trigger").text(n),e.removeOptionPanel(t)})},orderOptions:function(){var e=this,i=/(elements\[\S+])\[(-?\d+)\]/g;e.list.children("li").each(function(e){t(this).find("input").each(function(){t(this).attr("name")&&t(this).attr("name",t(this).attr("name").replace(i,"$1["+e+"]"))})})},getAlias:function(e,i){var n=t.zlux.url.ajax("manager","getalias",{force_safe:1});t.getJSON(n,{name:e},function(t){i(t)})},removeOptionPanel:function(t){t.find("div.panel input:text").val(t.find("a.trigger").show().text()),t.find("div.panel").removeClass("active")}}),t.zlux[a.prototype.name]=a}(jQuery,window,document),function(t,e,i,n){"use strict";var a=function(e,i){var n=this,o=t(e);o.data(a.prototype.name)||(n.element=t(e),n.options=t.extend({},a.prototype.options,i),this.events={},n.initialize(),n.element.data(a.prototype.name,n))};t.extend(a.prototype,t.zlux.Main.prototype,{name:"fieldsAttributesField",options:{},initialize:function(){var e=this;e.hidden=t(".hidden",e.element).detach(),e.list=t("ul.zlux-field-attributes",e.element),e.element.addClass("zl-bootstrap"),e.element.on("click",".zlux-x-delete",function(){return t(this).closest("li").slideUp(400,function(){t(this).remove(),e.updateIndexes(t(this).closest("ul"))}),!1}).on("click",".zlux-x-add-attr",function(){var i=t(this).siblings("ul"),n=e.hidden.find("li.zlux-x-attr").clone().removeClass("hidden");n.appendTo(i).slideDown(200).effect("highlight",{},1e3).find("input:first").focus(),e.updateIndexes()}).on("click",".zlux-x-add",function(){var i=t(this).siblings("ul"),n=e.hidden.find("li.zlux-x-option").clone().removeClass("hidden");n.appendTo(i).slideDown(200).effect("highlight",{},1e3).find("input:first").focus(),e.updateIndexes()}).on("blur",".zlux-x-name input",function(){var i=t(this).closest("li"),n=i.find(".panel input:text");if(""!==t(this).val()&&""===n.val()){var a="";e.getAlias(t(this).val(),function(t){a=t?t:"42",n.val(a),i.find("a.trigger").text(a)})}}).on("keydown",".panel input:text",function(i){i.stopPropagation(),13===i.which&&e.setOptionValue(t(this).closest("li")),27===i.which&&e.removeOptionPanel(t(this).closest("li"))}).on("click","input.accept",function(){return e.setOptionValue(t(this).closest("li")),!1}).on("click","a.cancel",function(){return e.removeOptionPanel(t(this).closest("li")),!1}).on("click","a.trigger",function(){return t(this).hide().closest("li").find("div.panel").addClass("active").find("input:text").focus(),!1}),t("ul",e.element).each(function(){e.initList(t(this))})},initList:function(e){var i=this;e.sortable({handle:".zlux-x-sort",containment:e.parent().parent(),placeholder:"dragging",axis:"y",opacity:1,revert:75,delay:100,tolerance:"pointer",zIndex:99,start:function(i,n){n.placeholder.height(40),e.sortable("refreshPositions"),e.children("li").find("ul").addClass("hidden"),t(n.item).height("")},stop:function(){i.updateIndexes(),e.children("li").find("ul").removeClass("hidden")}})},setOptionValue:function(t){var e=this,i=t.find("div.panel input:text"),n=i.val();""===n&&(n=t.find("div.zlux-x-name input").val()),this.getAlias(n,function(a){n=a?a:"42",i.val(n),t.find("a.trigger").text(n),e.removeOptionPanel(t)})},updateIndexes:function(){var e=this,i=/(elements\[\w{8}-\w{4}-\w{4}-\w{4}-\w{12}\]\[\S+?\])\[(\d+)\]/g,n=/(elements\[\w{8}-\w{4}-\w{4}-\w{4}-\w{12}\]\[\S+\])\[(\d+)\]/g;e.list.children("li").each(function(e){t("input",t(this)).each(function(){t(this).attr("name")&&t(this).attr("name",t(this).attr("name").replace(i,"$1["+e+"]"))}),t("ul",t(this)).children("li").each(function(e){t("input",t(this)).each(function(){t(this).attr("name")&&t(this).attr("name",t(this).attr("name").replace(n,"$1["+e+"]"))})})})},getAlias:function(e,i){var n=t.zlux.url.ajax("manager","getalias",{force_safe:1});t.getJSON(n,{name:e},function(t){i(t)})},removeOptionPanel:function(t){t.find("div.panel input:text").val(t.find("a.trigger").show().text()),t.find("div.panel").removeClass("active")}}),t.zlux[a.prototype.name]=a}(jQuery,window,document),function(t,e,i,n){"use strict";var a=function(e,i){var n=this,o=t(e);o.data(a.prototype.name)||(n.element=t(e),n.options=t.extend({},a.prototype.options,i),this.events={},n.initialize(),n.element.data(a.prototype.name,n))};t.extend(a.prototype,t.zlux.Main.prototype,{name:"fieldsItemsField",options:{controlName:""},initialize:function(){var e=this;e.aRelated=[],e.list=t("<ul />").addClass("zlux-field-items").appendTo(e.element).sortable({handle:".zlux-x-sort",placeholder:"dragging",axis:"y",opacity:1,delay:100,tolerance:"pointer",containment:"parent",forcePlaceholderSize:!0,scroll:!1,start:function(t,e){e.helper.addClass("ghost")},stop:function(t,e){e.item.removeClass("ghost")}}).on("click",".zlux-x-delete",function(){t(this).closest("li").fadeOut(200,function(){var i={};i.id=t(this).data("id"),e.removeRelation(i)})}),t(".zlux-x-item",e.element).each(function(i,n){var a=t(n).data("info");e.appendRelation(a),t(n).remove()}),e.element.addClass("zl-bootstrap"),e.dialogTrigger=t('<button type="button" class="btn btn-mini"><i class="icon-plus-sign"></i> Add item </button>').appendTo(e.element),t.zlux.assets.load("items").done(function(){e.dialogTrigger.zlux("itemsDialogManager",{position:{of:e.element,my:"left top",at:"right top"},apps:e.options.apps,types:e.options.types}).on("zlux.ObjectSelected",function(i,n,a){"-1"!=t.inArray(a.id,e.aRelated)?e.removeRelation(a):(t(".column-icon i",a.dom).removeClass("icon-file-alt").addClass("icon-check"),e.appendRelation(a))}).on("zlux.TableDrawCallback",function(i,n){t("tbody tr",n.oTable).each(function(i,n){"-1"!=t.inArray(n.getAttribute("data-id"),e.aRelated)&&t(".column-icon i",n).removeClass("icon-file-alt").addClass("icon-check")}),e.manager=n})})},appendRelation:function(e){var i=this;t('<li class="zlux-x-item" data-id="'+e.id+'"><div class="zlux-x-name">'+e.name+'</div><span class="zlux-x-tools"><i class="zlux-x-delete icon-remove-circle" title="Delete"></i><i class="zlux-x-sort icon-move" title="Sort"></i></span><div class="zlux-x-info"><div>'+e.type.name+" / "+e.application.name+'</div></div><input type="hidden" name="'+i.options.controlName+'" value="'+e.id+'"/></li>').appendTo(i.list),i.aRelated.push(e.id)},removeRelation:function(e){var i=this;t('li[data-id="'+e.id+'"]',i.list).remove(),i.aRelated.splice(t.inArray(e.id,i.aRelated),1),i.manager&&t('tbody tr[data-id="'+e.id+'"] .column-icon i',i.manager.oTable).removeClass("icon-check").addClass("icon-file-alt")}}),t.zlux[a.prototype.name]=a}(jQuery,window,document);