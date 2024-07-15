// wc_cash_app_pay_object // wp_localize_script object
console.debug(
	"wc_cash_app_pay_object",
	wc_cash_app_pay_object
	// wc_cash_app_pay_object?.isPro
);

jQuery(document).ready(function ($) {
	$("body").on("updated_checkout", async function () {
		await loadCashAppPay();
	});
});

var checkout_url = wc_cash_app_pay_object.checkout_url;
var cart = wc_cash_app_pay_object.cart;
var cart_items = wc_cash_app_pay_object.cart_items;
var amount = wc_cash_app_pay_object.amount;
var lineItems = wc_cash_app_pay_object.lineItems ?? [];

let initCashAppPay;
let cashAppPay;
var referenceId = Math.random().toString(35).slice(2);

async function buildPaymentRequest(payments, amount) {
	try {
		// https://developer.squareup.com/reference/sdks/web/payments/objects/PaymentRequestOptions
		// const paymentRequestOptions = {
		// 	"countryCode": "US",
		// 	"currencyCode": "USD",
		// 	"lineItems": [
		// 	  {
		// 		"amount": "22.15",
		// 		"label": "Item to be purchased",
		// 		"id": "SKU-12345
		// 		"imageUrl": "https://url-cdn.com/123ABC"
		// 		"pending": true
		// 		"productUrl": "https://my-company.com/product-123ABC"
		// 	  }
		// 	],
		// 	"taxLineItems": [
		// 	  {
		// 		"label": "State Tax",
		// 		"amount": "8.95",
		// 		"pending": true
		// 	  }
		// 	],
		// 	"discounts": [
		// 	  {
		// 		"label": "Holiday Discount",
		// 		"amount": "5.00",
		// 		"pending": true
		// 	  }
		// 	],
		// 	"requestBillingContact": false,
		// 	"requestShippingContact": false,
		// 	"shippingOptions": [
		// 	  {
		// 		"label": "Next Day",
		// 		"amount": "15.69",
		// 		"id": "1"
		// 	  },
		// 	  {
		// 		"label": "Three Day",
		// 		"amount": "2.00",
		// 		"id": "2"
		// 	  }
		// 	],
		// 	// pending is only required if it's true.
		// 	"total": {
		// 	  "amount": "41.79",
		// 	  "label": "Total",
		// 	},
		//  };
		const paymentRequest = await payments.paymentRequest({
			countryCode: "US",
			currencyCode: "USD",
			total: {
				amount: amount,
				label: "Total",
			},
			lineItems,
			shippingContact: {
				givenName: document.getElementById("billing_first_name")?.value ?? "",
				familyName: document.getElementById("billing_last_name")?.value ?? "",
				addressLines: [
					document.getElementById("billing_address_1")?.value ?? "",
					document.getElementById("billing_address_2")?.value ?? "",
				],
				city: document.getElementById("billing_city")?.value ?? "",
				state: document.getElementById("billing_state")?.value ?? "",
				postalCode: document.getElementById("billing_postcode")?.value ?? "",
				countryCode: document.getElementById("billing_country")?.value ?? "",
				email: document.getElementById("billing_email")?.value ?? "",
				phone: document.getElementById("billing_phone")?.value ?? "",
			},
		});
		console.debug(paymentRequest);
		return paymentRequest;
	} catch (error) {
		console.error(error);
		var error_message = typeof error === "object" ? error.message : error;
		wc_cash_app_pay_displayMessage(
			"<p>Unable to build Cash App Pay.<br>" + error_message + "</p>"
		);
		return;
	}
}

async function initializeCashAppPay(payments, amount) {
	try {
		// console.debug(amount, payments);
		const paymentRequest = await buildPaymentRequest(payments, amount);
		if (!paymentRequest) throw new Error("buildPaymentRequest error");
		cashAppPay = await payments.cashAppPay(paymentRequest, {
			redirectURL: window.location.href,
			referenceId: referenceId,
		});
		if (!cashAppPay) throw new Error("Initialization error");
		document.getElementById("cash-app-spinner").style.display = "none";

		console.debug(cashAppPay);
		// const cashAppPayButtonTarget = document.getElementById("cash-app-pay");
		// await cashAppPay.attach(cashAppPayButtonTarget);
		await cashAppPay.attach("#cash-app-pay");
		return cashAppPay;
	} catch (error) {
		console.error(error);
		var error_message = typeof error === "object" ? error.message : error;
		wc_cash_app_pay_displayMessage(
			"<p>Unable to initialize Cash App Pay.<br>" + error_message + "</p>"
		);
		return;
	}
}

function tokenizeCashApp(cashAppPayEl) {
	try {
		cashAppPayEl.addEventListener("ontokenization", (event) => {
			console.debug("ontokenization", event);
			const { tokenResult, error } = event.detail;
			if (error) {
				console.error(error);
				var error_message = typeof error === "object" ? error.message : error;
				throw "<p>Cash App Pay Tokenization Error<br>" + error_message + "</p>";
			} else if (tokenResult.status === "OK") {
				console.log("tokenResult", tokenResult);
				document.getElementById("payment_token").value = tokenResult.token;
				document.getElementById("cash-app-spinner").style.display = "none";
				// // "<p>Click on Place Order to finish your order. <strong>Order in progress...</strong></p>"
				// document
				// 	.getElementById("cash-app-pay")
				// 	.after(
				// 		"\n\n Click on Place Order to finish your order. Order in progress..."
				// );
				document.getElementById("wc-cash-app-pay-top").innerText =
					"Click on Place Order to finish your order. Order in progress...";
			} else {
				let errorMessage = `Tokenization failed with status: ${tokenResult.status}`;
				if (tokenResult.errors) {
					errorMessage += ` and errors: ${JSON.stringify(tokenResult.errors)}`;
				}
				console.error(errorMessage);
				throw (
					"<p>Cash App Pay failure after tokenization<br>" +
					errorMessage +
					"</p>"
				);
			}
		});
	} catch (error) {
		console.error(error);
		wc_cash_app_pay_displayMessage(error);
	}
}

async function loadCashAppPay() {
	var amount = document.getElementById("amount")?.value ?? "";
	var environment = document.getElementById("sq_environment")?.value ?? "";
	let locationId = document.getElementById("sq_location")?.value ?? "";

	if (!amount || !environment || !locationId) {
		wc_cash_app_pay_displayMessage(
			"<p>Missing required parameters. Finish setting up properly first</p>"
		);
		console.error("Missing required parameters");
		return;
	}

	if (!window.Square) {
		wc_cash_app_pay_displayMessage("<p>square.js failed to load properly</p>");
		console.error("square.js failed to load properly");
		return;
	}

	let payments;
	const sq_appId = "sq0idp-ZXTjnM5LRS7w5XE9MaRzvQ";

	try {
		console.log(sq_appId, locationId);
		payments = Square.payments(sq_appId, locationId);
		// console.debug("payments", payments);
	} catch (error) {
		console.error("window.quare.payments Error:", error);
		var error_message = typeof error === "object" ? error.message : error;
		wc_cash_app_pay_displayMessage(
			"<p>Cash App Pay Tokenization failed<br>" + error_message + "</p>"
		);
		return;
	}

	try {
		initCashAppPay = await initializeCashAppPay(payments, amount);
	} catch (error) {
		console.error("initialize CashAppPay Error:", error);
		var error_message = typeof error === "object" ? error.message : error;
		wc_cash_app_pay_displayMessage(
			"<p>Initializing Cash App Pay failed<br>" + error_message + "</p>"
		);
		return;
	}
	// console.info('initCashAppPay', typeof initCashAppPay, initCashAppPay?.status);
	if (typeof initCashAppPay === "object" && initCashAppPay.status) {
		// console.log("Cash App Pay is ready", initCashAppPay);
		try {
			tokenizeCashApp(initCashAppPay);
		} catch (error) {
			console.error(error);
			var error_message = typeof error === "object" ? error.message : error;
			wc_cash_app_pay_displayMessage(
				"<p>Cash App Pay failed tokenization.<br>" + error_message + "</p>"
			);
			return;
		}
	} else {
		console.error("initCashAppPay", typeof initCashAppPay, initCashAppPay);
		wc_cash_app_pay_displayMessage(
			"<p>Unable to load Cash App Pay.<br>Please refresh or try another payment method</p>"
		);
	}
}

function wc_cash_app_pay_displayMessage(message) {
	document.getElementById("cash-app-spinner").style.display = "none";
	document.getElementById("payment-status-container").innerHTML = message;
}