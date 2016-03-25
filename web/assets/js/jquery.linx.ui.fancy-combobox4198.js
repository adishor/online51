(function ($) {
            $.widget("ui.combobox", {
                // constructor                
                _create: function () {                    
                    if(this.element.attr('multiple')!=undefined)
                        return false; 
                                                            
                    var input,
                    self = this,
					select = this.element.hide(),
					selected = select.children(":selected"),
					//value = selected.val() ? selected.text() : "",
                    value = selected.text(), /* Linx-IT: Fix for default displaying option Select ... */
					wrapper = this.wrapper = $("<span>")
						.addClass("ui-combobox")
						.insertAfter(select);

                    input = $("<input>").attr('type', 'text')
                    if(select.attr("disabled"))
                        input.attr("disabled", ""); 
					input.appendTo(wrapper)
					.val(value)
					.addClass("ui-state-default ui-combobox-input")
                    .css({'width':'140px'})
                    .attr('selectid', select.attr('id')) /* Linx-IT: Fix for getting the associated select element */    
					.autocomplete({
					    delay: 0,
					    minLength: 0,
					    source: function (request, response) {
					        var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
					        response(select.children("option").map(function () {
					            var text = $(this).text();
					            if ((!request.term || matcher.test(text)))
					                return {
					                    label: text.replace(
											new RegExp(
												"(?![^&;]+;)(?!<[^<>]*)(" +
												$.ui.autocomplete.escapeRegex(request.term) +
												")(?![^<>]*>)(?![^&;]+;)", "gi"
											), "<strong>$1</strong>"),
					                    value: text,
					                    option: this
					                };
					        }));
					    },
					    select: function (event, ui) {

					        var par = $(this).parent().prev();
					        var selectedOption = null;
					        $(this).parent().prev().find("option").each(function (key, optElm) {
					            $(optElm).removeAttr('selected');
					            if ($(optElm).text() == ui.item.value) {
					                selectedOption = optElm;
					            }
					        });
					        if (selectedOption != null){
					            $(selectedOption).attr('selected', 'selected');
                            }
                            
					        $(this).parent().prev().trigger('change');
					        ui.item.option.selected = true;
					        self._trigger("selected", event, {
					            item: ui.item.option
					        });
					    },
					    change: function (event, ui) {                            
					        if (!ui.item) {
					            var matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex($(this).val()) + "$", "i"),
									valid = false;
					            select.children("option").each(function () {
					                if ($(this).text().match(matcher)) {
					                    this.selected = valid = true;
					                    return false;
					                }
					            });
					            if (!valid) {                                    
					                // remove invalid value, as it didn't match anything                                                                                                       
					                $(this).val("");
					                select.val("");
					                input.data("autocomplete").term = "";                                    
                                    if($(this).val() == '')
                                        $(this).parent().prev().trigger('change');
                                    
					                return false;
					            }
					        }
					    }
					})
					.addClass("ui-widget ui-widget-content ui-corner-left");

                    input.data("autocomplete")._renderItem = function (ul, item) {
                        return $("<li></li>")
						.data("item.autocomplete", item)
						.append("<a>" + item.label + "</a>")
						.appendTo(ul);
                    };

                    var button = $("<a>");
                    if(select.attr("disabled"))
                        button.attr("disabled", ""); 
					button.attr("tabIndex", -1)
					.attr("title", "Show All Items")
					.appendTo(wrapper)
					.button({
					    icons: {
					        primary: "ui-icon-triangle-1-s"
					    },
					    text: false
					})
					.removeClass("ui-corner-all")
					.addClass("ui-corner-right ui-combobox-toggle")
					.click(function () {                        
                        if (select.attr('disabled')) {
						    return;
					    }
					    // close if already visible
					    if (input.autocomplete("widget").is(":visible")) {
					        input.autocomplete("close");
					        return;
					    }

					    // work around a bug (likely same cause as #5265)
					    $(this).blur();

					    // pass empty string as value to search for, displaying all results
					    input.autocomplete("search", "");
					    input.focus();
					});
                },

                // events bound via _bind are removed automatically
                destroy: function () {
                    this.wrapper.remove();
                    this.element.show();
                    $.Widget.prototype.destroy.call(this);
                },                

                // _setOption is called for each individual option that is changing
                _setOption: function (key, value) {                    
                    if (key === 'disabled') {
                        this.disableWidget(value);
                    }
                    $.Widget.prototype._setOption.call(this, key, value);
                },
                disableWidget: function (value) {
                    
                    if(value==true){
                        this.element.attr('disabled','disabled');
                        this.wrapper.find('input:first').attr('disabled','disabled');
                        this.wrapper.find('a:first').addClass('ui-combobox-a-disabled');                        
                        this.wrapper.find('a:first').find('span:first').css({'background-image':"url(/Content/Images/ui-icons_d8e7f3_256x240.png)"});
                    }else{
                        this.element.removeAttr('disabled');
                        this.wrapper.find('input:first').removeAttr('disabled');
                        this.wrapper.find('a:first').removeClass('ui-combobox-a-disabled');                        
                        this.wrapper.find('a:first').find('span:first').css({'background-image':"url(/Content/Images/ui-icons_6da8d5_256x240.png)"});
                    }
                },

            });
        })(jQuery);