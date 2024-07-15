<?php
include(plugin_dir_path(__FILE__)."init_file.php");
final class vollstart_Ticket {
	private $MAIN;

	private $request_uri;
	private $parts = null;

	private $codeObj;
	private $order;

	private $isScanner = null;

	private $redeem_succesfully = false;
	private $onlyLoggedInScannerAllowed = false;

	public static function Instance($request_uri) {
		static $inst = null;
        if ($inst === null) {
            $inst = new vollstart_Ticket($request_uri);
        }
        return $inst;
	}

	public function __construct($request_uri) {
		global $sngmbhSerialcodesValidator;
		$this->MAIN = $sngmbhSerialcodesValidator;
		$this->request_uri = trim($request_uri);
		$this->onlyLoggedInScannerAllowed = $this->MAIN->getOptions()->isOptionCheckboxActive('wcTicketOnlyLoggedInScannerAllowed') ? true : false;
		load_plugin_textdomain('sngmbh-serial-codes-validator', false, 'sngmbh-serial-codes-validator/languages');
	}

	/**
	 * has to be explicitly called
	 */
	public function initFilterAndActions() {
		add_filter('query_vars', function( $query_vars ){
		    $query_vars[] = 'symbol';
		    return $query_vars;
		});
		add_filter("pre_get_document_title", function($title){
			return __("Ticket Info", "sngmbh-serial-codes-validator");
		}, 2000);
		add_action('wp_head', function() {
			$vollstart_Ticket = vollstart_Ticket::Instance($_SERVER["REQUEST_URI"]);
			$vollstart_Ticket->addMetaTags();
		}, 1);
		add_action('template_redirect', function() {
			$vollstart_Ticket = vollstart_Ticket::Instance($_SERVER["REQUEST_URI"]);
			$vollstart_Ticket->output();
			exit;
		});
	}

	/** falls man direkt aufrufen muss. Wie beim /ticket/scanner/ */
	public function renderPage() {
		$vollstart_Ticket = vollstart_Ticket::Instance($_SERVER["REQUEST_URI"]);
		$vollstart_Ticket->output();
	}

	private function getCore() {
		return $this->MAIN->getCore();
	}
	private function getBase() {
		return $this->MAIN->getBase();
	}

	public static function getOptionsRawObject() {
		// called from outside to have the ticket options
		$options = [];
		$options[] = [
				'key'=>'h12',
				'label'=>"Woocommerce ticket sale",
				'desc'=>"You can assign a code list to a product and this will generate or re-use a code from this code list as a ticket serial code It will be printed on the purchase information to the customer.",
				'type'=>"heading"
				];
		$options[] = ['key'=>'wcTicketOnlyLoggedInScannerAllowed', 'label'=>'Only allow adminstrator to open the ticket scanner', 'desc'=>'If active, only logged-in user can scan a code. It is also testing if the user is an administrator.', 'type'=>'checkbox'];
		$options[] = ['key'=>'wcTicketDontShowRedeemBtnOnTicket','label'=>"Do not show the redeem button on the ticket for the client.",'desc'=>"If active, it will not add the self-redeem button on the ticket detail view.",'type'=>"checkbox", 'def'=>"", 'additional'=>[]];
		$options[] = ['key'=>'wcTicketPrefixTextCode', 'label'=>"Text that will be added before the ticket code on the PDF invoice, order table and order details.", 'desc'=>"If left empty, default will be 'Ticket number:'", 'type'=>"text", 'def'=>"Ticket number:", 'additional'=>[], 'isPublic'=>false];
		$options[] = ['key'=>'wcTicketDisplayShortDesc', 'label'=>"Display the short description of the product on the ticket.", 'desc'=>"If active, it will be printed on the ticket detail view.", 'type'=>"checkbox", 'def'=>"", 'additional'=>[]];
		$options[] = ['key'=>'wcTicketDontDisplayCustomer', 'label'=>"Hide the customer name and address on the ticket.", 'desc'=>"If active, it will not print the customer information on the ticket detail view.", 'type'=>"checkbox", 'def'=>"", 'additional'=>[]];
		$options[] = ['key'=>'wcTicketDontDisplayPayment', 'label'=>"Hide the payment method on the ticket.", 'desc'=>"If active, it will not print the payment details on the ticket detail view.", 'type'=>"checkbox", 'def'=>"", 'additional'=>[]];
		$options[] = ['key'=>'wcTicketDontDisplayPDFButtonOnDetail', 'label'=>"Hide the PDF Download button on ticket detail page.", 'desc'=>"If active, it will not display the PDF download button on the ticket detail view. But the PDF can still be generated with the URL.", 'type'=>"checkbox", 'def'=>""];
		$options[] = ['key'=>'wcTicketDontDisplayPDFButtonOnMail', 'label'=>"Hide the PDF Download button/link on purchase order email.", 'desc'=>"If active, it will not display the PDF download option on the purchase email to the client. But the PDF can still be generated with the URL.", 'type'=>"checkbox", 'def'=>""];
		$options[] = ['key'=>'wcTicketDontDisplayDetailLinkOnMail', 'label'=>"Hide the ticket detail page link on purchase order email.", 'desc'=>"If active, it will not display the URL to the ticket detail page on the purchase email to the client.", 'type'=>"checkbox", 'def'=>""];
		$options[] = ['key'=>'wcTicketLabelPDFDownload', 'label'=>"Text that will be added as the PDF Ticket download label.", 'desc'=>"If left empty, default will be 'Download PDF Ticket'", 'type'=>"text", 'def'=>"Download PDF Ticket"];
		$options[] = ['key'=>'wcTicketCompatibilityMode', 'label'=>"Compatibility mode for ticket URL", 'desc'=>"If your theme is showing the 404 title or the ticket is not rendered at all, then you can try to use this compatibility mode. If active, then the URL /ticket/XYZ will be /ticket/?code=XYZ URL for the link to the ticket detail and ticket PDF page. Some themes causing issues with the normal mode.", 'type'=>"checkbox"];

		for($a=0;$a<count($options);$a++) {
			$options[$a]['label'] .= ' <span style="color:red;">DEPRICATED - please do not use the ticket feature anymore.</span>';
		}

		return $options;
	}

	private function isScanner() {
		// /wp-content/plugins/serial-codes-generator-and-validator/ticket/scanner/
		if ($this->isScanner == null) {

			if ($this->onlyLoggedInScannerAllowed) {
				if (in_array('administrator',  wp_get_current_user()->roles)) {

				} else {
					return false;
				}
			}

			$ret = false;
			$teile = explode("/", $this->request_uri);
			$teile = array_reverse($teile);
			if (count($teile) > 1) {
				if (substr(strtolower(trim($teile[1])), 0, 7) == "scanner") $ret = true;
			}
			$this->isScanner = $ret;
		}
		return $this->isScanner;
	}

	private function getText($text) {
		return __($text, "sngmbh-serial-codes-validator");
	}

	private function getOrder() {
		if ($this->order != null) return $this->order;
		$order = wc_get_order( $this->getParts()["order_id"] );
		if (!$order) throw new Exception("#8009 ".$this->getText("Order not found"));
		$this->order = $order;
		return $order;
	}

	private function getParts() {
		if ($this->parts == null) {
			if ($this->isScanner()) {
				if (!SNGMBH::issetRPara('code')) {
					throw new Exception("#8007 ticket number not provided");
				} else {
					$uri = trim(SNGMBH::getRequestPara('code', $def=''));
					$this->parts =  $this->getCore()->getTicketURLComponents($uri);
				}
			} else {
				$this->parts =  $this->getCore()->getTicketURLComponents($this->request_uri);
			}
		}
		return $this->parts;
	}

	private function getCodeObj($dontFail=false){
		global $sngmbhSerialcodesValidator;
		if ($this->codeObj != null) return $this->codeObj;
		$codeObj = $this->getCore()->retrieveCodeByCode($this->getParts()['code']);
		if ($codeObj['aktiv'] == 2) throw new Exception("#8005 ".$this->getText("Ticket is STOLEN"));
		if ($codeObj['aktiv'] != 1) throw new Exception("#8006 ".$this->getText("Ticket is not valid"));
		$metaObj = $this->getCore()->encodeMetaValuesAndFillObject($codeObj['meta'], $codeObj);
		$codeObj["metaObj"] = $metaObj;

		// check ob order_id stimmen
		if ($this->getParts()['order_id'] != $codeObj['order_id']) throw new Exception("#8001 ".$this->getText("Ticket number is wrong"));
		// check idcode
		if ($this->getParts()['idcode'] != $metaObj['wc_ticket']['idcode']) throw new Exception("#8006 ".$this->getText("Ticket number is wrong"));
		// check ob serial ein ticket ist
		if ($metaObj['wc_ticket']['is_ticket'] != 1) throw new Exception("#8002 ".$this->getText("Ticket is not valid"));
		// check ob order bezahlt ist
		$order = $this->getOrder();
		if (!$dontFail && !$this->isPaid($order)) throw new Exception("#8003 ".$this->getText("Ticket payment is not completed"));

		$this->codeObj = $codeObj;
		return $codeObj;
	}

	private function isPaid($order) {
		return SNGMBH::isOrderPaid($order);
	}

	private function outputTicketScanner() {
		echo '<center>';
		echo '<h3>'.$this->getText('Ticket Scanner').' - DEPRICATED</h3>';
		echo '<div id="ticket_scanner_info_area">';
		if (isset($_GET['code']) && isset($_GET['redeemauto']) && $this->redeem_succesfully == false) {
			echo '<h3 style="color:red;">TICKET NOT REDEEMED - see reason below</h3>';
		} else if (isset($_GET['code']) && isset($_GET['redeemauto']) && $this->redeem_succesfully) {
			echo '<h3 style="color:green;">TICKET OK - Redeemed</h3>';
		}
		echo '</div>';

		echo '</center>';
		echo '<div id="reader_output">';
		if (SNGMBH::issetRPara("code")) {
			try {
				$codeObj = $this->getCodeObj(true);
				$metaObj = $codeObj['metaObj'];

				$ticket_id = $this->getCore()->getTicketId($codeObj, $metaObj);
				$ticket_start_date = trim(get_post_meta( $metaObj['woocommerce']['product_id'], 'sngmbh_serial_code_ticket_start_date', true ));
				$ticket_start_time = trim(get_post_meta( $metaObj['woocommerce']['product_id'], 'sngmbh_serial_code_ticket_start_time', true ));
				$ticket_end_date = trim(get_post_meta( $metaObj['woocommerce']['product_id'], 'sngmbh_serial_code_ticket_end_date', true ));
				$ticket_end_time = trim(get_post_meta( $metaObj['woocommerce']['product_id'], 'sngmbh_serial_code_ticket_end_time', true ));
				$ticket_end_date_timestamp = strtotime($ticket_end_date." ".$ticket_end_time);
				$color = 'green';
				if ($ticket_end_date != "" && $ticket_end_date_timestamp < time()) {
					$color = 'orange';
				}
				if (!empty($metaObj['wc_ticket']['redeemed_date'])) {
					$color = 'red';
				}

				if (isset($_POST['action']) && $_POST['action'] == "redeem") {
					if ($this->redeem_succesfully) {
						echo '<p style="text-align:center;color:green"><b>'.$this->getText("Successfully redeemed").'</b></p>';
					} else {
						echo '<p style="text-align:center;color:red;"><b>'.$this->getText("Failed to redeem").'</b></p>';
					}
				}

				echo '<div style="border:5px solid '.esc_attr($color).';margin:10px;padding:10px;">';
				$this->outputTicketInfo();
				echo '</div>';

				echo '<form id="f_reload" action="?" method="get">
				<input type="hidden" name="code" value="'.urlencode($ticket_id).'">
				</form>';
				echo '
					<script>
					function reload_ticket() {
						document.getElementById("f_reload").submit();
					}
					</script>
				';
				if (empty($metaObj['wc_ticket']['redeemed_date'])) {
					echo '<form id="f_redeem" action="?" method="post">
							<input type="hidden" name="action" value="redeem">
							<input type="hidden" name="code" value="'.urlencode($ticket_id).'">
							</form></p></center>';
					echo '
						<script>
						function redeem_ticket() {
							document.getElementById("f_redeem").submit();
						}
						</script>
					';
				}
				echo '<center><p><button onclick="reload_ticket()">'.$this->getText("Reload Ticket").'</button>';
				if (empty($metaObj['wc_ticket']['redeemed_date'])) {
					echo '<button onclick="redeem_ticket()" style="background-color:green;color:white;">'.$this->getText("Redeem Ticket").'</button>';
				}
				echo '</p></center>';
			} catch (Exception $e) {
				echo '</div>';
				echo '<div style="color:red;">'.$e->getMessage().'</div>';
				echo $this->getParts()['code'];
			}
		}
		echo '</div>';
		echo '<center>';
		echo '<div id="reader" width="600px"></div>';
		echo '</center>';
		echo '<script>
			var serial_ticket_scanner_redeem = '.(isset($_GET['redeemauto']) ? 'true' : 'false').';
			var loadingticket = false;
			function setRedeemImmediately() {
				serial_ticket_scanner_redeem = !serial_ticket_scanner_redeem;
			}
			function onScanSuccess(decodedText, decodedResult) {
				if (loadingticket) return;
				loadingticket = true;
				// handle the scanned code as you like, for example:
				//console.log(`Code matched = ${decodedText}`, decodedResult);
				jQuery("#reader_output").html(decodedText+"<br>...'.$this->getText("loading").'...");
				window.location.href = "?code="+encodeURIComponent(decodedText) + (serial_ticket_scanner_redeem ? "&redeemauto=1" : "");
				html5QrcodeScanner.stop().then((ignore) => {
					// QR Code scanning is stopped.
					// reload the page with the ticket info and redeem button
					//console.log("stop success");
				}).catch((err) => {
					// Stop failed, handle it.
					//console.log("stop failed");
				});
		  	}
		  	function onScanFailure(error) {
				// handle scan failure, usually better to ignore and keep scanning.
				// for example:
				console.warn(`Code scan error = ${error}`);
		  	}
		  	var html5QrcodeScanner = new Html5QrcodeScanner(
				"reader",
				{ fps: 10, qrbox: {width: 250, height: 250} },
				/* verbose= */ false);
		  </script>';
	  	echo '<script>
		  function startScanner() {
				jQuery("#ticket_scanner_info_area").html("");
				jQuery("#reader_output").html("");
			  	html5QrcodeScanner.render(onScanSuccess, onScanFailure);
		  }
		  </script>';

		if (SNGMBH::issetRPara("code")) {
			echo "<center>";
			echo '<input type="checkbox" onclick="setRedeemImmediately()"'.(SNGMBH::issetRPara("redeemauto") ? " checked" :'').'> Scan and Redeem immediately<br>';
			echo '<button onclick="startScanner()">'.$this->getText("Scan next Ticket").'</button>';
			echo "</center>";

			// display the amount entered already
			if (!$this->MAIN->getOptions()->isOptionCheckboxActive('wcTicketDisplayRedeemedAtScanner')) {
				if ($this->MAIN->isPremium() && method_exists($this->MAIN->getPremiumFunctions(), 'getTicketStats')) {
					if (isset($metaObj['woocommerce']['product_id'])) {
						$amount = $this->MAIN->getPremiumFunctions()->getTicketStats()->getEntryAmountForProductId($metaObj['woocommerce']['product_id']);
						echo "<center><h5>";
						echo $amount." tickets redeemed already";
						echo "</h5></center>";
					}
				}
			}

		} else {
			echo '<script>
			startScanner();
			</script>';
		}
	}

	private function outputPDF() {
		$codeObj = $this->getCodeObj(true);
		$metaObj = $codeObj['metaObj'];
		$order = $this->getOrder();
		$ticket_id = $this->getCore()->getTicketId($codeObj, $metaObj);

		ob_start();
		$this->outputTicketInfo(true);
		$html = ob_get_contents();
		ob_end_clean();

		if (!class_exists('vollstart_PDF')) {
			require_once("vollstart_PDF.php");
		}
		$pdf = new vollstart_PDF();

		if (get_post_meta( $metaObj['woocommerce']['product_id'], 'sngmbh_serial_code_is_RTL', true ) == "yes") {
			//$pdf->setRTL(true);
		}

		$pdf->setFilemode('I');
		$pdf->addPart('<h1 style="text-align:center;">'.$this->getText('Ticket').'</h1>');
		$pdf->addPart('<table style="width:75%;"><tr><td>'.$html.'</td></tr></table>');
		$pdf->addPart('<br><br><p style="text-align:center;">'.$ticket_id.'</p>');
		$pdf->addPart('<br><br><p style="text-align:center;">'.get_bloginfo("name").'<br>'.get_bloginfo("description").'<br><br>'.site_url().'</p>');
		$pdf->addPart('<br><p style="text-align:center;font-size:9pt;">powered by Serial Code Validator Plugin for Wordpress</p>');
		$pdf->addPart('{QRCODE}');
		$pdf->setQRCodeContent(["text"=>$ticket_id]);
		try {
			$pdf->render();
		} catch(Exception $e) {
			echo "<h3>Error with the PDF ticket</h3>";
			echo $e->getMessage();
		}
		exit;
	}

	private function getOrderItem($order, $metaObj) {
		$order_item = null;
		foreach ( $order->get_items() as $item_id => $item ) {
			if ($metaObj['woocommerce']['item_id'] == $item_id) {
				$order_item = $item;
				break;
			}
		}
		return $order_item;
	}

	private function outputTicketInfo($onlyHTML=false) {
		$codeObj = $this->getCodeObj();
		$metaObj = $codeObj['metaObj'];
		$order = $this->getOrder();
		$ticket_id = $this->getCore()->getTicketId($codeObj, $metaObj);

		// suche item in der order
		$order_item = $this->getOrderItem($order, $metaObj);
		if ($order_item == null) throw new Exception("#8004 ".$this->getText("Order item not found"));
		$product = $order_item->get_product();
		// zeige Produkt title
		if (!$onlyHTML) echo '<h3 style="color:black;text-align:center;">'.$this->getText('Ticket').'</h3>';
		echo '<h4 style="color:black;">'.esc_html($product->get_Title()).'</h4>';

		// zeige datum
		$ticket_start_date = trim(get_post_meta( $metaObj['woocommerce']['product_id'], 'sngmbh_serial_code_ticket_start_date', true ));
		$ticket_start_time = trim(get_post_meta( $metaObj['woocommerce']['product_id'], 'sngmbh_serial_code_ticket_start_time', true ));
		$ticket_end_date = trim(get_post_meta( $metaObj['woocommerce']['product_id'], 'sngmbh_serial_code_ticket_end_date', true ));
		$ticket_end_time = trim(get_post_meta( $metaObj['woocommerce']['product_id'], 'sngmbh_serial_code_ticket_end_time', true ));
		$ticket_end_date_timestamp = strtotime($ticket_end_date." ".$ticket_end_time);
		if (!empty($ticket_start_date)) {
			echo '<p>';
			echo $ticket_start_date;
			if (!empty($ticket_start_time)) echo " ".$ticket_start_time;
			if (!empty($ticket_end_date) || !empty($ticket_end_time)) echo " - ";
			if (!empty($ticket_end_date))  echo $ticket_end_date;
			if (!empty($ticket_end_time)) echo " ".$ticket_end_time;
			if ($ticket_end_date_timestamp < time()) echo ' <span style="color:red;">'.$this->getText("EXPIRED").'</span>';
			echo '</p>';
		}

		// zeige optionales produkt ticket notes
		if ($this->MAIN->getOptions()->isOptionCheckboxActive('wcTicketDisplayShortDesc')) {
			$short_desc = $product->get_short_description();
			if (!empty($short_desc)) {
				echo '<p>'.wp_kses_post($short_desc).'</p>';
			}
		}
		$ticket_info = trim(get_post_meta( $metaObj['woocommerce']['product_id'], 'sngmbh_serial_code_is_ticket_info', true ));
		if (!empty($ticket_info)) {
			echo "<p>".wp_kses_post($ticket_info)."</p>";
		}

		// order details
		if (!$this->MAIN->getOptions()->isOptionCheckboxActive('wcTicketDontDisplayCustomer')) {
			echo "<p>";
			echo '<b>'.$this->getText("Customer").'</b><br>';
			echo wp_kses_post(trim($order->get_formatted_billing_address())).'<br>';
			echo "</p>";
		}

		if (!$this->MAIN->getOptions()->isOptionCheckboxActive('wcTicketDontDisplayPayment')) {
			echo "<p>";
			echo '<b>'.$this->getText("Payment details").'</b><br>';
			echo $this->getText("Order paid at:").' <b>'.$order->get_date_paid().'</b><br>';
			echo $this->getText("Order completed at:").' <b>'.$order->get_date_completed().'</b><br>';
			$payment_method = $order->get_payment_method_title();
			if (!empty($payment_method)) {
				echo $this->getText("Paid via:").' <b>'.esc_html($payment_method).' (#'.$order->get_transaction_id().')</b><br>';
			} else {
				echo $this->getText("Free ticket").'<br>';
			}
			$coupons = $order->get_coupon_codes();
			if (count($coupons) > 0) {
				echo $this->getText("Coupons used: ").' <b>'.esc_html(implode(", ", $coupons)).'</b><br>';
			}
			echo "</p>";
		}

		// zeige Ticket nummer
		echo "<p>".$this->getText("Ticket").": <b>".$codeObj['code_display']."</b><br>";

		// zeige gezahlten preis und product preis
		$paid_price = $order_item->get_subtotal() / $order_item->get_quantity();
		$product_price = $product->get_price();
		echo $this->getText("Price: ")."<b>".wc_price($paid_price, ['decimals'=>2])."</b>";
		if ($product_price != $paid_price) {
			//get_woocommerce_currency_symbol()
			echo " (".$this->getText("Originalpreis: ").wc_price($product_price, ['decimals'=>2]).")";
		}
		echo "</p>";

		// zeige BTN zum Redeem - oder wenn schon redeem, Meldung dazu
		if (!$onlyHTML) {
			if (!empty($metaObj['wc_ticket']['redeemed_date'])) {
				echo '<center>';
				echo '<h4 style="color:red;">'.$this->getText("Ticket redeemed")."</h4>";
				echo $this->getText("Redeemed at:")." ".$metaObj['wc_ticket']['redeemed_date']."<br>";
				if (!$this->isScanner()) {
					if ($ticket_end_date_timestamp > time()) {
						echo '<h5 style="font-weight:bold;color:green;">'.$this->getText("Ticket valid").'</h5>';
						echo '<form method="get"><input type="submit" value="'.$this->getText("Refresh page").'"></form>';
					}
				}
				echo '</center>';
			} else {
				if (!$this->MAIN->getOptions()->isOptionCheckboxActive('wcTicketDontShowRedeemBtnOnTicket')) {
					// zeige Redeem button
					if (!$this->isScanner()) {
						if ($ticket_end_date_timestamp > time()) {
							echo '
							<script>
							function redeem_ticket() {
								if (confirm("'.$this->getText("Do you want to redeem the ticket? Typically this is done at the entrance. This will mark this ticket as redeemed.").'")) {
									return true;
								}
								return false;
							}
							</script>
							';
							echo '<center><form onsubmit="return redeem_ticket()" method="post"><input type="hidden" name="action" value="redeem"><input type="submit" class="button-primary" value="'.$this->getText("Redeem Ticket").'"></form></center>';
						} else {
							echo '<center>'.$this->getText("Ticket expired").'</center>';
						}
					}
				}
			}
			// zeige QR code zum scannen für admin
			if (!$this->isScanner()) {
				echo '<div id="qrcode" style="margin-top:3em;text-align:center;"></div>';
				echo '<script>jQuery("#qrcode").qrcode("'.$ticket_id.'");</script>';
			}
			// zeige eingabe code an für admin (order_id, product_id, code)
			echo '<p style="text-align:center;">'.$ticket_id.'</p>';
			if (!$this->MAIN->getOptions()->isOptionCheckboxActive('wcTicketDontDisplayPDFButtonOnDetail')) {
				$dlnbtnlabel = $this->MAIN->getOptions()->getOptionValue('wcTicketLabelPDFDownload');
				echo '<p style="text-align:center;"><a class="button" target="_blank" href="'.$metaObj['wc_ticket']['_url'].'?pdf">'.$dlnbtnlabel.'</a></p>';
			}
		} // end onlyhtml
	}

	private function executeRequestScanner() {
		global $sngmbhSerialcodesValidator;
		if (isset($_POST['action']) && $_POST['action'] == "redeem" || (isset($_GET['redeemauto']) && isset($_GET['code']))) {
			if (!isset($_POST['code']) && !isset($_GET['code']) ) throw new Exception("#8008 code to redeem is missing");
			$this->redeem_succesfully = false;
			$codeObj = $this->getCodeObj();
			$metaObj = $codeObj['metaObj'];
			if ($metaObj['wc_ticket']['redeemed_date'] == "") {
				$order = $this->getOrder();
				$user_id = $order->get_user_id();
				$user_id = intval($user_id);
				$data = [
					'code'=>$codeObj['code'],
					'userid'=>$user_id,
					'redeemed_by_admin'=>1
				];
				$sngmbhSerialcodesValidator->getAdmin()->executeJSON('redeemWoocommerceTicketForCode', $data, true);
				$this->codeObj = null;
				$this->redeem_succesfully = true;
			}
		}
	}

	private function executeRequest() {
		global $sngmbhSerialcodesValidator;
		// auswerten $this->getParts()['_request']
		//if ($this->getParts()['_request'] == "action=redeem") {
		if (isset($_POST['action']) && $_POST['action'] == "redeem") {
			// redeem ausführen
			$codeObj = $this->getCodeObj();
			$metaObj = $codeObj['metaObj'];
			//
			if ($metaObj['wc_ticket']['redeemed_date'] == "") {
				$user_id = get_current_user_id();
				if (empty($user_id)) {
					$order = $this->getOrder();
					$user_id = $order->get_user_id();
				}
				$user_id = intval($user_id);
				$data = [
					'code'=>$codeObj['code'],
					'userid'=>$user_id
				];
				$sngmbhSerialcodesValidator->getAdmin()->executeJSON('redeemWoocommerceTicketForCode', $data, true);
				$this->codeObj = null;
			}
		}
	}

	public function addMetaTags() {
		echo "\n<!-- Meta TICKET -->\n";
        echo '<meta property="og:title" content="'.$this->getText("Ticket Info").'" />';
        echo '<meta property="og:type" content="article" />';
        //echo '<meta property="og:description" content="'.$this->getPageDescription().'" />';
		echo '<style>
			div.ticket_content p {font-size:initial !important;margin-bottom:1em !important;}
			</style>';
        echo "\n<!-- Ende Meta TICKET -->\n\n";
	}

	private function isPDFRequest() {
		if (isset($_GET['pdf'])) return true;
		$this->getParts();
		if ($this->parts != null) {
			return $this->parts['_isPDFRequest'];
		}
		return false;
	}

	public function output() {
		header('HTTP/1.1 200 OK');
		if (class_exists( 'WooCommerce' )) {

			if (!$this->isScanner() && $this->isPDFRequest()) {
				try {
					$this->outputPDF();
					exit;
				} catch (Exception $e) {
				}
			}

			$js_url = "jquery.qrcode.min.js?_v=".$this->MAIN->getPluginVersion();
			wp_enqueue_script(
				'ajax_script',
				plugins_url( "3rd/".$js_url,__FILE__ ),
				array('jquery', 'jquery-ui-dialog')
			);
			if ($this->isScanner()) {
				$js_url = plugin_dir_url(__FILE__)."3rd/html5-qrcode.min.js?_v=".$this->MAIN->getPluginVersion();
				wp_register_script('html5-qrcode', $js_url);
				wp_enqueue_script('html5-qrcode');
			}
			get_header();
			echo '<div style="width: 100%; justify-content: center;align-items: center;position: relative;">';
			echo '<div class="ticket_content" style="background-color:white;color:black;padding:15px;display:block;position: relative;left: 0;right: 0;margin: auto;text-align:left;max-width:640px;border:1px solid black;">';

			try {
				if ($this->isScanner()) {
					$this->executeRequestScanner();
					$this->outputTicketScanner();
				} else {
					$this->executeRequest();
					$this->outputTicketInfo();
				}
			} catch(Exception $e) {
				echo '<h1 style="color:red;">Error</h1>';
				echo $e->getMessage();
			}
			echo "</div>";
			echo "</div>";
		} else {
			get_header();
			echo '<h1 style="color:red;">No WooCommerce Support Found</h1>';
			echo '<p>Please contact us for a solution.</p>';
		}
		get_footer();
	}
}
?>