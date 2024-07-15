// wc_cashapp_qrcode // wp_localize_script object
console.debug("wc_cashapp_qrcode", wc_cashapp_qrcode);

jQuery(document).ready(function ($) {
	QRCodeGenerator($("#wc_cashapp_qrcode"), wc_cashapp_qrcode);
	$("body").on("updated_checkout", function () {
		QRCodeGenerator(
			$("#wc_cashapp_qrcode"),
			wc_cashapp_qrcode
		);
	});
});