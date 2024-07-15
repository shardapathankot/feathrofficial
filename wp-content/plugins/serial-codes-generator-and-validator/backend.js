function sngmbhSerialcodesValidator(_myAjaxVar, doNotInit){
	let myAjax = _myAjaxVar;
	let self = this;
	let PREMIUM = null;
	var $ = jQuery;
	var PARAS = basics_ermittelURLParameter();
	var DATA = {
        /*action: '',*/
        nonce: myAjax.nonce
    };

	var FATAL_ERROR = false;
    var DIV = null;
    var LAYOUT = null;
    var DATA_LISTS = null;
    var OPTIONS = {
		list:[], mapKeys:{},
		versions:{mapKeys:{}},
		meta_tags_keys:{list:[], mapKeys:{}},
		infos:{}
	};

	var STATE = null;

    if (_myAjaxVar._doNotInit) doNotInit = true;

	function destroy_tags(t) {
		if (t != null) {
			t = t.replace("<", "").replace(">","");
		}
		return t;
	}

	function _makePost(action, myData, cbf, ecbf) {
		if (FATAL_ERROR) return;
		let _data = Object.assign({}, DATA);
		_data.action = myAjax._action;
		_data.a_sngmbh = action;
		for(var key in myData) _data['data['+key+']'] = myData[key];
        $.post( myAjax.url, _data, function( response ) {
            if (!response.success) {
            	if (ecbf) ecbf(response);
            	else LAYOUT.renderFatalError(response.data);
            } else {
            	cbf && cbf(response.data);
            }
        });
	}

	function _makeGet(action, myData, cbf, ecbf) {
		if (FATAL_ERROR) return;
		let _data = Object.assign({}, DATA);
		_data.action = myAjax._action;
		_data.a_sngmbh = action;
		_data.t = new Date().getTime();
		for(var key in myData) _data['data['+key+']'] = myData[key];
        $.get( myAjax.url, _data, function( response ) {
            if (!response.success) {
				if (ecbf) ecbf(response);
            	else LAYOUT.renderFatalError(response.data);
            } else {
            	cbf && cbf(response.data);
            }
        });
	}

	function time() {
		return new Date().getTime();
	}

	function speakOutLoud(v, display) {
		if ('speechSynthesis' in window) {
			var t = typeof v === 'object' ? 'Value is an object.' : v;
			if (t.trim() == "") t = 'Value is empty';
			var msg = new SpeechSynthesisUtterance(t);
			msg.lang = "en-US";
			window.speechSynthesis.speak(msg);
			if (display) console.log("Speak:", v);
		} else {
			console.log(v);
		}
	}
	function _setOptions(optionData) {
		OPTIONS.list = optionData.options;
		for (let a=0;a<OPTIONS.list.length;a++) {
			let item = OPTIONS.list[a];
			OPTIONS.mapKeys[item.key] = item;
			OPTIONS.mapKeys[item.key].getValue = function(key) {
				return function() {return _getOptions_getValByKey(key);};
			}(item.key);
		}
		if (optionData.versions) {
			OPTIONS.versions.mapKeys = optionData.versions;
		}
		if (optionData.meta_tags_keys) {
			OPTIONS.meta_tags_keys.list = optionData.meta_tags_keys;
			OPTIONS.meta_tags_keys.mapKeys = {};
			for (let a=0;a<OPTIONS.meta_tags_keys.list.length;a++) {
				let item = OPTIONS.meta_tags_keys.list[a];
				OPTIONS.meta_tags_keys.mapKeys[item.key] = item;
				OPTIONS.meta_tags_keys.mapKeys[item.key].getValue = function(key) {
					return function() {return _getOptions_Meta_getValByKey(key);};
				}(item.key);
			}
		}
		if (optionData.infos) {
			OPTIONS.infos = optionData.infos;
		}

		if (isPremium()) {
			let serial = _getOptions_getValByKey('serial');
			if (serial == '') {
				if (STATE != "options") {
					let errortext = "You are using the premium version. Many thanks, please enter your serial key within the options";
					let i = confirm(errortext);
					if (i) {
						_displayOptionsArea();
					}
				}
			}
			if (serial != "" && typeof OPTIONS.infos.premium_expiration !== "undefined") {
				let expiration = OPTIONS.infos.premium_expiration;
				if (expiration.last_run != 0 && expiration.timestamp >= 0) {
					let expirationDate = new Date(expiration.timestamp * 1000);
					let toCheck = new Date();
					toCheck.setDate(toCheck.getDate() + 21);
					let today = new Date();
					if (expirationDate <= today || toCheck >= expirationDate) {
						let msg = typeof expiration.message !== "undefined" && expiration.message != "" ? '<br>'+expiration.message : '';
						let info_box = $('<div style="background-color:red;color:white;padding:10px;">').html("Your premium serial expires soon, at the "+expiration.expiration_date+ ' '+expiration.timezone+'<br>It will work, but no updates are possible for the premium plugin after the expiration date.<br>'+msg+'You can <a target="_blank" style="color:white;font-weight:bold;" href="https://vollstart.de/shop/support-update-plugin-serial-codes-generator-and-validator/">renew your license here</a>.');
						$('body').find('div[data-id="plugin_info_area"').html(info_box);
					}
				}
			}
		}
	}

	function _getOptions_getByKey(key) {
		if (OPTIONS.mapKeys[key]) return OPTIONS.mapKeys[key];
		return null;
	}
	function _getOptions_Meta_getByKey(key) {
		if (OPTIONS.meta_tags_keys.mapKeys[key]) return OPTIONS.meta_tags_keys.mapKeys[key];
		return null;
	}
	function _getOptions_Versions_getByKey(key) {
		if (OPTIONS.versions.mapKeys[key]) return OPTIONS.versions.mapKeys[key];
		return null;
	}
	function _getOptions_Infos_getByKey(key) {
		if (OPTIONS.infos[key]) return OPTIONS.infos[key];
		return null;
	}
	function _getOptions_isActivatedByKey(key) {
		let po = _getOptions_getByKey(key);
		if (po == null) return false;
		return po.value == 1;
	}
	function _getOptions_Versions_isActivatedByKey(key) {
		let po = _getOptions_Versions_getByKey(key);
		if (po == null) return false;
		return po == 1;
	}
	function _getOptions_getLabelByKey(key) {
		let po = _getOptions_getByKey(key);
		if (po == null) return "";
		return po.label;
	}
	function _getOptions_getDescByKey(key) {
		let po = _getOptions_getByKey(key);
		if (po == null) return "";
		return po.desc;
	}
	function _getOptions_Meta_getLabelByKey(key) {
		let po = _getOptions_Meta_getByKey(key);
		if (po == null) return "";
		return po.label;
	}
	function _getOptions_getValByKey(key) {
		let po = _getOptions_getByKey(key);
		if (po == null) return "";
		return po.value == "" ? po['default'] : po.value;
	}
	function _getOptions_Versions_getValByKey(key) {
		let po = _getOptions_Versions_getByKey(key);
		if (po == null) return "";
		return po;
	}

	function basics_ermittelURLParameter() {
		var parawerte = {};
	    var teile;
	    if (window.location.search !== "") {
	        teile = window.location.search.substring(1).split("&");
	        for (var a=0;a<teile.length;a++)
	        {
	            var pos = teile[a].indexOf("=");
	            if (pos < 0) {
	                parawerte[teile[a]] = true;
	            } else {
	                var key = teile[a].substr(0,pos);
	                parawerte[key] = decodeURIComponent(teile[a].substr(pos+1));
	            }
	        }
	    }
	    return parawerte;
	}

	function intval(v) {
		let retv = parseInt(v,10);
		if (isNaN(retv)) retv = 0;
		return retv;
	}

	function _requestURL(action, myData) {
		let paras = '?action='+myAjax._action+'&a_sngmbh='+action;
		for(let key in myData) paras += '&data['+key+']='+encodeURIComponent(myData[key]);
		for(let key in DATA) paras += '&'+key+'='+encodeURIComponent(DATA[key]);
		return myAjax.url + paras;
	}

	function getDataLists(cbf) {
		if (DATA_LISTS !== null) cbf && cbf();
		_makeGet('getLists', {}, function(data) {
			DATA_LISTS = data;
			cbf && cbf(DATA_LISTS);
		});
	}

	function getCodeObjectMeta(codeObj) {
		if (!codeObj.metaObj) codeObj.metaObj = JSON.parse(codeObj.meta);
		return codeObj.metaObj;
	}

	function updateCodeObject(codeObj, newCodeObj) {
		for(var prop in newCodeObj) {
			codeObj[prop] = newCodeObj[prop];
		}
		codeObj.metaObj = null;
	}

	function closeDialog(dlg) {
		$(dlg).dialog( "close" );
		$(dlg).html('');
		$(dlg).dialog("destroy").remove();
		$(dlg).empty();
		$(dlg).remove();
		$('.ui-dialog-content').dialog('destroy');
	}

	function _displaySupportInfoArea(cbf) {
		STATE = 'support';
		DIV.html(_getSpinnerHTML());
		_makeGet('getOptions', {}, reply=>{
			let newline = '<br>';
			let div_stats = $('<div/>').html(_getSpinnerHTML());

			_makeGet('getSupportInfos', {}, infos=>{
				div_stats.html("");
				div_stats.append('<b>Codes:</b>: '+infos.amount.codes+newline);
				div_stats.append('<b>Lists:</b>: '+infos.amount.lists+newline);
			});

			_setOptions(reply);
			let data = reply.options; // options values
			let versions = reply.versions;

			DIV.html($('<div/>').append($('<button/>').addClass("button-primary").html("Back").css("margin-bottom", "10px").on("click", function(){
				LAYOUT.renderAdminPageLayout();
			})));

			// zeige support email
			DIV.append('<h3>Support Email</h3><b>support@vollstart.de</b>');
			DIV.append('<h3>Support Context Information</h3><p>Please copy the following information, so that we can support you better and faster. Remove any critical information if needed.</p>');
			DIV.append('<b>Wordpress Version:</b> '+versions.wp+newline);
			DIV.append('<b>MySQL/Mariadb Version:</b> '+versions.mysql+newline);
			DIV.append('<b>PHP Version:</b> '+versions.php+newline);
			DIV.append('<b>Basic Plugin Version:</b> '+versions.basic+newline);
			DIV.append('<b>Basic DB Version:</b> '+versions.db+newline);
			if (versions.premium != "") {
				DIV.append('<b>Premium Plugin Version:</b> '+versions.premium+newline);
				DIV.append('<b>Premium DB Version:</b> '+versions.premium_db+newline);
			}
			DIV.append('<h4 style="margin-bottom:0;">Stats</h4>');
			DIV.append(div_stats);
			DIV.append('<h4 style="margin-bottom:0;">URLs</h4>');
			DIV.append('<b>Mulitsite: </b> '+reply.infos.site.is_multisite+newline);
			DIV.append('<b>Home: </b> '+reply.infos.site.home+newline);
			DIV.append('<b>Network home: </b> '+reply.infos.site.network_home+newline);
			DIV.append('<b>Site URL: </b> '+reply.infos.site.site_url+newline);

			/*
			DIV.append('<h4 style="margin-bottom:0;">Ticket URLs DEPRICATED!!</h4>');
			DIV.append('<b>Ticket Detail URL: </b> '+reply.infos.ticket.ticket_base_url+newline);
			DIV.append('<b>Ticket Detail Path: </b> '+reply.infos.ticket.ticket_detail_path+newline);
			DIV.append('<b>Ticket Scanner Path: </b> '+reply.infos.ticket.ticket_scanner_path+newline);
			*/

			DIV.append('<h4 style="margin-bottom:0;">Options</h4>');
			// liste alle optionen mit wert auf
			data.forEach(v=>{
				if (v.type != 'heading' && v.key != "serial") {
					if (v.additional && v.additional.doNotRender && v.additional.doNotRender === 1) {}
					else {
						let value = v.value;
						let def = '';
						if (value == '') {
							def = ' (DEFAULT used)';
							value = v.default;
						}
						text = document.createTextNode(value);
						DIV.append(`<b>${v.key}${def}:</b> `).append(text).append(`${newline}`);
					}
				}
			});

			// helper buttons
			$('<button/>').css("margin-top", "30px").addClass("sngmbh_btn-delete").html("Repair tables").appendTo(DIV).on("click", ()=>{
	    		LAYOUT.renderYesNo('Repair database tables?', 'Do you realy want to try to repair your database table definitions for serial code validator? It should be safe, but only needed in very rare cases. You might see errors messages during the page reload - that is normal. Why not asking support, if you should do it? ;)', dlg=>{
					dlg.html(_getSpinnerHTML());
					dlg.dialog({
						title:"Repaired", modal:true, dialogClass: "no-close",
						close: function(event, ui){ abort=true; },
						buttons: [
							{
								text: "Ok",
								click: function() {
									$( this ).dialog( "close" );
									$( this ).html('');
								}
							}
						]
					});
					_makePost('repairTables', {}, result=>{
						speakOutLoud(result, true);
						dlg.html(result);
					});
	    		});
			});
			cbf && cbf();
		});
	}

	function _displayOptionsArea(cbf) {
		STATE = 'options';
		DIV.html(_getSpinnerHTML());
		_makeGet('getOptions', {}, reply=>{
			_setOptions(reply);
			let data = reply.options; // options values
			let meta_tags_keys = reply.meta_tags_keys;

			DIV.html($('<div/>').append($('<button/>').addClass("button-primary").html("Back").css("margin-bottom", "10px").on("click", function(){
				LAYOUT.renderAdminPageLayout();
			})));

			let div_options = $('<div/>');
			let div_infos = $('<div style="padding-top: 40px;"/>');
			DIV.append(div_options);
			DIV.append(div_infos);

			div_infos.append('<a name="replacementtags"></a><h3>Replacement Tags</h3>').append('<p>You can use these replacement tags in your text messages and URLs for the meta code values</p>');
			meta_tags_keys.forEach(v=>{
				let t = '<p><b>{'+v.key+'}</b>: '+v.label+'</p>';
				div_infos.append(t);
			});

			div_options.append('<h3>Options</h3>');
			// render die input felder
			function __getOptionByKey(key) {
				for(let a=0;a<data.length;a++) {
					if (key == data[a].key) return data[a];
				}
				return null;
			}
			data.forEach(v=>{
				if (typeof v.additional !== "undefined" && v.additional.doNotRender) return;
				if (v.type === "heading") {
					div_options.append('<hr>').append('<h3'+(v.desc !== "" ? ' style="margin-bottom:0;"' : '')+'>'+v.label+'</h3>').append(v.desc !== "" ? '<div style="margin-bottom:15px;"><i>'+v.desc+'</i></div>':'');
				} else {
					let elem_div = $('<div/>').css({"margin-bottom": "15px","margin-right": "15px"});
					let elem_input = $('<input type="'+v.type+'">');
					if (typeof v.additional !== "undefined" && typeof v.additional.disabled !== "undefined") {
						elem_input.attr("disabled", true);
					}

					let cbf = null;
					let pcbf = null;
					let value = (""+v.value) !== "" ? (""+v.value).trim() : ""+v.default;

					v.label = v.label + ' <span style="color:grey;">{OPTIONS.'+v.key+'}</span>';

					switch (v.type) {
						case "checkbox":
							v.value = intval(v.value);
							elem_input.prop("checked",v.value === 1 ? true : false);
							elem_input.on("change", function(){
								_makePost('changeOption', {'key':v.key, 'value':elem_input[0].checked ? 1:0});
							});
							elem_div.html(elem_input).append(v.label).append(v.desc !== "" ? '<br><i>'+v.desc+'</i>':'');
							break;
						case "number":
							if (typeof v.additional.min !== "undefined") elem_input.attr("min", v.additional.min);
							break;
						case "dropdown":
							elem_input = $('<select>');
							if (v.additional.multiple) {
								elem_input.prop("multiple", true);
							}
							v.additional.values.forEach(_v=>{
								$('<option>').attr("value", _v.value).html(_v.label).appendTo(elem_input);
							});
							if (v.additional.multiple) {
								if (v.value.length == 0) {
									value = v.default;
								} else {
									value = v.value;
								}
							} else {
								if (value == "") value = 1;
							}
							elem_input.val(value);
							break;
						case "media":
							let image_info = $('<div>');
							let image = $('<image style="display:none;">');
							let image_btn_del = $('<button class="sngmbh_btn sngmbh_btn-delete" style="display:none;">').html(_x('Remove image', 'label', 'serial-codes-generator-and-validator'));
							image_btn_del.on('click', ()=>{
								LAYOUT.renderYesNo(_x('Remove image', 'title', 'serial-codes-generator-and-validator'), __('Do you really want to remove the image information from this option?', 'serial-codes-generator-and-validator'), ()=>{
									elem_input.val("");
									elem_input.trigger("change");
									_renderMedia(0, v, image_info, image, image_btn_del);
								});
							});
							if (v.additional.max) {
								if (v.additional.max.width) {
									image.css("max-width", v.additional.max.width+'px');
								}
								if (v.additional.max.height) {
									image.css("max-height", v.additional.max.height+'px');
								}
							}
							elem_input.attr("type", "hidden");
							let image_btn_add = $('<button style="display:block;" />').addClass("button-primary")
										.html(v.additional.button)
										.on("click", ()=>{
											_openMediaChooser(elem_input);
										});
							$('<div/>').css({"margin-bottom": "15px","margin-right": "15px"})
								.html(v.label+'<br>')
								.append(image_btn_add)
								.append(v.desc !== "" ? '<i>'+v.desc+'</i>':'')
								.append(elem_input)
								.append(image_info)
								.append(image)
								.append(image_btn_del)
								.appendTo(elem_div);
							_renderMedia(value, v, image_info, image, image_btn_del);
							pcbf = function() {
								image_info.html(_getSpinnerHTML());
								image.css('display', 'none');
							}
							cbf = function () {
								let value = elem_input.val();
								_renderMedia(value, v, image_info, image, image_btn_del);
							}
							break;
					}

					if (v.type != "checkbox") {
						if (v.type != "media") {
							elem_div.html(v.label+'<br>').append(elem_input);
							elem_div.append(v.desc !== "" ? '<br><i>'+v.desc+'</i>':'');
						}
						if (v.type != "number") {
							elem_input.css({"width":"80%"});
						}
						if (v.type != "dropdown") {
							elem_input.attr("value",value);
						}
						elem_input.on("change", ()=>{
							let value = elem_input.val();
							_makePost('changeOption', {'key':v.key, 'value':value}, cbf, null, pcbf);
						});
					}
					if (v.key == "wcassignmentUseGlobalSerialFormatter") {
						let option = __getOptionByKey('wcassignmentUseGlobalSerialFormatter_values');
						let formatterValues = null;
						if (option.value != "") {
							try {
								formatterValues = JSON.parse(option.value);
							} catch (e) {
								//console.log(e);
							}
						}
						let extra_div = $('<div>').appendTo(elem_div).css("margin-top", "10px").css("margin-left", "50px").css("padding", "10px").css("border", "1px solid black");
						// render here den formatter
						let serialCodeFormatter = _form_fields_serial_format(extra_div);
						serialCodeFormatter.setNoNumberOptions();
						serialCodeFormatter.setFormatterValues(formatterValues);
						serialCodeFormatter.setCallbackHandle(_formatterValues=>{
							// speicher formatterValues
							_makePost('changeOption', {'key':'wcassignmentUseGlobalSerialFormatter_values', 'value':JSON.stringify(_formatterValues)});
						});
						serialCodeFormatter.render();
					}

					elem_div.appendTo(div_options);
				}
			});
			cbf && cbf();
		});
	}

	function _displaySettingAreaButton() {
		let btn_grp = $('<div/>').addClass("btn-group");
		$('<button/>').addClass("button-primary").html("Support Info")
			.on("click", ()=>{
				_displaySupportInfoArea();
			})
			.appendTo(btn_grp);
		/*
		if (_getOptions_Versions_isActivatedByKey('is_wc_available')) {
			$('<button/>').addClass("button-primary").html("Ticket Scanner DEPRICATED")
			.on("click", ()=>{
				let url = _getOptions_Infos_getByKey('ticket').ticket_scanner_path;
				window.open(url, 'ticketscanner');
			})
			.appendTo(btn_grp);
		}
		*/
		$('<button/>').addClass("button-primary").html("Options")
			.on("click", ()=>{
				_displayOptionsArea();
			})
			.appendTo(btn_grp);
		if (isPremium()) {
			btn_grp = PREMIUM.displaySettingAreaButton(btn_grp);
		}
		return btn_grp;
	}

	function _form_fields_serial_format(appendToDiv) {
		let input_prefix_codes;
		let input_type_codes;
		let input_amount_letters;
		let input_letter_excl;
		let input_letter_style;
		let input_include_numbers;
		let input_serial_delimiter;
		let input_serial_delimiter_space;
		let input_number_start;
		let input_number_offset;

		let noNumbersOptions = false;
		let cbk = null;
		let formatterValues;

		function _setNoNumberOptions() {
			noNumbersOptions = true;
		}
		function _setCallbackHandle(_cbk) {
			cbk = _cbk;
		}
		function _callCallbackHandle() {
			cbk && cbk(_getFormatterValues());
		}
		function _setFormatterValues(values) {
			formatterValues = values;
		}

		function __render() {
			$('<br>').appendTo(appendToDiv);
			// prefix
			let div_prefix_codes = _createDivInput("Enter a prefix (optional)").appendTo(appendToDiv);
				input_prefix_codes = $('<input type="text">').appendTo(div_prefix_codes);
				$('<div>').html('You can use date placeholder to have the prefix filled with the date of the confirmed purchase.<br>You can use: {Y} = year, {m} = month, {d} = day, {H} = hour, {i} = minutes, {s} = seconds, {TIMESTAMP} = unix timestamp.').appendTo(div_prefix_codes);
				if (formatterValues && formatterValues['input_prefix_codes'] != null) input_prefix_codes.val(formatterValues['input_prefix_codes']);
				input_prefix_codes.on("change", ()=>{
					_callCallbackHandle();
				});
			// type numbers/serials
			let div_type_codes = _createDivInput("Choose type of codes").appendTo(appendToDiv);
			input_type_codes = $('<select><option value="1" selected>Serials</option><option value="2">Numbers</option></select>').appendTo(div_type_codes);
			if (formatterValues && formatterValues['input_type_codes'] != null) input_type_codes.val(formatterValues['input_type_codes']);

			if (noNumbersOptions) {
				input_type_codes.prop("disabled", true);
			}
			input_type_codes.on("change", function() {
				if (input_type_codes.val() === "2") {
					div_serials && div_serials.find("input").prop("disabled", true);
					div_serials && div_serials.find("select").prop("disabled", true);
					div_numbers && div_numbers.find("input").prop("disabled", false);
					div_numbers && div_numbers.find("select").prop("disabled", false);
				} else {
					div_serials && div_serials.find("input").prop("disabled", false);
					div_serials && div_serials.find("select").prop("disabled", false);
					div_numbers && div_numbers.find("input").prop("disabled", true);
					div_numbers && div_numbers.find("select").prop("disabled", true);
				}
				_callCallbackHandle();
			});
			// serials options
			let div_serials = $('<div>').html('<h4>Serials options</h4>').appendTo(appendToDiv);
				// anzahl letters
				let div_amount_letters = _createDivInput("Amount of letter needed").appendTo(div_serials);
				input_amount_letters = $('<input type="number" required value="9" min="1" max="30">').appendTo(div_amount_letters);
				if (formatterValues && formatterValues['input_amount_letters'] != null) input_amount_letters.val(formatterValues['input_amount_letters']);
				input_amount_letters.on("change", function(){
					input_serial_delimiter.trigger("change");
					_callCallbackHandle();
				});
				// select letter exclusion
				let div_letter_excl = _createDivInput("Letter exclusion").appendTo(div_serials);
				input_letter_excl = $('<select><option value="1">None</option><option value="2" selected>i,l,o,p,q</option></select>').appendTo(div_letter_excl);
				if (formatterValues && formatterValues['input_letter_excl'] != null) input_letter_excl.val(formatterValues['input_letter_excl']);
				input_letter_excl.on("change", ()=>{
					_callCallbackHandle();
				});
				// radio button text gross/klein/both/none
				let div_letter_style = _createDivInput("Letter style").appendTo(div_serials);
				input_letter_style = $('<select><option value="1" selected>Uppercase</option><option value="2">Lowercase</option><option value="3">Both</option></select>').appendTo(div_letter_style);
				if (formatterValues && formatterValues['input_letter_style'] != null) input_letter_style.val(formatterValues['input_letter_style']);
				input_letter_style.on("change", ()=>{
					_callCallbackHandle();
				});
				// radio button numbers/none
				let div_include_numbers = _createDivInput("Numbers needed?").appendTo(div_serials);
				input_include_numbers = $('<select><option value="1" selected>No</option><option value="2">Yes</option><option value="3">Only numbers</option></select>').appendTo(div_include_numbers);
				if (formatterValues && formatterValues['input_include_numbers'] != null) input_include_numbers.val(formatterValues['input_include_numbers']);
				input_include_numbers.on("change", ()=>{
					_callCallbackHandle();
				});
				// select delimiter none/-/./space
				let div_serial_delimiter = _createDivInput("Delimiter?").appendTo(div_serials);
				input_serial_delimiter = $('<select><option value="1" selected>None</option><option value="2">-</option><option value="4">:</option><option value="3">Space</option></select>').appendTo(div_serial_delimiter);
				if (formatterValues && formatterValues['input_serial_delimiter'] != null) input_serial_delimiter.val(formatterValues['input_serial_delimiter']);
				function __refreshDelimiterSpace() {
					input_serial_delimiter_space.html("");
					if (input_serial_delimiter.val() !== "1") {
						let anzahl = parseInt(input_amount_letters.val(),10);
						if (anzahl > 0) {
							for(let a=1;a<anzahl;a++) input_serial_delimiter_space.append($('<option'+(anzahl > 2 && a === 3 ? " selected": "")+'>').attr("value",a).html(a));
						}
					}
				}
				input_serial_delimiter.on("change", function(){
					__refreshDelimiterSpace();
					_callCallbackHandle();
				});
				// choose delimiter space
				let div_serial_delimiter_space = _createDivInput("After how many letters?").appendTo(div_serials);
				input_serial_delimiter_space = $('<select></select>').appendTo(div_serial_delimiter_space);
				if (formatterValues && formatterValues['input_serial_delimiter'] != null) {
					// setze Werte erstmal ein
					__refreshDelimiterSpace();
				}
				if (formatterValues && formatterValues['input_serial_delimiter_space'] != null) input_serial_delimiter_space.val(formatterValues['input_serial_delimiter_space']);
				input_serial_delimiter_space.on("change", ()=>{
					_callCallbackHandle();
				});
			// numbers options
			let div_numbers = $('<div>').html('<h4>Numbers options</h4>').appendTo(appendToDiv);
				if (noNumbersOptions) div_numbers.css("display","none");
				// number start
				let div_number_start = _createDivInput("Start number").appendTo(div_numbers);
				input_number_start = $('<input type="number" disabled required value="10000" min="1">').appendTo(div_number_start);
				if (formatterValues && formatterValues['input_number_start'] != null) input_number_start.val(formatterValues['input_number_start']);
				input_number_start.on("change", ()=>{
					_callCallbackHandle();
				});
				// number offset
				let div_number_offset = _createDivInput("Offset for each number").appendTo(div_numbers);
				input_number_offset = $('<input type="number" disabled required value="1" min="1">').appendTo(div_number_offset);
				if (formatterValues && formatterValues['input_number_offset'] != null) input_number_offset.val(formatterValues['input_number_offset']);
				input_number_offset.on("change", ()=>{
					_callCallbackHandle();
				});
		}

		function __generateCode(length, cases, withnumbers, exclusion) {
			let charset = 'abcdefghijklmnopqrstuvwxyz';
			if (cases === 1) charset = charset.toUpperCase();
			if (cases === 3) charset += charset.toUpperCase();
		    if (withnumbers === 2) charset += '0123456789';
		    if (withnumbers === 3) charset = '0123456789';
		    if (typeof exclusion !== "undefined") {
		    	exclusion.forEach(function(v){
		    		let regex = new RegExp(v, 'gi');
		    		charset = charset.replace(regex, "");
		    	});
		    }
		    let retVal = "";
		    for (var i = 0, n = charset.length; i < length; ++i) {
		        retVal += charset.charAt(Math.floor(Math.random() * n));
		    }
		    return retVal;
		}
		function __insertSeperator(str, serial_delimiter, serial_delimiter_space) {
			if (str !== "" && serial_delimiter !== "" && serial_delimiter_space > 0) {
				let result = [str[0]];
				for(let x=1; x<str.length; x++) {
	    			if (x%serial_delimiter_space === 0) {
	      				result.push(serial_delimiter, str[x]);
	     			} else {
	      				result.push(str[x]);
	     			}
	  			}
				return result.join('');
			}
			return str;
		}

		function _isTypeNumbers() {
			return input_type_codes.val()  === "2";
		}
		function _getPrefix() {
			return input_prefix_codes.val().trim();
		}
		function _getAmountLetters() {
			let amount_letters = parseInt(input_amount_letters.val().trim(),10);
			if (isNaN(amount_letters) || amount_letters < 1) {
				input_amount_letters.select();
				return alert("Amount of letters has to be higher");
			}
			return amount_letters;
		}
		function _getLetterExclusion() {
			return input_letter_excl.val() === "2" ? ['i','l','o','p','q'] : [];
		}
		function _getLetterStyle() {
			return parseInt(input_letter_style.val(),10);
		}
		function _getIncludeNumbers() {
			return parseInt(input_include_numbers.val(),10);
		}
		function _getSerialDelimiter() {
			return ['','-',' ',':'][parseInt(input_serial_delimiter.val(),10)-1];
		}
		function _getSerialDelimiterSpace() {
			let serial_delimiter_space = 0;
			try {
				serial_delimiter_space = _getSerialDelimiter() !== "" ? parseInt(input_serial_delimiter_space.val(),10) : 0;
			} catch (e) {}
			return serial_delimiter_space;
		}
		function _getNumberStart() {
			let start_number = parseInt(input_number_start.val().trim(),10);
			if (isNaN(start_number) || start_number < 1) {
				input_number_start.select();
				return alert("Your start number is not correct. It has to be an integer and start from 1");
			}
			return start_number;
		}
		function _getNumberOffset() {
			let number_offset = parseInt(input_number_offset.val().trim(),10);
			if (isNaN(number_offset) || number_offset < 1) number_offset = 1;
			return number_offset;
		}
		function _generateSerialCode(offsetCounter) {
			let code;
			let prefix = _getPrefix();
			if (_isTypeNumbers()) { // numbers
				if (!offsetCounter) offsetCounter = 0;
				let number_offset = offsetCounter * _getNumberOffset();
				code = _getNumberStart() + number_offset;
				if (prefix !== '') code = prefix + code;
			} else {
				code = __generateCode(_getAmountLetters(), _getLetterStyle(), _getIncludeNumbers(), _getLetterExclusion());
				code = __insertSeperator(code, _getSerialDelimiter(), _getSerialDelimiterSpace());
				if (prefix !== '') code = prefix + code;
			}
			return code;
		}
		function _getFormatterValues() {
			return {
				input_prefix_codes:_getPrefix(),
				input_type_codes:input_type_codes.val(),
				input_amount_letters:_getAmountLetters(),
				input_letter_excl:input_letter_excl.val(),
				input_letter_style:_getLetterStyle(),
				input_include_numbers:input_include_numbers.val(),
				input_serial_delimiter:input_serial_delimiter.val(),
				input_serial_delimiter_space:input_serial_delimiter_space.val(),
				input_number_start:_getNumberStart(),
				input_number_offset:_getNumberOffset()
			};
		}

		return {
			render:__render,
			getAmountLetters:_getAmountLetters,
			getLetterExclusion:_getLetterExclusion,
			getLetterStyle:_getLetterStyle,
			getIncludeNumbers:_getIncludeNumbers,
			getSerialDelimiter:_getSerialDelimiter,
			getSerialDelimiterSpace:_getSerialDelimiterSpace,
			getNumberStart:_getNumberStart,
			getNumberOffset:_getNumberOffset,
			isTypeNumbers:_isTypeNumbers,
			getPrefix:_getPrefix,
			generateSerialCode:_generateSerialCode,
			setNoNumberOptions:_setNoNumberOptions,
			getFormatterValues:_getFormatterValues,
			setCallbackHandle:_setCallbackHandle,
			setFormatterValues:_setFormatterValues,
			createDivInput:_createDivInput
		};
	}

	function _createDivInput(label) {
		return $('<div/>').css({
			"display": "inline-block",
		    "margin-bottom": "15px",
		    "margin-right": "15px"
		}).html(label+"<br>");
	}

	class Layout {
		constructor(){
			DIV.addClass("sngmbh_container").css("margin-right", "10px");
			this.div_liste = $('<div/>').html(_getSpinnerHTML());
			this.div_codes = $('<div/>').html(_getSpinnerHTML());
		}
		renderMainBody() {
			let div_body = $('<div/>');
			if (isPremium()) {
				div_body.append('<div style="color:green;font-weight:bold;">PREMIUM active</div>');
			} else {
				div_body.append('<div style="color:red;font-weight:bold;">FREE version</div>');
			}
			div_body.append($('<div style="text-align:right;">').html(_displaySettingAreaButton()));
			div_body.append($('<h3/>').html("Lists of codes"));
			div_body.append($('<p/>').html("Organize your codes in lists. You can assign codes to a list."));
			div_body.append(this.div_liste);
			div_body.append($('<hr/>'));
			div_body.append($('<h3/>').html("Codes"));
			div_body.append(this.div_codes);
			return div_body;
		}
		renderAddCodes(cbf) {
			DIV.html(_getSpinnerHTML());
			getDataLists(function() {
				function __generateCodes() {
					// generate codes and
					let amount_codes = parseInt(input_amount_codes.val().trim(),10);
					if (isNaN(amount_codes) || amount_codes < 1) {
						input_amount_codes.select();
						return alert("Enter an amount of how many codes you need");
					}
					if (amount_codes > _maxCodes) {
						input_amount_codes.val(_maxCodes);
						amount_codes = _maxCodes;

					}
					let uniq = {};
					let versuche = 0;
					if (serialCodeFormatterForm.isTypeNumbers()) { // numbers
						for(let a=0; a < amount_codes; a++) {
							let code = serialCodeFormatterForm.generateSerialCode( a );
							if (typeof uniq[code] !== "undefined") {
								continue;
							}
							uniq[code] = true;
						}
						versuche = amount_codes;
					} else {
						// erstmal kein check ob mit dem alphabet und die geforderte Menge an letters, unique codes erstellt werden können
						let counter = 0;
						let versuche_max = amount_codes * 1.5;
						while(counter < amount_codes && versuche < versuche_max) {
							versuche++;
							let code = serialCodeFormatterForm.generateSerialCode();
							if (typeof uniq[code] !== "undefined") {
								continue;
							}
							uniq[code] = true;
							counter++;
						}
					}
					return [Object.keys(uniq), versuche];
				} // __generateCodes

				let div = $('<div/>').append($('<button/>').addClass("button-primary").html("Back").css("margin-bottom", "10px").on("click", function(){
					LAYOUT.renderAdminPageLayout();
				}));
				// eingabe generator options
				let div_generator = $('<div/>').css("padding", "10px").css("border","1px solid black").html("<h3>1. Code generator (optional step)</h3>").appendTo(div);
				if (isPremium()) div_generator.append('<p>Up 100.000 codes generation per run. The limit is to prevent performance issues.<br>You can repeat the "store codes" operations as often as needed.</p>');
				// anzahl codes
				let div_amount_codes = _createDivInput("Enter amount of codes needed").appendTo(div_generator);
				let _maxCodes = myAjax._max.codes;
				if (!isPremium()) div_amount_codes.append(_maxCodes+' Max. '+getLabelPremiumOnly()+' up to 100.000 for each run<br>');
				let input_amount_codes = $('<input type="number" required value="100" min="1" max="'+_maxCodes+'">').appendTo(div_amount_codes);

				// predefine elements
				let serialCodeFormatterForm = _form_fields_serial_format(div_generator);
				serialCodeFormatterForm.render();

				let elem_clean_codebox = $('<input checked type="checkbox" />');
				$('<div/>').css({"margin-bottom": "15px","margin-right": "15px"})
					.html(elem_clean_codebox)
					.append('Clear the code list textarea field below to add only the new generated codes')
					.appendTo(div_generator);

				let elem_create_cvv = $('<input type="checkbox" />');
				$('<div/>').css({"margin-bottom": "15px","margin-right": "15px"})
					.html(elem_create_cvv)
					.append('Generate Code Verification Value (CVV) for each code')
					.appendTo(div_generator);

				// button generate
				div_generator.append($('<button/>').addClass("button-secondary").html("Generate codes").on("click", function(){
					let time_start = performance.now();
					btn_store_codes.prop("disabled", false);
					input_textarea.prop("disabled", false);
					if (elem_clean_codebox[0].checked) {
						input_textarea.html("");
					}
					input_textarea.prop("disabled", true);
					div_textarea_info.css("padding-bottom", "50px").html(_getSpinnerHTML());
					setTimeout(function(){
						let r = __generateCodes();
						let codes = r[0];
						let secs = ((performance.now() - time_start) / 1000)+"";
						if (elem_create_cvv[0].checked) {
							codes = codes.map(v=>{
								return v += ';'+(Math.floor(Math.random() * 10000) + 10000).toString().substring(1);
							});
						}
						input_textarea.append(codes.join("\n")).append("\n");
						input_textarea.prop("disabled", false);
						div_textarea_info.html('Created '+codes.length+' codes. In '+secs.slice(0,5)+' seconds, with '+r[1]+' runs to find unique codes');
						_calcLinesOfCodeTextArea();
					},250);
				}));

				// eingabe maske textarea
				function _calcLinesOfCodeTextArea() {
					let codesAmount = 0;
					input_textarea.val().trim().split('\n').forEach(v=>{
						if (v.trim() !== "") codesAmount++;
					});
					input_textarea_info.html('contains '+codesAmount+' codes');
				}
				let div_textarea = $('<div/>').html('<h3>2. Codes to store on the server</h3><p>One code per line and/or comma-separated (,). <br>If you want to add the CVV number then separate your serial code with (;) and append your CVV number.<br>While storing the codes to the server, it will check if the code is unique and mark the codes, that are not.</p>').appendTo(div);
				let div_textarea_info = $('<div/>').appendTo(div_textarea);
				let input_textarea = $('<textarea>').change(_calcLinesOfCodeTextArea).css("height","135px").css("width","100%").appendTo(div_textarea);
				let input_textarea_info = $('<div/>').appendTo(div_textarea);
				div_textarea.append("<br>");
				_calcLinesOfCodeTextArea();
				// list auswahl
				let div_code_list = _createDivInput("Assign to this code list").appendTo(div_textarea);
				let input_code_list = $('<select><option value="0">None</select></select>').appendTo(div_code_list);
				DATA_LISTS.forEach(v=>{
					input_code_list.append('<option value="'+v.id+'">'+v.name+'</option>');
				});
				div_textarea.append("<br>");

				// additional prem fields
				if (isPremium() && PREMIUM.addAddCodeFields) {
					div_textarea.append(PREMIUM.addAddCodeFields());
				}

				// button store codes
				if (!isPremium()) div_textarea.append("<b>You can store up to "+myAjax._max.codes_total+'. '+getLabelPremiumOnly()+' unlimited<br>');
				let btn_store_codes = $('<button/>');
				btn_store_codes.addClass("button-primary").html("Store codes").on("click", function(){
					// extract codes and
					let codes = [];
					let codesLines = input_textarea.val().split("\n").map(x=>x.trim());
					codesLines.forEach(x=>{
						x = destroy_tags(x);
						x.split(",").forEach(y=>{
							y = y.trim();
							y = destroy_tags(y);
							if (y != "") codes.push(y);
						});
					});
					if (codes.length === 0) return;

					// sperre btn store codes
					btn_store_codes.prop("disabled", true);
					input_textarea.prop("disabled", true);

					div_textarea_info.append($('<div/>').addClass("notice notice-info").html("Each entry will turn green (successfull stored) or red (NOT OK - duplicat entry on the server).<br>Scroll down and wait for all to finish.<br>In the textarea below you will find all the successful stored codes."));
					let _output = $('<ol/>').appendTo(div_textarea_info);
					div_textarea_info.append("<h3>Successfull stored codes</h3>");
					let output_textarea_codes_done = $('<textarea disabled style="4px solid green;width:100%;height:150px;"></textarea>').appendTo(div_textarea_info);

					let list_id = parseInt(input_code_list.val(),10);

					function __addCodesInChunks(chunk_size) {
					    let dlg = $('<div/>').html(_getSpinnerHTML());
						dlg.dialog({title:'Importing',closeOnEscape: true,modal: true, dialogClass: "no-close", close: function(event, ui){ abort=true; } });

						let abort = false;
						let counter_ok = 0;
						let counter_notok = 0;
						let counter_all = codes.length;
						const array_chunks = (array, chunk_size) => Array(Math.ceil(array.length / chunk_size)).fill().map((_, index) => index * chunk_size).map(begin => array.slice(begin, begin + chunk_size));
						let chunks = array_chunks(codes, chunk_size);
						function _addCodeChunk(idx) {
							if (abort) return;
							if (idx >= chunks.length) {
								dlg.append("<p>Import process finished</p>");
								$('<center/>').append($('<button class="button-primary" />').html('Ok').on("click", ()=>{ closeDialog(dlg); })).appendTo(dlg);
								return;
							}
							let arr = chunks[idx];
							arr.forEach(v=>{
								let div_info_entry = $('<li data-id="code_'+v+'"/>').html(v);
								_output.append(div_info_entry);
							});
							let attr = {"codes":arr, "list_id":list_id};
							if (isPremium() && PREMIUM.addAddCodeFieldsData) {
								attr = PREMIUM.addAddCodeFieldsData(div_textarea, attr);
							}

							_makePost("addCodes", attr, function(data){
								counter_ok += data.ok.length;
								counter_notok += data.notok.length;
								if (myAjax._max.codes_total > 0 && myAjax._max.codes_total <= parseInt(data.total_size)) {
									div_textarea_info.prepend('<h3 style="color:red;">Your Limit of '+myAjax._max.codes_total+' codes is reached. Use the premium version to have unlimited codes.</h3>');
								}
								let per = Math.ceil(((counter_ok+counter_notok)/counter_all)*100);
								let info_content = '<div style="width:100%;border:1px solid #efefef;background-color:white;"><div style="text-align:center;height:20px;background-color:#428bca;color:white;width:'+per+'%;">'+per+'%</div></div>';
								info_content += '<p style="margin-top:20px;">Amount: '+(counter_ok+counter_notok)+'/'+counter_all+'<br>Ok: '+counter_ok+'<br>Not OK: '+counter_notok+'</p>';
								dlg.html(info_content);
								data.ok.forEach(_v=> {
									_output.find('li[data-id="code_'+_v+'"]').css("color","green").append(" (OK)");
									output_textarea_codes_done.append(_v+"\n");
								});
								data.notok.forEach(_v=> {
									_output.find('li[data-id="code_'+_v+'"]').css("color","red").append(" (NOT OK)");
								});
								setTimeout(()=>{
									_addCodeChunk(idx+1);
								}, 100);
							}, function(response){
								if (response.data.slice(0,4) === "#208") {
									FATAL_ERROR === false && LAYOUT.renderFatalError(response.data);
									FATAL_ERROR = true;
								}
							});
						}

						if (chunks.length === 0) {
							closeDialog(dlg);
						} else {
							_addCodeChunk(0);
						}
					} // __addCodesInChunks
					__addCodesInChunks(100);

					// zeige ok button, der info area leer macht und den btn store codes wieder aktiviert
					div_textarea_info.append($('<button/>').addClass("button-primary").css("margin-bottom", "20px").html("Ok").on("click", function(){
						div_textarea_info.html("");
						btn_store_codes.prop("disabled", false);
						input_textarea.prop("disabled", false);
						window.scrollTo(0,0);
					}));

				}).appendTo(div_textarea);
				DIV.html(div);
				cbf && cbf();
			});
		}
		renderAdminPageLayout(cbf) {
			function __showListImport() {
				let content = $('<div/>');
				$('<p>').html('You can upload a CSV file (, delimiter). You have to provide a header line with column names.').appendTo(content);
				$('<ul>')
					.append($('<li>').html('<b>name</b> - for the list name'))
					.append($('<li>').html('<b>desc</b> - short description'))
					.append($('<li>').html('<b>codes</b> - one or more serials that will be added if not exists and assigned to the list.<br>You can use ";" to add more than one code. e.g. 1234-abc;2345-abc. Missing codes will be created and set active.'))
					.append($('<li>').html('<b>meta_redirect_isdisabled</b> - 1 or 0 to deactivate or activate the redirect option for this list'))
					.append($('<li>').html('<b>meta_redirect_btndontshow</b> - 1 or 0 to hide or display the redirect button'))
					.append($('<li>').html('<b>meta_redirect_url</b> - URL for a redirect'))
					.append($('<li>').html('<b>meta_redirect_btn</b> - button label for the redirect button'))
					.appendTo(content);
				$('<p>').html('If the same list name will be used in the same file, then the description will be updated with the last line and the serials are added. No serials are removed from the list.').appendTo(content);
				let form = $('<form>');
				let input_file = $('<input type="file" id="csv_file" name="csv_file">').on("change", event=>{
					let filename = event.target.value.split(/(\\|\/)/g).pop().trim();
					if (filename === "") {
						form.find('input[type="submit"]').prop("disabled", true);
					} else {
						form.find('input[type="submit"]').prop("disabled", false);
					}
				});
				form.append(_createDivInput("Choose CSV file").append(input_file));
				form.append('<p><input disabled class="button-primary" type="submit"></p>');
				form.on("submit", event=>{
					event.preventDefault();

					var form_data = new FormData();
					form_data.append('action', myAjax._action);
					form_data.append('a_sngmbh', 'importLists');
					var file_data = $('#csv_file').prop('files')[0];
					form_data.append('file', file_data);

					form.find('input[type="submit"]').prop("disabled", true);
					content.append(_getSpinnerHTML());

					$.ajax({
						url: myAjax.url, // point to server-side PHP script
						dataType: 'json',  // what to expect back from the PHP script, if anything
						cache: false,
						contentType: false,
						processData: false,
						data: form_data,
						type: 'post',
						success: function(result){
							$('#csv_file').val("");
							content.html('<h3>Output</h3>');
							if (result.success) {
								//content.append(JSON.stringify(result));
								content.append('<div>Total lines read: <b>'+result.data.total+'</b></div>');
								content.append('<div>Total lines with lists: <b>'+result.data.lines+'</b></div>');
								content.append('<div>Total lists imported: <b>'+result.data.imported+'</b></div>');
								content.append('<div>Total lists created: <b>'+result.data.counter_list_created+'</b></div>');
								content.append('<div>Total lists updated: <b>'+result.data.counter_list_updated+'</b></div>');
								content.append('<div>Total codes found: <b>'+result.data.counter_codes+'</b></div>');
								content.append('<div>Total codes created: <b>'+result.data.counter_codes_created+'</b></div>');
								content.append('<div>Total codes updated: <b>'+result.data.counter_codes_updated+'</b></div>');
								content.append('<p><b>NOTE:</b> If the list already exists then it will be updated.</p>');
							}
							DATA_LISTS = null;
							__renderTabelleListen();
							tabelle_codes_datatable.ajax.reload();
						}
					 });

				});

				form.appendTo(content);

				LAYOUT.renderInfoBox("Import List", content);
			}

			function __showMaskExport(totalRecordCount) {
				if (!totalRecordCount) totalRecordCount = 0;
				let maxRange = totalRecordCount > 40000 ? 40000 : totalRecordCount;
				let _options = {
					title: 'Export codes',
			      	modal: true,
			      	minWidth: 400,
					minHeight: 200,
			      	buttons: [
			      		{
			      			text: "Export",
			      			click: function() {
								___submitForm();
			      			}
			      		},
			      		{
			      			text: "Cancel",
			      			click: function() {
			      				closeDialog(this);
			      			}
			      		}
		      		]
			    };
			    let formdlg = $('<form/>').html('<b>Choose your export settings</b><p>');
			    formdlg.append('Choose the delimiter for the column values<br><select name="delimiter"><option value="1">, (Comma)</option><option value="2">; (Semicolon)</option><option value="3">| (Pipe)</option></select><p>');
			    formdlg.append('Choose a file suffix<br><select name="suffix"><option value="1">.csv</option><option value="2">.txt</option></select><p>');

			    let _listChooser = $('<select name="listchooser"><option value="0">All</option></select>');
			    for(let a=0;a<DATA_LISTS.length;a++) {
			    	_listChooser.append('<option value="'+DATA_LISTS[a].id+'">'+DATA_LISTS[a].name+'</option>');
			    }
			    formdlg.append('Limit export to code list<br>').append(_listChooser).append('<p>');

			    formdlg.append('Choose a sorting field<br><select name="orderby"><option value="1" selected>Creation date</option><option value="2">Code</option><option value="3">Code Display</option><option value="4">List name</option></select><p>');
			    formdlg.append('Choose a sorting direction<br><select name="orderbydirection"><option value="1" selected>Ascending</option><option value="2">Descending</option></select><p>');
			    formdlg.append('Set a range<br><i>You have '+totalRecordCount+' codes stored.<br>Some systems are slow and the connection timeout interupts the export, if you have too many codes. In that case, you can export your codes in several steps. e.g. 0 and 20000 amount and then 20001 and 20000 amount.</i><br>Enter your row start (0 = from the first)<br><input type="number" name="rangestart" value="0"><br>Enter amount of codes<br><input type="number" name="rangeamount" value="'+maxRange+'"><p>');
			    let dlg = $('<div/>').append(formdlg);

				dlg.dialog(_options);

				let form = dlg.find("form").on("submit", function(event) {
					event.preventDefault();
					___submitForm();
				});

				function ___submitForm() {
					let delimiter = dlg.find('select[name="delimiter"]').val();
					let filesuffix = dlg.find('select[name="suffix"]').val();
					let orderby = dlg.find('select[name="orderby"]').val();
					let orderbydirection = dlg.find('select[name="orderbydirection"]').val();
					let rangestart = dlg.find('input[name="rangestart"]').val();
					let rangeamount = dlg.find('input[name="rangeamount"]').val();
					let listchooser = dlg.find('select[name="listchooser"]').val();
					let url = _requestURL('exportTableCodes', {'delimiter':delimiter, 'filesuffix':filesuffix, 'orderby':orderby, 'orderbydirection':orderbydirection, 'rangestart':rangestart, 'rangeamount':rangeamount, 'listchooser':listchooser});
					closeDialog(dlg);
					window.open(url, "_blank");
				}
			}
			function __showMaskList(editValues){
				let _options = {
					title: editValues !== null ? 'Edit List' : 'Add List',
			      	modal: true,
			      	minWidth: 600,
					minHeight: 400,
					open: function(e) {
        				//$(e.target).parent().css('background-color','orangered');
    				},
    				buttons: [
			      		{
			      			text: "Ok",
			      			click: function() {
								___submitForm();
			      			}
			      		},
			      		{
			      			text: "Cancel",
			      			click: function() {
			      				closeDialog(this);
			      			}
			      		}
		      		]
			    };
			    let dlg = $('<div/>').html('<form>Name<br><input name="inputName" type="text" style="width:100%;" required></form>');
				dlg.dialog(_options);

				dlg.find("form").append($('<p>Description<br><textarea name="desc" style="width:100%;"></textarea></p>'));

				if (isPremium()) PREMIUM.addListMaskEditFields(dlg, editValues);
				else {
					if (_getOptions_isActivatedByKey("oneTimeUseOfRegisterCode")) {
						dlg.append($('<p><b>Overrule '+_getOptions_getLabelByKey("h4")+' per Code list</b> '+getLabelPremiumOnly()+'</p>'));
					}
				}

				let metaObj = [];
				if (editValues && typeof editValues.meta !== "undefined" && editValues.meta != "") {
					try {
						metaObj = JSON.parse(editValues.meta);
					} catch(e) {}
				}

				let isRedirectActivated = _getOptions_isActivatedByKey("userJSRedirectActiv");
				let p_redir = $('<div>').css("margin-top", "10px").css("margin-left", "24px").css("padding", "10px").css("border", "1px solid black");
				if (!isRedirectActivated) {
					p_redir.css("color", "grey");
					p_redir.append('<div style="color:red;">Redirect user is deactivated in the option.</div>');
				}
				p_redir.append('<input type="checkbox" name="redirectisdisabled"> Do not redirect the user.');
				p_redir.append('<br><br>'+_getOptions_getLabelByKey("userJSRedirectURL"));
				p_redir.append('<br><input type="text" name="redirecturl" style="width:100%;">');
				p_redir.append('<br><br>'+_getOptions_getLabelByKey("userJSRedirectBtnLabel"));
				p_redir.append('<br><input type="text" name="redirectbtn" style="width:100%;">');
				p_redir.append('<br><i>'+_getOptions_getDescByKey("userJSRedirectBtnLabel")+' Or the value of the global setting "'+_getOptions_getValByKey("userJSRedirectBtnLabel")+'" for this field will be taken.</i>');
				p_redir.append('<br><br><input type="checkbox" name="redirectbtndontshow"> Do not show the button and redirect immediately. Overrules the global settings.');
				if (!isRedirectActivated) {
					p_redir.find('input[name="redirectisdisabled"]').prop("disabled", true);
					p_redir.find('input[name="redirecturl"]').prop("disabled", true);
					p_redir.find('input[name="redirectbtn"]').prop("disabled", true);
					p_redir.find('input[name="redirectbtndontshow"]').prop("disabled", true);
				}
				dlg.find("form").append($('<p><b>'+_getOptions_getLabelByKey("h8")+'</b></p>').append(p_redir));

				dlg.find("form").append($('<p><input name="serialformatter" type="checkbox"> Overrule the serial code format settings.</p>'));
				let extra_div = $('<div>').appendTo(dlg).css("margin-top", "10px").css("margin-left", "24px").css("padding", "10px").css("border", "1px solid black")
						.html('<p><b>Note:</b> Will be overriden if you set the serial code format settings on the product!</p>');
				let serialCodeFormatter = _form_fields_serial_format(extra_div);
				serialCodeFormatter.setNoNumberOptions();
				if (typeof metaObj.formatter !== "undefined" && metaObj.formatter.format != "") {
					let formatterValues;
					try {
						let o = metaObj.formatter.format.replace(new RegExp("\\\\", "g"), "").trim();
						formatterValues = JSON.parse(o);
						serialCodeFormatter.setFormatterValues(formatterValues);
					} catch (e) {}
				}
				serialCodeFormatter.render();

				let form = dlg.find("form").on("submit", function(event) {
					event.preventDefault();
					___submitForm();
				});

				if (editValues) {
					form[0].elements['inputName'].value = editValues.name;
					form[0].elements['inputName'].select();
					if (typeof metaObj.desc !== "undefined") {
						form[0].elements['desc'].value = metaObj.desc;
					}
					if (typeof metaObj.formatter !== "undefined" && metaObj.formatter.active) {
						form[0].elements['serialformatter'].checked = true;
					}
					if (_getOptions_isActivatedByKey("userJSRedirectActiv") && typeof metaObj.redirect !== "undefined") {
						if (typeof metaObj.redirect.url !== "undefined") {
							form[0].elements['redirecturl'].value = metaObj.redirect.url.trim();
						}
						if (typeof metaObj.redirect.btn !== "undefined") {
							form[0].elements['redirectbtn'].value = metaObj.redirect.btn.trim();
						}
						if (typeof metaObj.redirect.btndontshow !== "undefined" && metaObj.redirect.btndontshow) {
							form[0].elements['redirectbtndontshow'].checked = true;
						}
						if (typeof metaObj.redirect.isdisabled !== "undefined" && metaObj.redirect.isdisabled) {
							form[0].elements['redirectisdisabled'].checked = true;
						}
					}
				}

				function ___submitForm() {
					let inputName = form[0].elements['inputName'].value.trim();
					if (inputName === "") return;

					dlg.html(_getSpinnerHTML());
					let _data = {"name":inputName};
					_data['meta'] = {"desc":"", "formatter":{}};
					_data['meta']['desc'] = form[0].elements['desc'].value.trim();
					_data['meta']['formatter']['active'] = form[0].elements['serialformatter'].checked ? 1 : 0;
					_data['meta']['formatter']['format'] = JSON.stringify(serialCodeFormatter.getFormatterValues());
					if (_getOptions_isActivatedByKey("userJSRedirectActiv")) {
						let redir_obj = {"url":form[0].elements['redirecturl'].value.trim()};
						redir_obj.btn = form[0].elements['redirectbtn'].value.trim();
						redir_obj.btndontshow = form[0].elements['redirectbtndontshow'].checked ? 1 : 0;
						redir_obj.isdisabled = form[0].elements['redirectisdisabled'].checked ? 1 : 0;
						_data['meta']['redirect'] = redir_obj;
					}
					if (isPremium()) PREMIUM.addListMaskEditFieldsData(_data, form[0], editValues);

					form[0].reset();
					if (editValues) {
						_data.id = editValues.id;
						_makePost('editList', _data, function(result) {
							DATA_LISTS = null;
							__renderTabelleListen();
							tabelle_codes_datatable.ajax.reload();
							setTimeout(function(){closeDialog(dlg);},250);
						}, function() {
							closeDialog(dlg);
						});
					} else {
						_makePost('addList', _data, function(result) {
						//_makeGet('addList', _data, function(result) {
							DATA_LISTS = null;
							__renderTabelleListen();
							closeDialog(dlg);
						}, function(response) {
							if (response.data.slice(0,4) === "#108") {
								FATAL_ERROR === false && LAYOUT.renderFatalError(response.data);
								FATAL_ERROR = true;
							}
							closeDialog(dlg);
						});
					}
				}

			} // ende showmaskliste

			function __showMaskCode(editValues){
				let _options = {
					title: editValues !== null ? 'Edit Code' : 'Add Code',
			      	modal: true,
			      	minWidth: 400,
					minHeight: 200,
			      	buttons: [
			      		{
			      			text: "Ok",
			      			click: function() {
								___submitForm();
			      			}
			      		},
			      		{
			      			text: "Cancel",
			      			click: function() {
				        		$( this ).dialog( "close" );
				        		$( this ).html('');
			      			}
			      		}
		      		]
			    };
			    let dlg = $('<div />').html('<form>List<br><select name="inputListId"><option value="0">None</option></select></form>');
				DATA_LISTS.forEach(v=>{
					$(dlg).find('select[name="inputListId"]').append('<option '+(editValues && parseInt(editValues.list_id,10) === parseInt(v.id,10) ? 'selected ':'')+'value="'+v.id+'">'+v.name+'</option>');
				});

				let elem_cvv = $('<input type="text" size="6" minlength="5" maxlength="4" />');
				$('<div/>').css({"margin-top":"10px","margin-bottom": "15px","margin-right": "15px"})
					.html('CVV - use 4 digits for best results<br>')
					.append(elem_cvv)
					.append('<br><i>If CVV is set, then your user will be asked to enter also the CVV to check the serial code.</i>')
					.appendTo(dlg.find("form"));

				let div_status = $('<div/>');
				div_status.append(
					$('<select name="inputStatus"/>')
						.append('<option '+(editValues.aktiv === "1"?'selected':'')+' value="1">is activ</option>')
						.append('<option '+(editValues.aktiv === "0"?'selected':'')+' '+(!isPremium()?'disabled':'')+' value="0">is inactiv '+(!isPremium()?getLabelPremiumOnly():'')+'</option>')
						.append('<option '+(editValues.aktiv === "2"?'selected':'')+' value="2">is stolen</option>')
					)
				.appendTo(dlg);

				dlg.dialog(_options);

				if (editValues) {
					if (editValues.cvv) elem_cvv.val(editValues.cvv);
				}

				if (isPremium()) PREMIUM.addCodeMaskEditFields(dlg, editValues);

				let form = dlg.find("form").on("submit", function(event) {
					event.preventDefault();
					___submitForm();
				});
				function ___submitForm() {
					let inputListId = parseInt($(dlg).find('select[name="inputListId"]').val(),10);
					let inputStatusValue = $(dlg).find('select[name="inputStatus"]').val();
					dlg.html(_getSpinnerHTML());
					let _data = {"list_id":inputListId, "aktiv":inputStatusValue, "cvv":elem_cvv.val().trim()};
					if (isPremium()) PREMIUM.addCodeMaskEditFieldsData(_data, form[0], editValues);
					form[0].reset();
					if (editValues) {
						_data.code = editValues.code;
						_makeGet('editCode', _data, function(result) {
							tabelle_codes_datatable.ajax.reload();
							closeDialog(dlg);
						}, function() {
							closeDialog(dlg);
						});
					} else {
						alert("use the add option");
					}
				}
			} // ende __showMaskCode

			let id_codes = myAjax.divPrefix+'_tabelle_codes';
			let tabelle_liste_datatable;
			let tabelle_codes_datatable;
			let tabelle_codes = $('<table/>').attr("id", id_codes);
			let tplace = $('<div/>');

			function __renderTabelleListen() {
				getDataLists(function(){
					let id_liste = myAjax.divPrefix+'_tabelle_liste';
					let tabelle_liste = $('<table/>').attr("id", id_liste);
					tabelle_liste.html('<thead><tr><th align="left">Name</th><th align="left">Created</th><th></th></tr></thead>');
					tplace.html(tabelle_liste);

					let table = $('#'+id_liste);
					$(table).DataTable().clear().destroy();
					tabelle_liste_datatable = $(table).DataTable({
						"visible": true,
						"searching": true,
		    			"ordering": true,
		    			"processing": true,
		    			"serverSide": false,
		    			"stateSave": true,
		    			"data": DATA_LISTS,
		    			"order": [[ 0, "asc" ]],
		    			"columns":[
		    				{"data":"name", "orderable":true},
		    				{"data":"time", "orderable":true, "width":80},
		    				{"data":null,"orderable":false,"defaultContent":'',"className":"buttons dt-right","width":110,
		    					"render": function ( data, type, row ) {
		    						return '<button class="button-secondary" data-type="showCodes">Codes</button> <button class="button-secondary" data-type="edit">Edit</button> <button class="button-secondary" data-type="delete">Delete</button>';
		                		}
		                	}
		    			]
					});
					table.on('click', 'button[data-type="showCodes"]', function (e) {
		        		let data = tabelle_liste_datatable.row( $(this).parents('tr') ).data();
		        		tabelle_codes_datatable.search("LIST:"+data.id).draw();
					});
					table.on('click', 'button[data-type="edit"]', function (e) {
		        		let data = tabelle_liste_datatable.row( $(this).parents('tr') ).data();
		        		__showMaskList(data);
					});
					table.on('click', 'button[data-type="delete"]', function (e) {
		        		let data = tabelle_liste_datatable.row( $(this).parents('tr') ).data();
		        		LAYOUT.renderYesNo('Do you want to delete?', 'Are you sure, you want to delete this list?<br><p><b>'+data.name+'</b></p>No code will be deleted. Just the list.', function() {
		        			let _data = {'id':data.id};
		        			_makePost('removeList', _data, function(result) {
								__renderTabelleListen();
								tabelle_codes_datatable.ajax.reload();
							});
		        		});
					});
				}); // end of loading lists
			} // __renderTabelleListen

			STATE = 'admin';
			DIV.html(_getSpinnerHTML());
			_makeGet('getOptions', {}, optionData=>{
				_setOptions(optionData);
				DIV.html('');
				DIV.append(this.renderMainBody());

				let btn_liste_empty = $('<button/>').addClass("button-secondary").html("Empty table").on("click", function(){
					if (confirm("Do you want to empty the 'List of Codes' table? All data will be lost.")) {
						_makeGet('emptyTableLists', null, function(result){
							tabelle_codes_datatable.ajax.reload();
							__renderTabelleListen();
						});
					}
				});
				let btn_liste_import = $('<button/>').addClass("button-secondary").html("Import").on("click", function(){
					__showListImport();
				});
				let btn_liste_new = $('<button/>').addClass("button-primary").html("Add").on("click", function(){
					__showMaskList(null);
				});
				this.div_liste.html($('<div/>').css('text-align', 'right').css('margin-bottom','10px').append(btn_liste_import).append(btn_liste_empty).append(isPremium()?'':' Max. '+myAjax._max.lists+' list. Unlimited with '+getLabelPremiumOnly()+' ').append(btn_liste_new));
				this.div_liste.append(tplace);

				__renderTabelleListen();

				let additionalColumn = {customerName:''};
				if (_getOptions_isActivatedByKey('displayAdminAreaColumnBillingName')) {
					additionalColumn.customerName = '<th>Customer</th>';
				}

				tabelle_codes.html('<thead><tr><th style="text-align:left;padding-left:10px;"><input type="checkbox" data-id="checkAll"></th><th>&nbsp;</th><th align="left">Code</th>'+additionalColumn.customerName+'<th align="left">List</th><th align="left">Created</th><th>OrderId</th><th>CVV</th><th>Status</th><th>Used</th><th></th></tr></thead>');
				tabelle_codes.find('input[data-id="checkAll"]').on('click', (e)=> {
					let isChecked = $(e.currentTarget).prop('checked');
					let found = false;
					tabelle_codes.find('input[data-type="select-checkbox"]').each((i,v)=>{
						$(v).prop('checked', isChecked);
						found = true;
					});
					if (isChecked && found) {
						//drop_codes_bulk.prop("disabled", false);
					} else {
						//drop_codes_bulk.prop("disabled", true);
					}
				});
				let btn_codes_new = $('<button/>').addClass("button-primary").html("Add").on("click", function(){
					if (!isPremium() && tabelle_codes_datatable.page.info().recordsTotal > myAjax._max.codes_total) {
						alert("You reached maximum amount of codes. You need to delete codes before you can add more new codes or buy the premium version to have unlimited codes.");
					} else {
						LAYOUT.renderAddCodes();
					}
				});
				let btn_codes_empty = $('<button/>').addClass("button-secondary").html("Empty table").on("click", function(){
					if (confirm("Do you want to empty the 'Codes' table? All data will be lost.")) {
						_makeGet('emptyTableCodes', null, function(result){
							tabelle_codes_datatable.ajax.reload();
						});
					}
				});
				let btn_codes_export = $('<button/>').addClass("button-secondary").html("Export codes").on("click", function(){
					//let url = _requestURL('exportTableCodes', null);
					//window.open(url, "_blank");
					//console.log(tabelle_codes_datatable.page.info());
					__showMaskExport(tabelle_codes_datatable.page.info().recordsTotal);
				});
				let drop_codes_bulk = $('<select data-id="bulk-code-action" />').html('<option value="">Bulk Action</option>').append('<option value="delete">Delete</option>');
				drop_codes_bulk.on('change', ()=>{
						let val = drop_codes_bulk.val();
						if (val !== "") {
							let selectedElems = [];
							tabelle_codes.find('input[data-type="select-checkbox"]').each((i,v)=>{
								if ($(v).prop("checked")) selectedElems.push(v);
							});
							if (selectedElems.length) {
								if (BulkActions.codes[val]) BulkActions.codes[val](selectedElems, tabelle_codes_datatable);
							}
						}
						drop_codes_bulk.val('');
					});

				this.div_codes.html($('<div/>').css('text-align', 'right').css('margin-bottom','10px').append(drop_codes_bulk).append(btn_codes_export).append(btn_codes_empty).append(isPremium()?'':' Max. '+myAjax._max.codes_total+' codes. Unlimited with '+getLabelPremiumOnly()+' ').append(btn_codes_new));
				this.div_codes.append(tabelle_codes);

				let table_columns = [
					{"data":null,"orderable":false,"defaultContent":'', "render":function (data, type, row) {
						return '<input type="checkbox" data-type="select-checkbox" data-key="'+data.id+'">';
					}},
					{"data":null,"className":'details-control',"orderable":false,"defaultContent":''},
					{"data":"code_display", "orderable":true, "render":(data,type,row)=>{
						return destroy_tags(data);
					}},
					{"data":"list_name", "orderable":true, "render":(data,type,row)=>{
						return destroy_tags(data);
					}},
					{"data":"time", "orderable":true},
					{"data":"order_id", "className":"dt-right", "orderable":true},
					{"data":null, "orderable":false, "className":"dt-center", "render":(data, type, row)=>{
						return data.cvv === "" ? "" : '****';
					}},
					{"data":null, "orderable":true, "className":"dt-center", "render":(data, type, row)=>{
						if (data.aktiv === "2") return '<span style="color:red;">stolen</span>';
						return data.aktiv === "1" ? '<span style="color:green;">active</span>' : '<span style="color:grey;">inactive</span>';
					}},
					{"data":null, "orderable":true, "className":"dt-center", "render":(data, type, row)=>{
						let _stat = '';
						if (data.meta != "") {
							let metaObj = JSON.parse(data.meta);
							if (typeof metaObj['used'] !== "undefined") {
								if (metaObj.used.reg_request !== "") _stat = 'used';
							}
						}
						return _stat;
					}},
					{"data":null,"orderable":false,"defaultContent":'',"className":"buttons dt-right",
						"render": ( data, type, row )=>{
							return '<button class="button-secondary" data-type="edit">Edit</button> <button class="button-secondary" data-type="delete">Delete</button>';
						}
					}
				];
				let addition_column_offset = 0;
				if (_getOptions_isActivatedByKey('displayAdminAreaColumnBillingName')) {
					addition_column_offset++;
					table_columns.splice(3, 0, {
						"data":"_customer_name","orderable":false
					});
				}

				tabelle_codes_datatable = $(this.div_codes).find('#'+id_codes).DataTable({
					"search": {
						"search": typeof PARAS.code !== "undefined" ? encodeURIComponent(PARAS.code.trim()) : ''
					},
					"processing": true,
	    			"serverSide": true,
	    			"stateSave": false,
					"ajax": _requestURL('getCodes'),
	    			"order": [[ 4, "desc" ]],
	    			"columns":table_columns
				});
				tabelle_codes.on('click', 'button[data-type="edit"]', function (e) {
	        		let data = tabelle_codes_datatable.row( $(this).parents('tr') ).data();
	        		__showMaskCode(data);
				});
				tabelle_codes.on('click', 'button[data-type="delete"]', function (e) {
	        		let data = tabelle_codes_datatable.row( $(this).parents('tr') ).data();
	        		LAYOUT.renderYesNo('Do you want to delete?', 'Are you sure, you want to delete this code?<br><br><b>'+data.code+'</b>', function() {
	        			//let _data = {'code':data.code};
						let _data = {'id':data.id};
	        			_makePost('removeCode', _data, function(result) {
							tabelle_codes_datatable.ajax.reload();
						});
	        		});
				});
	    		$('#'+id_codes+' tbody').on('click', 'td.details-control', function () {
	    			function ___format(d) {
	    				let metaObj = [];
	    				if (d.meta) {
	    					metaObj = JSON.parse(d.meta);
    					}
	    				let div = $('<div/>');

						// hole das aktuelle Metaobj
						function __getData(_codeObj) {
							div.html(_getSpinnerHTML());
							_makeGet('getMetaOfCode',{'code':d.code}, dataMeta=>{
								if (_codeObj) { // um eine Aktualisierung in das codeObj aufzunehmen
									_codeObj.meta = JSON.stringify(dataMeta);
									updateCodeObject(d, _codeObj);
									metaObj = getCodeObjectMeta(d);
								}

								div.html("");
								d.meta = JSON.stringify(dataMeta);
								d.metaObj = dataMeta;

								let btn_grp = $('<div/>').addClass("btn-group").appendTo(div);
								$('<button>').html("Display QR with code").appendTo(btn_grp).on("click", e=>{
									let id = 'qrcode_'+d.code+'_'+time();
									let content = 'This QR image contains:<br><b>'+d.code+'</b><br><br><div id="'+id+'" style="text-align:center;"></div><script>jQuery("#'+id+'").qrcode("'+d.code+'");</script>';
									LAYOUT.renderInfoBox('QR with code', content);
								});
								if (typeof d.metaObj._QR != "undefined" && typeof d.metaObj._QR.directURL != "undefined" && d.metaObj._QR.directURL != "") {
									$('<button>').html("Display QR with URL").appendTo(btn_grp).on("click", e=>{
										let id = 'qrcode_url_'+d.code+'_'+time();
										let qr_content = d.metaObj._QR.directURL;
										let content = 'This QR image contains:<br><b>'+qr_content+'</b><br><br><div id="'+id+'" style="text-align:center;"></div><script>jQuery("#'+id+'").qrcode("'+qr_content+'");</script>';
										LAYOUT.renderInfoBox('QR with URL and code', content);
									});
								}
								div.append('<div/>');

								// male die Inhalte
								div.append('#'+d.id+'<br><b>Created:</b> '+d.time+'<br><b>Code:</b> '+d.code+'<br><b>Code Display:</b> '+d.code_display+'<br><b>Code Verification Value (CVV):</b> '+(d.cvv == "" ? '-' : d.cvv)+'<br><b>Aktiv:</b> '+(parseInt(d.aktiv,10) === 1?'True':'False'));
								div.append(_displayCodeDetails(d, metaObj, tabelle_codes_datatable));

								div.append("<h3>WooCommerce Order</h3>");
								if (!_getOptions_Versions_isActivatedByKey("is_wc_available")) {
									div.append($("<div>").css("color", "red").html("WooCommerce not found"));
								}
								div.append('<b>Order ID:</b> ' + (parseInt(d.order_id) === 0 ? '-' : '#'+d.order_id+' <a target="_blank" href="post.php?post='+d.order_id+'&action=edit">Show in WooCommerce Orders</a>'));
								if (typeof metaObj['woocommerce'] !== "undefined") {
									if (metaObj.woocommerce.order_id !== 0) {
										div.append($("<div>").html("<b>Order from:</b> ").append($('<span>').text(metaObj.woocommerce.creation_date)));
										div.append($("<div>").html("<b>Product Id:</b> ").append($('<span>').html(metaObj.woocommerce.product_id+' <a target="_blank" href="post.php?post='+encodeURIComponent(metaObj.woocommerce.product_id)+'&action=edit">Show Product</a>')));
									}
								}
								if (parseInt(d.order_id) > 0) {
									div.append($('<div style="margin-top:10px;">').html($('<button>').addClass("button-delete").html('Delete WooCommerce order info for this code').on("click", function(){
										if (confirm("Do you really want to remove your order information of this code '"+d.code_display+"'? This will remove the also the code(s) from the order! For the PREMIUM PLUGIN: It will only remove it from the position of the order. If you have in one order more than one item with serial codes, then it will only remove the code(s) from this item on the order. For the BASIC PLUGIN, it will remove all codes from all items on this order. Click OK to proceed the removal.")){
											_makeGet('removeWoocommerceOrderInfoFromCode', {'code':d.code}, _codeObj=>{
												//tabelle_codes_datatable.ajax.reload();
												__getData(_codeObj);
											});
										}
									})));
								}

								/*
								div.append("<h4>WooCommerce Ticket sale</h4>");
								div.append(_displayWCETicket(d, tabelle_codes_datatable));
								*/

								div.append("<h3>WooCommerce Purchase Restriction</h3>");
								if (typeof metaObj['wc_rp'] !== "undefined") {
									if (metaObj.wc_rp.order_id !== 0) {
										div.append($("<div>").html("<b>Used for Order ID:</b> ").append($('<span>').html('#'+metaObj.wc_rp.order_id+' <a target="_blank" href="post.php?post='+encodeURIComponent(metaObj.wc_rp.order_id)+'&action=edit">Show in WooCommerce Orders</a>')));
										div.append($("<div>").html("<b>Order from:</b> ").append($('<span>').text(metaObj.wc_rp.creation_date)));
										div.append($("<div>").html("<b>Product Id:</b> ").append($('<span>').html(metaObj.wc_rp.product_id+' <a target="_blank" href="post.php?post='+encodeURIComponent(metaObj.wc_rp.product_id)+'&action=edit">Show Product</a>')));
										div.append($('<div style="margin-top:10px;">').html($('<button>').addClass("button-delete").html('Delete WooCommerce purchase code info from order').on("click", function(){
											if (confirm("Do you really want to remove your the purchase code information from the order of this code '"+d.code_display+"'? This will remove the also the code(s) from the order items! This code can then be reused for purchases. Click OK to proceed the removal.")){
												_makeGet('removeWoocommerceRstrPurchaseInfoFromCode', {'code':d.code}, _codeObj=>{
													//tabelle_codes_datatable.ajax.reload();
													__getData(_codeObj);
												});
											}
										})));
									} else {
										div.append($("<div>").html("<b>Used for Order ID:</b> -"));
									}
								}

								div.append("<h3>Registered user</h3>");
								div.append(_displayRegisteredUserForCode(d, metaObj, tabelle_codes_datatable));

								div.append("<h3>IP list checked for this code</h3>");
								if (isPremium()) {
									div.append(PREMIUM.displayTrackedIPsForCode(d.code));
								} else {
									div.append(getLabelPremiumOnly());
								}

								if (isPremium() && PREMIUM.displayCodeDetailsAtEnd) div.append(PREMIUM.displayCodeDetailsAtEnd(d, tabelle_codes_datatable, metaObj));

								div.append("<hr>");
							});
						}
						__getData();
	    				return div;
	            	}

	        		var tr = $(this).closest('tr');
	        		var row = tabelle_codes_datatable.row( tr );
	        		if ( row.child.isShown() ) {
	            		// This row is already open - close it
	            		row.child.hide();
	            		tr.removeClass('shown');
	        		} else {
	            		// Open this row
	            		row.child( ___format(row.data()) ).show();
	            		tr.addClass('shown');
	        		}
				});
				cbf && cbf();
			}); // end getOptions
		} // render layout

		renderInfoBox(title, content) {
			let _options = {
				title: title,
		      	modal: true,
		      	minWidth: 400,
				minHeight: 200,
		      	buttons: [{text:'Ok', click:function(){
		      		$(this).dialog("close");
		      		$(this).html("");
		      	}}]
		    };
		    let dlg = $('<div/>').html(content);
			dlg.dialog(_options);
			return dlg;
		}
		renderFatalError(content) {
			return LAYOUT.renderInfoBox('Error', content);
		}
		renderYesNo(title, content, cbfYes, cbfNo) {
			let _options = {
				title: title,
		      	modal: true,
		      	minWidth: 400,
				minHeight: 200,
		      	buttons: [{text:'Yes', click:function(){
		      		$(this).dialog("close");
		      		$(this).html("");
		      		cbfYes && cbfYes(dlg);
		      	}},{text:'No', click:function(){
		      		$(this).dialog("close");
		      		$(this).html("");
		      		cbfNo && cbfNo();
		      	}}]
		    };
		    let dlg = $('<div/>').html(content);
			dlg.dialog(_options);
			return dlg;
		}
	}

	function _displayCodeDetails(codeObj, metaObj, tabelle) {
		let div = $('<div/>');
		function __getData(_codeObj) {
			if (_codeObj) { // um eine Aktualisierung in das codeObj aufzunehmen
				updateCodeObject(codeObj, _codeObj);
				metaObj = getCodeObjectMeta(codeObj);
			}

			div.html("");
			if (codeObj.meta !== "") {
				let metaObj = getCodeObjectMeta(codeObj);
				if (typeof metaObj.confirmedCount !== "undefined") {
					div.append($('<div/>').html('<b>Confirmed count:</b> '+metaObj.confirmedCount));
					if (metaObj.confirmedCount > 0 && metaObj.validation) {
						if (metaObj.validation.first_success != "") {
						div.append($('<div/>').html('<b>First successful validation at:</b> '+metaObj.validation.first_success));
						div.append($('<div/>').html('<b>First successful validation IP:</b> '+metaObj.validation.first_ip));
						}
						if (metaObj.validation.last_success != "" && metaObj.validation.last_success != metaObj.validation.first_success) {
							div.append($('<div/>').html('<b>Last successful validation at:</b> '+metaObj.validation.last_success));
							div.append($('<div/>').html('<b>Last successful validation IP:</b> '+metaObj.validation.last_ip));
						}
					}
				}
				let btngrp = $('<div style="margin-top:10px;">');
				if (typeof metaObj['used'] !== "undefined") {
					div.append("<h3>Code marked as used</h3>");
					if (metaObj.used.reg_request !== "") {
						div.append($("<div>").html("<b>Request from:</b> ").append($('<span>').text(metaObj.used.reg_request)));
						div.append($("<div>").html("<b>Request by wordpress user:</b> ").append($('<span>').text(metaObj.used.reg_userid)));
						if (metaObj.used._reg_username) div.append($("<div>").html("<b>Request by wordpress user:</b> ").append($('<span>').text(metaObj.used._reg_username)));
						div.append($("<div>").html("<b>Request from IP:</b> ").append($('<span>').text(metaObj.used.reg_ip)));

						btngrp.append($('<button/>').addClass("button-delete").html('Delete code used information').on("click", function(){
							if (confirm("Do you really want to remove the usage information of this code '"+codeObj.code_display+"'? This will also reset the 'Confirmed count' to 0.")){
								_makeGet('removeUsedInformationFromCode', {'code':codeObj.code}, _codeObj=>{
									//tabelle.ajax.reload();
									__getData(_codeObj);
								});
							}
						}));
					} else {
						div.append("Not used - still available");
					}

					btngrp.append($('<button/>').addClass("button-edit").html('Edit wordpress user information').on("click", function(){
						// display eingabe maske für userid
						function __showMask(){
							let _options = {
								title: 'Edit requested wordpress user',
								modal: true,
								minWidth: 400,
								minHeight: 200,
								buttons: [
									{
										id: 'okBtn',
										text: "Ok",
										click: function() {
											___submitForm();
										}
									},
									{
										text: "Cancel",
										click: function() {
											$( this ).dialog( "close" );
											$( this ).html('');
										}
									}
								]
							};
							let dlg = $('<div />');
							let form = $('<form />').appendTo(dlg);

							let elem_userid = $('<input type="number" min="0" value="'+metaObj.used.reg_userid+'" />');
							$('<div/>').css({"margin-top":"10px","margin-bottom": "15px","margin-right": "15px"})
								.html('Requested wordpress userid<br>')
								.append(elem_userid)
								.appendTo(form);

							dlg.append('<p>Changes will trigger the webhook, if activated.<br>The IP will be updated too. The requested date will only be changed, if it was not set already.</p>');
							dlg.dialog(_options);

							form.on("submit", function(event) {
								event.preventDefault();
								___submitForm();
							});
							function ___submitForm() {
								let reg_userid = intval(elem_userid.val().trim());
								dlg.html(_getSpinnerHTML());
								let _data = {"reg_userid":reg_userid};
								form[0].reset();
								_data.code = codeObj.code;
								$('#okBtn').remove();
								_makeGet('editUseridForUsedInformationFromCode', _data, _codeObj=>{
									//tabelle.ajax.reload();
									__getData(_codeObj);
									closeDialog(dlg);
								}, function() {
									closeDialog(dlg);
								});
							}
						} // ende __showMask
						__showMask();
					})); // end button-edit
				}
				div.append(btngrp);

				if (isPremium()) div.append(PREMIUM.displayCodeDetails(codeObj, tabelle, metaObj));
			} // endif codeObj.meta !== ""
		}
		__getData();
		return div;
	}

	function _displayWCETicket(codeObj, tabelle) {
		let div = $('<div/>');
		return div;
		function __getData(_codeObj) {
			if (_codeObj) { // um eine Aktualisierung in das codeObj aufzunehmen
				updateCodeObject(codeObj, _codeObj);
			}

			div.html("");
			metaObj = getCodeObjectMeta(codeObj);
			if (typeof codeObj.metaObj != "undefinded") {
				metaObj = codeObj.metaObj;
			}
			/*
			if (typeof metaObj['woocommerce'] !== "undefined" && metaObj.woocommerce.order_id !== 0 && typeof metaObj.wc_ticket !== "undefined") {
				if (metaObj.wc_ticket.set_by_admin > 0) {
					div.append($("<div>").html("<b>Ticket set by admin user:</b> ").append($('<span>').text(metaObj.wc_ticket._set_by_admin_username+' ('+metaObj.wc_ticket.set_by_admin+') '+metaObj.wc_ticket.set_by_admin_date)));
				}
				if (metaObj.wc_ticket.redeemed_date != '') {
					div.append($("<div>").html("<b>Redeemed at:</b> ").append($('<span>').text(metaObj.wc_ticket.redeemed_date)));
					div.append($("<div>").html("<b>Redeemed by wordpress userid:</b> ").append($('<span>').text(metaObj.wc_ticket.userid)));
					if (metaObj.wc_ticket._username) div.append($("<div>").html("<b>Redeemed by wordpress user:</b> ").append($('<span>').text(metaObj.wc_ticket._username)));
					div.append($("<div>").html("<b>IP while redeemed:</b> ").append($('<span>').text(metaObj.wc_ticket.ip)));
					if (metaObj.wc_ticket.redeemed_by_admin > 0) {
						div.append($("<div>").html("<b>Redeemed by admin user:</b> ").append($('<span>').text(metaObj.wc_ticket._redeemed_by_admin_username+' ('+metaObj.wc_ticket.redeemed_by_admin+')')));
					}
				} else {
					if (metaObj.wc_ticket.is_ticket != 0) {
						div.append($("<div>").html("<b>Ticket number: </b>"+codeObj.code_display));
					}
				}
				if (metaObj.wc_ticket.is_ticket == 1) {
					div.append($("<div>").html('<b>Ticket Page:</b> <a target="_blank" href="'+metaObj.wc_ticket._url+'">Open Ticket Detail Page</a>'));
					div.append($("<div>").html('<b>Ticket PDF:</b> <a target="_blank" href="'+metaObj.wc_ticket._url+'?pdf">Open Ticket PDF</a>'));
				}

				let btngrp = $('<div style="margin-top:10px;">').appendTo(div);
				if (metaObj.wc_ticket.is_ticket == 0) {
					$('<button>').html("Set as ticket sale").on("click", ()=>{
						if (confirm("Do you want to set this purchased serial code as a ticket sale?")) {
							_makeGet('setWoocommerceTicketForCode', {'code':codeObj.code}, _codeObj=>{
								__getData(_codeObj);
							});
						}
					}).appendTo(btngrp);
				}
				let btn_redeem = $('<button>').addClass("button-delete").html('Redeem ticket').on("click", ()=>{
					let reg_userid = (metaObj.user && metaObj.user.reg_userid) ? metaObj.user.reg_userid : 0;
					if (confirm("Do you really want to redeem the ticket code '"+codeObj.code_display+"'? Click OK to redeem the ticket.")){
						let userid = prompt('Optional. You can enter a userid you redeem the ticket for', reg_userid);
						_makeGet('redeemWoocommerceTicketForCode', {'code':codeObj.code, 'userid':userid}, _codeObj=>{
							__getData(_codeObj);
						});
					}
				}).appendTo(btngrp);
				if (metaObj.wc_ticket.is_ticket == 0 || metaObj.wc_ticket.redeemed_date != "") {
					btn_redeem.attr("disabled", true);
				}

				let btn_unredeem = $('<button>').addClass("button-delete").html('Delete redeem information').on("click", ()=>{
					if (confirm("Do you really want to remove the information that the ticket code '"+codeObj.code_display+"' is redeemed? Click OK to un-redeem the ticket and allow your customer to use the ticket again.")){
						_makeGet('removeRedeemWoocommerceTicketForCode', {'code':codeObj.code}, _codeObj=>{
							__getData(_codeObj);
						});
					}
				}).appendTo(btngrp);
				if (metaObj.wc_ticket.is_ticket == 0 || metaObj.wc_ticket.redeemed_date == "") {
					btn_unredeem.attr("disabled", true);
				}
				if (metaObj.wc_ticket.is_ticket == 1 && metaObj.wc_ticket.redeemed_date == "") {
					$('<button>').addClass("button-delete").html("Unset Ticket").on("click", ()=>{
						if (confirm("Do you really want to remove the ticket info from this code? The WooCommerce sale will be set and you need to remove it manually.")) {
							_makeGet('removeWoocommerceTicketForCode', {'code':codeObj.code}, _codeObj=>{
								__getData(_codeObj);
							});
						}
					}).appendTo(btngrp);
				}
			}
			*/
		}
		__getData();
		return div;
	}

	function _displayRegisteredUserForCode(codeObj, metaObj, tabelle) {
		let div = $('<div/>');
		function __getData(_codeObj) {
			if (_codeObj) { // um eine Aktualisierung in das codeObj aufzunehmen
				updateCodeObject(codeObj, _codeObj);
				metaObj = getCodeObjectMeta(codeObj);
			}
			div.html("");
			let btngrp = $('<div style="margin-top:10px;">');
			if (typeof codeObj.meta !== "undefined" && codeObj.meta !== "") {
				let metaObj = getCodeObjectMeta(codeObj);
				if (metaObj.user.reg_request !== "") {
					div.append($("<div>").html("<b>Register value:</b> ").append($('<span>').text(metaObj.user.value)));
					div.append($("<div>").html("<b>Register by wordpress userid:</b> ").append($('<span>').text(metaObj.user.reg_userid)));
					if (metaObj.user._reg_username) div.append($("<div>").html("<b>Register by wordpress user:</b> ").append($('<span>').text(metaObj.user._reg_username)));
					div.append($("<div>").html("<b>Request from:</b> ").append($('<span>').text(metaObj.user.reg_request)));
					div.append($("<div>").html("<b>Request from IP:</b> ").append($('<span>').text(metaObj.user.reg_ip)));
					btngrp.append($('<button/>').addClass("button-delete").html('Delete registered user information').on("click", function(){
						if (confirm("Do you really want to remove the registered user value of this code '"+codeObj.code_display+"'?")){
							// sende delete user from code operation zum server
							div.html(_getSpinnerHTML());
							_makeGet('removeUserRegistrationFromCode', {'code':codeObj.code}, _codeObj=>{
								//tabelle.ajax.reload();
								__getData(_codeObj);
							});
						}
					}));
				} else {
					div.append("No registration to this code done");
				}

				btngrp.append($('<button/>').addClass("button-edit").html('Edit registered user information').on("click", function(){
					// display eingabe maske für value und userid
					function __showMask(){
						let _options = {
							title: 'Edit registered user',
							modal: true,
							minWidth: 400,
							minHeight: 200,
							buttons: [
								{
									id: 'okBtn',
									text: "Ok",
									click: function() {
										___submitForm();
									}
								},
								{
									text: "Cancel",
									click: function() {
										$( this ).dialog( "close" );
										$( this ).html('');
									}
								}
							]
						};
						let dlg = $('<div />');
						let form = $('<form />').appendTo(dlg);

						let elem_value = $('<input type="text" value="'+metaObj.user.value+'" />');
						$('<div/>').css({"margin-top":"10px","margin-bottom": "15px","margin-right": "15px"})
							.html('Registered value<br>')
							.append(elem_value)
							//.append('<br><i>If CVV is set, then your user will be asked to enter also the CVV to check the serial code.</i>')
							.appendTo(form);
						let elem_userid = $('<input type="number" min="0" value="'+metaObj.user.reg_userid+'" />');
						$('<div/>').css({"margin-top":"10px","margin-bottom": "15px","margin-right": "15px"})
							.html('Registered wordpress userid<br>')
							.append(elem_userid)
							.appendTo(form);

						dlg.append('<p>Changes will trigger the webhook, if activated.<br>The IP will updated too. The registered date will only be changed, if it was not set already.</p>');
						dlg.dialog(_options);

						form.on("submit", function(event) {
							event.preventDefault();
							___submitForm();
						});
						function ___submitForm() {
							let reg_userid = intval(elem_userid.val().trim());
							let reg_value = elem_value.val().trim();
							dlg.html(_getSpinnerHTML());
							let _data = {"value":reg_value, "reg_userid":reg_userid};
							form[0].reset();
							_data.code = codeObj.code;
							$('#okBtn').remove();
							_makeGet('editUseridForUserRegistrationFromCode', _data, _codeObj=>{
								//tabelle.ajax.reload();
								__getData(_codeObj);
								closeDialog(dlg);
							}, function() {
								closeDialog(dlg);
							});
						}
					} // ende __showMask
					__showMask();
				})); // end button-edit
				div.append(btngrp);
				if (isPremium()) div.append(PREMIUM.displayRegisteredUserForCode(codeObj, tabelle, metaObj));
			} // endif typeof codeObj.meta !== "undefined" && codeObj.meta !== ""
		}
		__getData();
		return div;
	}

	function addStyleCode(content) {
		let c = document.createElement('style');
		c.innerHTML = content;
		document.getElementsByTagName("head")[0].appendChild(c);
	}
	function addStyleTag(url, id, onloadfkt, attrListe, loadLatest) {
	  var script  = document.createElement('link');
	  script.type = 'text/css';
	  script.rel = "stylesheet";
	  let myId = id;
	  if (!myId) myId = url;
		if (document.getElementById(id) && document.getElementById(id).src === url) {
			onloadfkt && onloadfkt();
			return; // prevent re-adding the same tag
		}
	  script.id = id;
	  if (attrListe) for(var attr in attrListe) script.setAttribute(attr, attrListe[attr]);
	  script.href = url;
	  if (loadLatest) script.href += '?t='+new Date().getTime();
	  if (typeof onloadfkt !== "undefined") script.onload = onloadfkt;
	  document.getElementsByTagName("head")[0].appendChild(script);
	}

	function addScriptTag(url, id, onloadfkt, attrListe, loadLatest) {
	  	var head    = document.getElementsByTagName("head")[0];
	  	var script  = document.createElement('script');
	  	script.type = 'text/javascript';
	  	let myId = id;
	  	if (!myId) myId = url;
		if (document.getElementById(id) && document.getElementById(id).src === url) {
			onloadfkt && onloadfkt();
			return; // prevent re-adding the same tag
		}
	  script.id = id;
	  if (attrListe) for(var attr in attrListe) script.setAttribute(attr, attrListe[attr]);
	  script.src = url;
	  if (loadLatest) script.src += '?t='+new Date().getTime();
	  if (typeof onloadfkt !== "undefined") script.onload = onloadfkt;
	  head.appendChild(script);
	}

	function getLabelPremiumOnly() {
		return '[<a href="https://vollstart.de/serial-codes-validator-premium/">PREMIUM ONLY</a>]';
	}

	function _getSpinnerHTML() {
		return '<span class="lds-dual-ring"></span>';
	}

	function _loadingJSDatatables(cbf) {
		let loaded = {};
		addStyleCode('table.dataTable tr.shown td.details-control {background: url('+myAjax._plugin_home_url+'/img/details_close.png) no-repeat center center;}td.details-control {background: url('+myAjax._plugin_home_url+'/img/details_open.png) no-repeat center center;cursor: pointer;}');
		addStyleTag(myAjax._plugin_home_url+'/3rd/datatables.min.css', 'jquery_dataTables', function() {
			loaded['1'] = true;
			if (loaded['2']) {
				cbf && cbf();
			}
		}, {'crossorigin':"anonymous"});
		addScriptTag(myAjax._plugin_home_url+"/3rd/datatables.min.js", 'jquery_dataTables', function() {
			loaded['2'] = true;
			if (loaded['1']) {
				cbf && cbf();
			}
		}, {'crossorigin':"anonymous", "charset":"utf8"});
	}

	function isPremium() {
		return myAjax._isPremium == "1" || myAjax._isPremium === true;
	}

	var BulkActions = {
		'codes': {
			'delete': (selectedElems, tabelle_codes_datatable) => {
	    		LAYOUT.renderYesNo('Delete all selected codes?', 'Are you sure, you want to delete all selected codes?<br><br>'+selectedElems.length+' codes will be deleted.', function() {
	    			let _data = {'ids':[]};
	    			selectedElems.forEach(v=>{
	    				_data.ids.push($(v).attr("data-key"));
	    			});
	    			_makePost('removeCodes', _data, function(result) {
						tabelle_codes_datatable.ajax.reload();
					});
	    		});
			}
		}
	}

	function getHelperFunktions() {
		return {
			_getSpinnerHTML:_getSpinnerHTML,
			_makePost:_makePost,
			_makeGet:_makeGet,
			_requestURL:_requestURL,
			_getLAYOUT:function(){ return LAYOUT;},
			_getDIV:function(){ return DIV;},
			_BulkActions:BulkActions,
			_closeDialog:closeDialog,
			_OPTIONS:function(){ return OPTIONS;},
			_updateCodeObject:updateCodeObject,
			_getCodeObjectMeta:getCodeObjectMeta,
			_getDataLists:getDataLists,
			_basics_ermittelURLParameter:basics_ermittelURLParameter
		};
	}

	function init() {
		addStyleCode('.lds-dual-ring {display:inline-block;width:64px;height:64px;}.lds-dual-ring:after {content:" ";display:block;width:46px;height:46px;margin:1px;border-radius:50%;border:5px solid #fff;border-color:#2e74b5 transparent #2e74b5 transparent;animation:lds-dual-ring 0.6s linear infinite;}@keyframes lds-dual-ring {0% {transform: rotate(0deg);}100% {transform: rotate(360deg);}}');
		addStyleTag(myAjax._plugin_home_url+'/css/styles_backend.css');

    	DIV = $('#'+myAjax.divId);
    	DIV.html(_getSpinnerHTML());
    	LAYOUT = new Layout();
		function _init() {
	 		_loadingJSDatatables(function() {
				if (typeof PARAS.display !== "undefined" && PARAS.display == 'options') {
					_displayOptionsArea();
				} else if (typeof PARAS.display !== "undefined" && PARAS.display == 'support') {
					_displaySupportInfoArea();
				} else {
					LAYOUT.renderAdminPageLayout();
				}
			});
		}

    	if (isPremium() && myAjax._premJS !== "") {
    		addScriptTag(myAjax._premJS, null, function() {
    			PREMIUM = new sngmbhSerialcodesValidatorPremium(myAjax, getHelperFunktions(), this);
    			_init();
    		});
    	} else {
			_init();
    	}

	}
	if (!doNotInit) init();
	return {
		init: init,
		form_fields_serial_format:_form_fields_serial_format
	};
}
if (typeof Ajax_sngmbhSerialcodesValidator !== "undefined") {
	window.sngmbhSerialcodesValidator_backend = sngmbhSerialcodesValidator(Ajax_sngmbhSerialcodesValidator);
}