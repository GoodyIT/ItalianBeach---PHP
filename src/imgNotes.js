/*
 * imgNotes
 * 
 *
 * Copyright (c) 2013 Wayne Mogg
 * Licensed under the MIT license.
 */
;(function($) {
	$.widget("wgm.imgNotes", {
		options: {
			zoom: 1,
			zoomStep: 0.05,
			zoomable: true,
			canEdit: false,
			vAll: "middle",
			hAll: "middle",
			tagWidth: 16,
			tagHeight: 16,
			tagFontSize: 6,
/*
 * Default callback to create a marker indicating a note location
 *	See the examples for more elaborate alternatives.
 */
			onAdd: function() {
				this.options.vAll = "bottom";
				this.options.hAll = "middle";
				return  $(document.createElement('span')).addClass("marker black").html(this.noteCount);
			},
/*
 *	Default callback when the marker is clicked and the widget has canEdit = true
 *	Opens a dialog with a textarea to write a note.
 *	See the examples for a more elaborate alternative that includes a WYSIWYG editor
 */
			onEdit: function(ev, elem) {
				var $elem = $(elem);
				$('#NoteDialog').remove();
				return $('<div id="NoteDialog"></div>').dialog({
					title: "Note Editor",
					resizable: false,
					modal: true,
					height: "300",
					width: "450",
					position: { my: "left bottom", at: "right top", of: elem},
					buttons: {
						"Save": function() {
							var txt = $('textarea', this).val();
//			Put the editied note back into the data area of the element
//			Very important that this step is included in custom callback implementations
							$elem.data("note", txt);
							$(this).dialog("close");
						},
						"Delete": function() {
							$elem.trigger("remove");
							$(this).dialog("close");
						},
						Cancel: function() {
							$(this).dialog("close");
						}
					},
					open: function() {
						$(this).css("overflow", "hidden");
						var textarea = $('<textarea id="txt" style="height:100%; width:100%;">');
						$(this).html(textarea);
//			Get the note text and put it into the textarea for editing
						textarea.val($elem.data("note"));
					}
				});				
			},
/*
 *	Default callback when the marker is clicked and the widget has canEdit = false
 *	Opens a dialog displaying the contents of the marker's note
 *	See examples for alternatives such as using tooltips.
 */
			onShow: function(ev, elem) {
				var $elem = $(elem);
				$('#NoteDialog').remove();
				return $('<div id="NoteDialog"></div>').dialog({
					modal: false,
					resizable: false,
					height: 300,
					width: 250,
					position: { my: "left bottom", at: "right top", of: elem},
					buttons: {
						"Close" : function() {
							$(this).dialog("close");
						}
					},
					open: function() {
//			Get the note text and put it into the textarea for editing
						$(this).html($elem.data("note"));
						$(this).closest(".ui-dialog").find(".ui-dialog-titlebar:first").hide();
						
					},
					close: function() {
						$(this).dialog("destroy");
					}
				});
			},
/*
 *	Default callback when the markers are repainted
 */
			onUpdateMarker: function(elem) {
				var $elem = $(elem);
				var $img = $(this.img);
				var pos = $img.imgViewer("imgToView", $elem.data("relx"), $elem.data("rely"));
				if (pos) {
					_width = this.options.tagWidth * this.options.zoom;
					_height = this.options.tagWidth * this.options.zoom;					
					_fontSize = this.options.tagFontSize * this.options.zoom*1.1;	
					deltaWidth = _width - this.options.tagWidth;
					deltaHeight = _height - this.options.tagWidth;
					
					$elem.data("width", _width);
					$elem.data("height", _height);
					_margin_top = $elem.data("margin-top") - deltaHeight;
					_margin_left = $elem.data("margin-left") - deltaWidth;
//					console.log(_margin_top + ' ' + _margin_left);
					$elem.css({
						left: (pos.x - $elem.data("xOffset")),
						top: (pos.y - $elem.data("yOffset")),
						width: _width + 'px',
						height: _height + 'px',
						'margin-top': _margin_top/2 + 'px',
						'margin-left': _margin_left/2 + 'px',
						'font-size': _fontSize + 'px',
						position: "absolute"
					});
				}
			},
/*
 *	Default callback when the image view is repainted
 */
			onUpdate: function() {
				var self = this;
				
				$.each(this.notes, function() {
				
					self.options.onUpdateMarker.call(self, this);
				});
			}
		},
		
		
		_create: function() {
			var self = this;
			if (!this.element.is("img")) {
				$.error('imgNotes plugin can only be applied to img elements');
			}
//		the note/marker elements
			self.notes = [];
//		the number of notes
			self.noteCount = 0;
//		the original img element
			self.img = self.element[0];
			var $img = $(self.img);
//		attach the imgViewer plugin for zooming and panning with a custon click and update callbacks
			$img.imgViewer({
							onClick: function(ev, imgv) {
								if (self.options.canEdit) {
									ev.preventDefault();
									var rpos = imgv.cursorToImg(ev.pageX, ev.pageY);
									if (rpos) {
										var elem = self.addNote(rpos.x, rpos.y);
										self._trigger("onEdit", ev, elem);
									}
								}
							},
							onUpdate: function(ev, imgv) {
								self.options.zoom = imgv.options.zoom;
								self.options.onUpdate.call(self);
							},
							zoom: self.options.zoom,
							zoomStep: self.options.zoomStep,
							zoomable: self.options.zoomable
			});
			$img.imgViewer("update");
		},
/*
 *	Remove the plugin
 */  
		destroy: function() {
			this.clear();
			$(this.img).imgViewer("destroy");
			$.Widget.prototype.destroy.call(this);
		},
		
		_setOption: function(key, value) {
			switch(key) {
				case 'vAll':
					switch(value) {
						case 'top': break;
						case 'bottom': break;
						default: value = 'middle';
					}
					break;
				case 'hAll':
					switch(value) {
						case 'left': break;
						case 'right': break;
						default: value = 'middle';
					}
					break;
			}
			var version = $.ui.version.split('.');
			if (version[0] > 1 || version[1] > 8) {
				this._super(key, value);
			} else {
				$.Widget.prototype._setOption.apply(this, arguments);
			}
			switch(key) {
				case 'zoom':
					$(this.img).imgViewer("option", "zoom", value);
					break;
				case 'zoomStep':
					$(this.img).imgViewer("option", "zoomStep", value);
					break;
				case 'zoomable':
					$(this.img).imgViewer("option", "zoomable", value);
					break;
			}
		},
/*
 *	Pan the view to be centred at the given relative image location
 */
		panTo: function(relx, rely) {
			return $(this.img).imgViewer("panTo", relx, rely);
		},
			
/*
 *	Add a note
 */
		addNote: function(relx, rely, type, text) {
			var self = this;
			this.noteCount++;
			var elem = this.options.onAdd.call(this);
			var $elem = $(elem);
			$(this.img).imgViewer("addElem",elem);
			$elem.data("relx", relx).data("rely", rely).data("type", type).data("note", text).data("checked", "unchecked").data("margin-left", this.options.tagWidth/2).data("margin-top", this.options.tagHeight/2);
			$elem.html(text);

			$tooltip = '<abbr title="' + text + '" rel="tooltip" style="white-space: nowrap;">UX</abbr>';
			$elem.attr("title", text);

			if (type === "booked" || type === "booking"){
				// $elem.css('background-color', "red");
				$elem.removeClass('blue').addClass('red');
			}
			else {
				// $elem.css('background-color', "green");
				$elem.removeClass('red').addClass('blue');
			}
			
			if (seat != ''){
				// $elem.css('background-color', "yellow");
    //                             $elem.css('color', 'black');
                                $elem.data('checked', 'checked');
                            }
			switch (this.options.vAll) {
				case "top": $elem.data("yOffset", 0); break;
				case "bottom": $elem.data("yOffset", $elem.height()); break;
				default: $elem.data("yOffset", Math.round($elem.height()/2 + dy));
			}
			switch (this.options.hAll) {
				case "left": $elem.data("xOffset", 0); break;
				case "right": $elem.data("xOffset", $elem.width()); break;
				default: $elem.data("xOffset", Math.round($elem.width()/2 + dx));
			}
			
			$elem.click(function(ev) {
				$_message = ["Sorry, This sunshade was already booked by others! ", "This sunshade is available now."];
				if (lang == 'it')
					$_message = ["Mi dispiace, questo ombrellone è stato già prenotato da altri!", "Questo ombrellone è ora disponibile."];
				$_message_sub = "";
				$_notifyType = ["danger", "success"];
				$_markerColor = 'blue';
				$_markerColorOrigin = '';
				if ($elem.hasClass('blue')) {
					$elem.removeClass('blue');
					$_markerColorOrigin = 'blue';
				}
				if ($elem.hasClass('yellow')) {
					$elem.removeClass('yellow');
					$_markerColorOrigin = 'yellow';
				}
				if ($elem.hasClass('red')) {
					$elem.removeClass('red');
					$_markerColorOrigin = 'red';
				}

				$_textColor = "#fff";
                                
                if (type === "booked" || type === "booking") 
				{
					$_message = $_message[0];
					$_notifyType = $_notifyType[0];
					$_markerColor = "red";
				} else {
					$_message = $_message[1];
					$_notifyType = $_notifyType[1];
								   $_message_sub_1 = " was inserted into your booking list successfully <br><br> <div style=\"text-align:center\"> <a  align=\"center\" href=\"javascript:bookingChart();\" target=\"_self\" style=\"color:white;\"><i class=\"fa fa-shopping-cart\" aria-hidden=\"true\" style=\"font-size:20px;\"></i>&nbsp&nbsp<strong>Booking Chart</strong></a></div>";
					if (lang == 'it') {
						$_message_sub_1 = " è stato inserito nella vostro carrello prenotazione con successo <br><br><div style=\"text-align:center\"> <a href=\"javascript:bookingChart();\" target=\"_self\" style=\"color:white;\"><i class=\"fa fa-shopping-cart\" aria-hidden=\"true\" style=\"font-size:20px\"></i>&nbsp&nbsp<strong>Carrello Prenotazione</strong></a></div>"
					}
					$_message_sub =  "<br>" + text + $_message_sub_1;
					$_markerColor = "yellow";
					
					$_checked = $elem.data("checked");
					if($_checked === "checked" || seat == text ){
						$_message_1 = "Sunshade booking for ";
						$_message_2 = " was cancelled.";
						if (lang == 'it') {
							$_message_1 = "La Prenotazione dell'Ombrellone per ";
							$_message_2 = " è stata annullata.";
						}
						$_message = $_message_1 + text + $_message_2;
						$_message_sub = "";
						$elem.data("checked", "unchecked");
						$_markerColor = "blue";

						seat = '';

						document.cookie = "seat=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
					} else {
						if (seat != '' && seat != text) {
							$_message = "Sorry, you have already chose one sunshade. It's time to make a reservation";
							if (lang == 'it') {
								$_message = "Siamo spiacenti, hai è già scelto un ombrellone. <br> E' il momento di fare una prenotazione";
							}
							$_message_sub = "";
							$_markerColor = $_markerColorOrigin;
						}
						else{
							$elem.data("checked", "checked");
							$_markerColor = "yellow";
							$_textColor = "black";

							seat = text;

							var date = new Date();
							date.setTime(date.getTime()+(1*24*60*60*1000));
							var expires = "; expires="+date.toGMTString();
							document.cookie = "seat"+"="+text+expires+"; path=/";
						}
					}
				}

			//	$elem.css('background-color', $_markerColor);
				$elem.addClass($_markerColor);
				$elem.css('color', $_textColor);

				ev.preventDefault();
				$.notifyClose('all');
				$.notifyDefaults({
					placement: {
						from:'top',
						align:'center'
					},
					animate: {
						enter: 'animated bounceInDown',
						exit: 'animated bounceOutUp'
					},
					offset: {
						x: 100,
						y: parseInt($elem.position().top)
					}
				});
				$.notify({
					message: "<strong>" + $_message + "</strong>" + $_message_sub
				},{
					type: $_notifyType,
					allow_dismiss: true,	
					delay: 1000000000,
				});
				
				/* if (self.options.canEdit) {
					self._trigger("onEdit", ev, elem);
				} else {
					self._trigger("onShow", ev, elem);
				} */
			});
			$elem.on("remove", function() {
				self._delete(elem);
			});
			this.notes.push(elem);
			$(this.img).imgViewer("update");
			return elem;
		},
/*
 *	Number of notes
 */
		count: function() {
			return this.noteCount;
		},
/*
 *	Delete a note
 */
		_delete: function(elem) {
			this.notes = this.notes.filter(function(v) { return v!== elem; });
			$(elem).off();
			$(elem).remove();
			$(this.img).imgViewer("update");
		},
/*
 *	Clear all notes
 */
		clear: function() {
			var self = this;
			var total = self.notes.length;
			for ( var i = 0; i < total; i++ ){
				var $this = self.notes[i];
				$this.off();
				$this.remove();
			}
			self.notes=[];
			self.noteCount = 0;
		},
/*
 *	Add notes from a javascript array
 */
		import: function(notes) {
	//		console.log(notes);
			var self = this;
			$.each(notes, function() {
				self.addNote(this.x, this.y, this.bookstate, this.seat);
			});
			$(this.img).imgViewer("update");
		},
/*
 *	Export notes to an array
 */
		export: function() {
			var notes = [];
			$.each(this.notes, function() {
				var $elem = $(this);
				notes.push({
						x: $elem.data("relx"),
						y: $elem.data("rely"),
						note: $elem.data("note")
				});
			});
			return notes;
		}
	});
})(jQuery);
