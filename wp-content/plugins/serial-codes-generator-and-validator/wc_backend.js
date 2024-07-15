function SngmbhSerialcodesValidator_WC_backend($, phpObject) {
	let _self = this;
	let _sngmbhSerialcodesValidator;

	function renderFormatterFields() {
		let hiddenValueField = $('input[data-id="'+phpObject.formatterInputFieldDataId+'"]');
		let formatterValues = $(hiddenValueField).val();

		if (formatterValues != "") {
			try {
				formatterValues = JSON.parse(formatterValues);
			} catch (e) {
				//console.log(e);
			}
		}

		let serialCodeFormatter = _sngmbhSerialcodesValidator.form_fields_serial_format($('#'+phpObject._divAreaId));
		serialCodeFormatter.setNoNumberOptions();
		serialCodeFormatter.setFormatterValues(formatterValues);
		serialCodeFormatter.setCallbackHandle(_formatterValues=>{
			$(hiddenValueField).val(JSON.stringify(_formatterValues));
		});
		serialCodeFormatter.render();

		$(hiddenValueField).val(JSON.stringify(serialCodeFormatter.getFormatterValues()));
	}

	function _addHandlerToTheCodeFields() {
		$('body').find('button[data-id="'+phpObject.prefix+'btn_download_serial_infos"]').prop('disabled', phpObject.is_serial != "1").on('click', event=>{
			event.preventDefault();
			let btn = event.target;
			$(btn).prop("disabled", true);
			let url = phpObject.ajaxurl;
			let _data = {
				action:encodeURIComponent(phpObject.action),
				nonce:encodeURIComponent(phpObject.nonce),
				a_sngmbh:'downloadSerialInfosOfProduct',
				"data[product_id]":encodeURIComponent(phpObject.product_id)
			};
			$.get( url, _data, function( response ) {
				if (!response.success) {
					alert(response);
				} else {
					let ticket_infos = response.data.serial_infos;
					let product = response.data.product;
					let w = window.open('about:blank');
					addStyleCode('.lds-dual-ring {display:inline-block;width:64px;height:64px;}.lds-dual-ring:after {content:" ";display:block;width:46px;height:46px;margin:1px;border-radius:50%;border:5px solid #fff;border-color:#2e74b5 transparent #2e74b5 transparent;animation:lds-dual-ring 0.6s linear infinite;}@keyframes lds-dual-ring {0% {transform: rotate(0deg);}100% {transform: rotate(360deg);}}', w.document);
					w.document.body.innerHTML += _getSpinnerHTML();
					window.setTimeout(()=>{
						let output = $('<div style="margin-left:2.5cm;margin-top:1cm;">');
						output.append($('<h3>').html('Serial Infos for Product "'+product.name+'"'));
						for(let i=0;i<ticket_infos.length;i++) {
							let ticket_info = ticket_infos[i];
							let metaObj = getCodeObjectMeta(ticket_info);
							let elem = $('<div>').appendTo(output);
							elem.append($('<h4>').html('#'+(i+1)+'. '+ticket_info.code_display));
							elem.append("Order Id: "+metaObj.woocommerce.order_id+"<br>");
							if (ticket_info._customer_name) {
								elem.append(ticket_info._customer_name);
							}
							elem.append($('<div style="margin-top:10px;margin-bottom:15px;">').qrcode(ticket_info.code));
							if (typeof metaObj._QR != "undefined" && typeof metaObj._QR.directURL != "undefined" && metaObj._QR.directURL != "") {
								elem.append($('<h5>').html("With your QR code value"));
								elem.append($('<div style="margin-top:10px;margin-bottom:15px;">').qrcode(metaObj._QR.directURL));
							}
							elem.append('<hr>');
							elem.appendTo(output);
						}
						$(w.document.body).html(output);
						$(btn).prop("disabled", false);
						w.print();
					}, 250);
				}
			});
		});
	}

	function getCodeObjectMeta(codeObj) {
		if (!codeObj.metaObj) codeObj.metaObj = JSON.parse(codeObj.meta);
		return codeObj.metaObj;
	}

	function addStyleCode(content, d) {
		if (!d) d = document;
		let c = d.createElement('style');
		c.innerHTML = content;
		d.getElementsByTagName("head")[0].appendChild(c);
	}

	function _getSpinnerHTML() {
		return '<span class="lds-dual-ring"></span>';
	}

	function starten() {
		_sngmbhSerialcodesValidator = sngmbhSerialcodesValidator(phpObject, true);
		if (phpObject.scope && phpObject.scope == "order") {
		} else {
			renderFormatterFields();
			_addHandlerToTheCodeFields();
		}
	}

	function init() {
		if (typeof sngmbhSerialcodesValidator === "undefined") {
			$.ajax({
				url: phpObject._backendJS,
				dataType: 'script',
				success: function( data, textStatus, jqxhr ) {
					starten();
				}
			});
		} else {
			starten();
		}
	}

	init();
}
(function($){
 	$(document).ready(function(){
 		SngmbhSerialcodesValidator_WC_backend($, Ajax_sngmbhSerialcodesValidator_wc);
 	});
})(jQuery);