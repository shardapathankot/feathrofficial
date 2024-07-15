<?php
include(plugin_dir_path(__FILE__)."init_file.php");
class sngmbhSerialcodesValidator_Options {
	private $_options;
	private $MAIN;
	private $_prefix;
	public function __construct($MAIN, $_prefix) {
		$this->MAIN = $MAIN;
		$this->_prefix = $_prefix;
	}
	private function getBase() {
		return $this->MAIN->getBase();
	}
	public function initOptions(){
		$this->_options = [];
		//$this->_options[] = $this->getOptionsObject('deleteTables', "Delete Tables with deletion of plugin");
		$this->_options[] = $this->getOptionsObject('h0a', "Access","","heading");
		$this->_options[] = $this->getOptionsObject('allowOnlySepcificRoleAccessToAdmin', "Allow only specific roles access to the admin area","If active, then only the administrator and the choosen roles area allowed to access this admin area.","checkbox", "", [], true);
		$all_roles = wp_roles()->roles;
		$editable_roles = apply_filters('editable_roles', $all_roles);
		$additional = [ "multiple"=>1, "values"=>[["label"=>"No role execept Administrator allowed", "value"=>"-"]] ];
		foreach($editable_roles as $key => $value) {
			if ($key == "administrator") continue;
			$additional['values'][] = ["label"=>$value['name'], "value"=>$key];
		}
		$this->_options[] = $this->getOptionsObject('adminAreaAllowedRoles',	"Allow the specific role to access the ticket scanner", "If a role is chosen, then the user with this role is allowed to use the ticket scanner. This will not exclude the 'administrator', if the option is activated.", "dropdown",	"-", $additional, false);

		$this->_options[] = $this->getOptionsObject('h0', "Validator Form","","heading");
		$this->_options[] = $this->getOptionsObject('textValidationButtonLabel', "Your own check button label","If left empty, default will be 'Check'","text", "Check", [], true);
		$this->_options[] = $this->getOptionsObject('textValidationInputPlaceholder', "Your own input field placeholder text","If left empty, default will be 'XXYYYZZ'","text", "XXYYYZZ", [], true);
		$this->_options[] = $this->getOptionsObject('textValidationBtnBgColor', "You own background color of the button","If left empty, default will be <span style='color:#007bff;'>'#007bff'</span>","text", "", [], true);
		$this->_options[] = $this->getOptionsObject('textValidationBtnBrdColor', "You own border color of the button","If left empty, default will be <span style='color:#007bff;'>'#007bff'</span>","text", "", [], true);
		$this->_options[] = $this->getOptionsObject('textValidationBtnTextColor', "You own text color of the button","If left empty, default will be 'white'","text", "", [], true);

		$this->_options[] = $this->getOptionsObject('h1', "Validation Messages","","heading");
		$this->_options[] = $this->getOptionsObject('textValidationMessage1', "Your own 'Code confirmed' message","If left empty, default will be 'Code confirmed'","text", "Code confirmed", [], true);
		$this->_options[] = $this->getOptionsObject('textValidationMessage0', "Your own 'Code not found' message","If left empty, default will be 'Code not found'","text", "Code not found", [], true);
		$this->_options[] = $this->getOptionsObject('textValidationMessage2', "Your own 'Code inactive' message","If left empty, default will be 'Please contact support for further investigation'","text","Please contact support for further investigation", [], true);
		$this->_options[] = $this->getOptionsObject('textValidationMessage3', "Your own 'Code is already registered to a user' message","If left empty, default will be 'Is registered to a user'","text","Is registered to a user", [], true);
		$this->_options[] = $this->getOptionsObject('textValidationMessage4', "Your own 'Code expired' message","If left empty, default will be 'Code expired'","text","Code expired", [], true);
		$this->_options[] = $this->getOptionsObject('textValidationMessage6', "Your own 'Code and CVV is not valid' message","If left empty, default will be 'Code and CVV is not valid'.","text","Code and CVV is not valid", [], true);
		$this->_options[] = $this->getOptionsObject('textValidationMessage7', "Your own 'Product/Code stolen' message","If left empty, default will be 'Product stolen'. You coud set it to be more precise e.g.: 'The serial number is reported as stolen'","text","Product is stolen", [], true);
		$this->_options[] = $this->getOptionsObject('textValidationMessage8', "Your own 'Ticket is redeemed' message","If left empty, default will be 'Ticket is redeemed'.","text","Ticket is redeemed", [], true);

		$this->_options[] = $this->getOptionsObject('h1a', "Display Additional Information","","heading");
		$this->_options[] = $this->getOptionsObject('displayCodeInfoDateFormat', "Your own date format","If left empty, default will be 'Y/m/d'. Using the php date function format. Y=year, m=month, d=day H:hours, i:minutes, s=seconds","text", "Y/m/d", [], true);
		$this->_options[] = $this->getOptionsObject('displayAdminAreaColumnBillingName', "Display a column with the name of the buyer", "If active, then a new column within the admin area for each code will be shown with the billing name. It will also add the billing name to the export. <b>This feature can be very slow.</b>","checkbox");
		$this->_options[] = $this->getOptionsObject('displayCodeListDescriptionIfValid', "Display the assigned code list description if the serial code was confirmed.","If active and a code list is assigned to the code, then the description will be shown to the 'Code confirmed' message", "checkbox", "", []);
		$this->_options[] = $this->getOptionsObject('displayCodeInfoFirstCheck', "Display the first successful check date.","Displays the label and date of the first successful validation, if not empty.", "checkbox", "", []);
		$this->_options[] = $this->getOptionsObject('displayCodeInfoFirstCheckLabel', "Your own 'first successful check' label","If left empty, default will be 'Successful validation at {VALIDATION-FIRST_SUCCESS}'. The placeholder {VALIDATION-FIRST_SUCCESS} will be replace with the data. If you do not enter the placeholder, then the data will be added at the end of the label.","text", "Successful validation at {VALIDATION-FIRST_SUCCESS}", [], true);
		$this->_options[] = $this->getOptionsObject('displayCodeInfoLastCheck', "Display the last successful check date.","Displays the label and date of the last successful validation, if not empty.", "checkbox", "", []);
		$this->_options[] = $this->getOptionsObject('displayCodeInfoLastCheckLabel', "Your own 'last successful check' label","If left empty, default will be 'Last successful validation at {VALIDATION-LAST_SUCCESS}'","text", "Last successful validation at {VALIDATION-LAST_SUCCESS}", [], true);
		$this->_options[] = $this->getOptionsObject('displayCodeInfoConfirmedCount', "Display the amount of successful checks.","Displays the label and amount of the successful validations", "checkbox", "", []);
		$this->_options[] = $this->getOptionsObject('displayCodeInfoConfirmedCountLabel', "Your own 'amount of successful checks' label","If left empty, default will be 'This code was already {CONFIRMEDCOUNT} times validated'. The placeholder {CONFIRMEDCOUNT} will be replace with the amount. If you do not enter the placeholder, then the amount will be added at the end of the label.","text", "This code was already {CONFIRMEDCOUNT} times validated", [], true);

		$this->_options[] = $this->getOptionsObject('h2', "Logged in user only","","heading");
		$this->_options[] = $this->getOptionsObject('onlyForLoggedInWPuser', "Allow only logged in wordpress user to enter a serial code", "If active and the user is not logged in, then the input fields will be disabled.", "checkbox", "", [], true);
		$this->_options[] = $this->getOptionsObject('onlyForLoggedInWPuserMessage', "Your own 'Only for logged in user' message","If left empty, default will be 'You need to log in to use the serial code validator'","text", "You need to log in to use the serial code validator", [], true);

		$this->_options[] = $this->getOptionsObject('h4', "One time code check","","heading");
		$this->_options[] = $this->getOptionsObject('oneTimeUseOfRegisterCode', "Each 'confirmed' code will be marked as used","If active, a successful checked code will be marked as used and the message for used-code will be displayed instead of the code-confirmed message");
		$this->_options[] = $this->getOptionsObject('textValidationMessage5', "Your own 'Code is used' message","If left empty, default will be 'Code is already used'","text", "Code is already used", [], true);
		$this->_options[] = $this->getOptionsObject('oneTimeUseOfRegisterAmount', "After which successful 'confirmed' check, should the code be marked as used?","If left empty, default will be '1'","number", 1, ['min'=>1]);
		$this->_options[] = $this->getOptionsObject('oneTimeUseOfRegisterCodeWPuser', "Record wordpress userid","If active, the wordpress userid will be stored if the user is logged in.");

		$this->_options[] = $this->getOptionsObject('h5', "Register user to code","","heading");
		$this->_options[] = $this->getOptionsObject('autoUserRegisterToCodeWithOrder', "Register user to the purchased code.","If active, the user of the order will be to register to the code(s) during the order purchase. It will only work, if the order has a user. Guests cannot be assigned to the code, because no user id available.", "checkbox");
		$this->_options[] = $this->getOptionsObject('allowUserRegisterCode', "Allow your users to register themself for a code.","If active, the user will get the option to register with an 'email address' (or your registration value text) to the code. <b>IMPORTANT</b>: If activate, the redirect option will executed after the registration.", "checkbox", "", [], true);
		$this->_options[] = $this->getOptionsObject('textRegisterButton', "Your own button label 'Register for this code'","If left empty, default will be 'Register for this code'","text", "Register for this code", [], true);
		$this->_options[] = $this->getOptionsObject('textRegisterValue', "Your own label for the user registration value question","If left empty, default will be 'Enter your email address'","text", "Enter your email address", [], true);
		$this->_options[] = $this->getOptionsObject('textRegisterSaved', "Your own message for the 'user registration value is stored' operation","If left empty, default will be 'Your code is registered to you'","text", "Your code is registered to you", [], true);
		$this->_options[] = $this->getOptionsObject('allowUserRegisterCodeWPuserid', "Track wordpress userid","If active and the user is logged in, then the userid will be stored to the registration information.");
		$this->_options[] = $this->getOptionsObject('allowUserRegisterSkipValueQuestion', "Skip asking for the registration value, if the user is logged in","If active and the user is logged in, then question of 'Register for this code' will be not shown and the 'is stored text' will be displayed immediately.", "checkbox", "", [], true);

		$this->_options[] = $this->getOptionsObject('h6', "Display registered information of a code","","heading");
		$this->_options[] = $this->getOptionsObject('displayUserRegistrationOfCode', "Display the collected information of a registration to a code.", 'Usefull if your codes are certificatins and you want if somebody type in the code to see who it belongs to.');
		$this->_options[] = $this->getOptionsObject('displayUserRegistrationPreText', "Your own pre-text for the display of the collected information","If not empty, it will be added one line above the registered information to the code","text", "");
		$this->_options[] = $this->getOptionsObject('displayUserRegistrationAfterText', "Your own after-text for the display of the collected information","If not empty, it will be added one line below the registered information to the code","text", "");

		$this->_options[] = $this->getOptionsObject('h8', "User redirection","","heading");
		$this->_options[] = $this->getOptionsObject('userJSRedirectActiv', "Activate redirect the user after a valid code found.","If active, the user will be redirected to the URL your provide below.", "checkbox", "", [], true);
		$this->_options[] = $this->getOptionsObject('userJSRedirectIfSameUserRegistered', "Redirect already registered codes and the user is the same.","If active, the user will be redirected to the URL your provide below, even if the code is registered already and user checking is the same user that is registered to the code. It will not be executed, if the 'one time usage restriction is active'.", "checkbox", "", [], true);
		$this->_options[] = $this->getOptionsObject('userJSRedirectURL', "URL to redirect the user, if the code is valid.","The URL can be relative like '/page/' or absolute 'https//domain/url/'.<br>You can use these placeholder for your URL:<ul><li><b>{USERID}</b>: Will be replaced with the userid if the user is loggedin or empty</li><li><b>{CODE}</b>: Will be replaced with the code (without the delimiters)</li><li><b>{CODEDISPLAY}</b>: Will be replaced with the code (WITH the delimiters)</li><li><b>{IP}</b>: The IP address of the user</li><li><b>{LIST}</b>: Name of the list if assigned</li><li><b>{LIST_DESC}</b>: Description of the assigned list</li><li><a href='#replacementtags'>More tags here</a></li></ul>", "text", "");
		$this->_options[] = $this->getOptionsObject('userJSRedirectBtnLabel', "Button to click for the user to be forwarded","Only if filled out, the button will be displayed. If you left this field empty, then the user will be redirected immediately if the code is valid, without a button to click.","text", "");

		$this->_options[] = $this->getOptionsObject('h9', "Webhooks","","heading");
		$this->_options[] = $this->getOptionsObject('webhooksActiv', "Activate webhooks to call a service with the validation check.","If active, each validation request from a user will trigger an URL from the server side to another URL. Be carefull. This could slow down the validation check. It depends how fast your service URLs are responding.<br>The URL can be relative like '/page/' or absolute 'https//domain/url/'.<br><br><b>You can use these placeholder for your URL:</b><ul><li><b>{USERID}</b>: Will be replace with the userid if the user is loggedin or empty</li><li><b>{CODE}</b>: Will be replace with the code (without the delimiters)</li><li><b>{CODEDISPLAY}</b>: Will be replace with the code (WITH the delimiters)</li><li><b>{IP}</b>: The IP address of the user</li><li><b>{LIST}</b>: Name of the list if assigned</li><li><a href='#replacementtags'>More tags here</a></li></ul>");
		$this->_options[] = $this->getOptionsObject('webhookURLinactive', "URL to your service if the checked code <b>is inactive</b>.","Only triggered, if not empty.", "text", "");
		$this->_options[] = $this->getOptionsObject('webhookURLvalid', "URL to your service if the checked code <b>is valid</b>.","Only triggered, if not empty.", "text", "");
		$this->_options[] = $this->getOptionsObject('webhookURLinvalid', "URL to your service if the checked code <b>is invalid</b> (not found).","Only triggered, if not empty.", "text", "");
		$this->_options[] = $this->getOptionsObject('webhookURLregister', "URL to your service if <b>someone register to this code</b>.","Only triggered, if not empty.", "text", "");
		$this->_options[] = $this->getOptionsObject('webhookURLisregistered', "URL to your service if the checked code is already <b>registered to someone</b>.","Only triggered, if not empty.", "text", "");
		$this->_options[] = $this->getOptionsObject('webhookURLsetused', "URL to your service if the checked code is valid and is <b>marked to be used the first time</b>.","Only triggered, if not empty.", "text", "");
		$this->_options[] = $this->getOptionsObject('webhookURLmarkedused', "URL to your service if the checked code is already <b>marked as used and checked again</b>.","Only triggered, if not empty.", "text", "");
		$this->_options[] = $this->getOptionsObject('webhookURLrestrictioncodeused', "URL to your service if an order item is bought using a restriction code.", "Only triggered, if not empty.", "text", "");
		$this->_options[] = $this->getOptionsObject('webhookURLaddwcinfotocode', "URL to your service if a code received WooCommerce data, if a 'code was purchased'.","Only triggered, if not empty.", "text", "");
		$this->_options[] = $this->getOptionsObject('webhookURLwcremove', "URL to your service if the WooCommerce data is removed from the code.","Only triggered, if not empty.", "text", "");
		$this->_options[] = $this->getOptionsObject('webhookURLaddwcticketinfoset', "URL to your service if the WooCommerce ticket data is set for this code.","Only triggered, if not empty.", "text", "");
		$this->_options[] = $this->getOptionsObject('webhookURLaddwcticketredeemed', "URL to your service if the WooCommerce ticket is redeemed.","Only triggered, if not empty.", "text", "");
		$this->_options[] = $this->getOptionsObject('webhookURLaddwcticketunredeemed', "URL to your service if the WooCommerce ticket is un-redeemed.","Only triggered, if not empty.", "text", "");
		$this->_options[] = $this->getOptionsObject('webhookURLaddwcticketinforemoved', "URL to your service if the WooCommerce ticket data is removed from the code.","Only triggered, if not empty.", "text", "");

		$this->_options[] = $this->getOptionsObject('h10', "Woocommerce product serial assignment","","heading");
		if (!$this->MAIN->isPremium()) {
			$this->_options[] = $this->getOptionsObject('wcassignmentTextNoCodePossible', "Text that will be used, if you do not have <b>premium</b> and run out of free code amount. This text will be added to the WooCoomerce purchase information instead of the code", "If left empty, default will be 'Please contact our support for the serial code'","text", "Please contact our support for the serial code", [], true);
		}
		$this->_options[] = $this->getOptionsObject('wcassignmentReuseNotusedCodes', "Reuse codes from the code list assigned to the woocommerce product, that are not already used by a woocommerce purchase.","If active, the system will try to use an existing code from the code list that is free. If no free code could be found, a new code will be created and assigned to the purchase.", "checkbox", true, []);
		$this->_options[] = $this->getOptionsObject('wcassignmentPrefixTextCode', "Text that will be added before the code on the PDF invoice.", "If left empty, default will be 'Code:'","text", "Code:", [], false);
		$this->_options[] = $this->getOptionsObject('wcassignmentDoNotPutCVVOnEmail', "Do not print the serial code CVV on the email 'customer completed order'.","If active, the assigned CVV will not be printed on the email", "checkbox", "", []);
		$this->_options[] = $this->getOptionsObject('wcassignmentDoNotPutCVVOnPDF', "Do not print the serial code CVV on the PDF invoice woocommerce purchase.","If active, the assigned CVV will not be printed on the PDF", "checkbox", "", []);
		$this->_options[] = $this->getOptionsObject('wcassignmentDoNotPutOnEmail', "Do not put the serial code in the email 'customer completed order'.","If active, the assigned serial code will not be put in the email", "checkbox", "", []);
		$this->_options[] = $this->getOptionsObject('wcassignmentDoNotPutOnPDF', "Do not print the serial code on the PDF invoice woocommerce purchase.","If active, the assigned serial code will not be printed on the PDF", "checkbox", "", []);
		$this->_options[] = $this->getOptionsObject('wcassignmentDisplayCodeSeperator', "On Email: Text or letter to be used to be used as a seperator for codes of the user.", "If the user has more than one code assigned to her, then this text will be used to seperate them for display the codes. If left empty, then it will be ', ' as a default.","text", ", ");
		$this->_options[] = $this->getOptionsObject('wcassignmentDisplayCodeSeperatorPDF', "On PDF invoice: Text or letter to be used to be used as a seperator for codes of the user.", "If the user has more than one code assigned to her, then this text will be used to seperate them for display the codes. If left empty, then it will be ', ' as a default.","text", ", ");
		$this->_options[] = $this->getOptionsObject('wcassignmentUseGlobalSerialFormatter', "Set the serial code formatter pattern for new sales.","If active, the a new serial code will generated using the following settings", "checkbox", "", []);
		$this->_options[] = $this->getOptionsObject('wcassignmentUseGlobalSerialFormatter_values', "","", "text", "", ["doNotRender"=>1]);

		$this->_options[] = $this->getOptionsObject('h11', "Woocommerce restrict product sale without serial code","You can restrict the sale of products to be allowed only if your customer enters a valid and unused serial code. You can assign the serial code list for the restriction, within the product details.","heading");
		$this->_options[] = $this->getOptionsObject('wcRestrictPurchase', "Activate the purchase restrictions of product sales without a valid and unused serial code.","If active and a product in the cart has an active restriction set, then your customer will see an input field for the purchase allowance code<br>If not activated, then the restrictions set on the products are ignored - allows you to pause the purchase restriction feature.", "checkbox", "", []);
		$this->_options[] = $this->getOptionsObject('wcRestrictDoNotPutOnPDF', "Do not print the used restriction serial code on the PDF invoice woocommerce purchase.","If active, the used serial code will not be printed on the PDF", "checkbox", "", []);
		$this->_options[] = $this->getOptionsObject('wcRestrictPrefixTextCode', "Text that will be added before the used purchase code on the PDF invoice, order table and order details.", "If left empty, default will be 'Purchase Code:'","text", "Purchase Code:", [], false);
		$this->_options[] = $this->getOptionsObject('wcRestrictCartInfo', "Text that will be displayed below the product title within the WooCommerce cart.", "If left empty, default will be 'This product can only be purchased with a code'","text", "This product can only be purchased with a code", []);
		$this->_options[] = $this->getOptionsObject('wcRestrictCartFieldPlaceholder', "Text that will be displayed as the placeholder text on the code input field.", "If left empty, default will be 'Enter purchase allowance code'","text", "Enter purchase allowance code", []);
		$this->_options[] = $this->getOptionsObject('wcRestrictFreeCodeByOrderRefund', "Clear the code if the order was deleted or a refund triggered", "If the order is deleted or the status is set to 'refund', then the WooCommerce order information is removed from the code. If the option 'one time usage' is active, then the code will be unmarked as used.", "checkbox", "", []);
		$this->_options[] = $this->getOptionsObject('wcRestrictOneTimeUsage', "Mark the used restriction serial as used", "If someone used the restriction key, it will be marked as used and cannot be used anymore.", "dropdown", "", ["values"=>[['value'=>0, 'label'=>'Use the One-Time-Usage settings for serials'],['value'=>1, 'label'=>'Mark immediately as used - one time usage'], ['value'=>2, 'label'=>'Unlimited usage allowed']]]);

		if (file_exists(plugin_dir_path(__FILE__)."vollstart_Ticket.php")) {
			include_once(plugin_dir_path(__FILE__)."vollstart_Ticket.php");
			if (class_exists('vollstart_Ticket')) {
				$_options = vollstart_Ticket::getOptionsRawObject();
				foreach($_options as $o) {
					$this->_options[] = $this->getOptionsObject(
						$o['key'], $o['label'], $o['desc'], $o['type'],
						isset($o['def']) ? $o['def'] : null,
						isset($o['additional']) ? $o['additional'] : [],
						isset($o['isPublic']) ? $o['isPublic'] : false
					);
				}
			}
		}

		$this->_options[] = $this->getOptionsObject('h13', "Display code to your loggedin user","You can display the serial codes assigned to an user with this shortcode <b>[sngmbhSerialcodesValidator_code]</b>.","heading");
		$this->_options[] = $this->getOptionsObject('userDisplayCodePrefix', "Text that will be added before the serial code(s) for the user are displayed.", "","text", "Your serial code(s): ", [], false);
		$this->_options[] = $this->getOptionsObject('userDisplayCodePrefixAlways', "Display the prefix text always.","If active, your prefix text will be rendered always. Even if the user is not logged in or do not have any serial codes assigned to her yet.", "checkbox", "", []);
		$this->_options[] = $this->getOptionsObject('userDisplayCodeSeperator', "Text or letter to be used to be used as a seperator for codes of the user.", "If the user has more than one code assigned to her, then this text will be used to seperate them for display the codes. If left empty, then it will be ', ' as a default.","text", ", ");

		$this->_options[] = $this->getOptionsObject('h20', "User profile", "", "heading");
		$this->_options[] = $this->getOptionsObject('userProfileDisplayRegisteredNumbers', "Display registered serial numbers within the user profile", "", "checkbox");
		$this->_options[] = $this->getOptionsObject('userProfileDisplayBoughtNumbers', "Display bought serial numbers within the user profile", "", "checkbox");

		$this->_options[] = $this->getOptionsObject('h14', "QR code","You can generate QR code images for your serial codes.","heading");
		$this->_options[] = $this->getOptionsObject('qrDirectURL', "URL for the QR image.","The URL should be absolute, if you like to provide the generated QR image to your customers. The image can be retrieved within the serial code area. The code detail contains a button for it.<br>You can use these placeholder for your URL:<ul><li><b>{CODE}</b>: Will be replaced with the code (without the delimiters)</li><li><b>{CODEDISPLAY}</b>: Will be replaced with the code (WITH the delimiters)</li><b>{LIST}</b>: Name of the list if assigned</li><li><b>{LIST_DESC}</b>: Description of the assigned list</li><li><a href='#replacementtags'>You could use more tags.</a> But it is not recommend, since the QR code is generated within the admin area.</li></ul>", "text", "");

		if ($this->MAIN->isPremium()) {
			$this->_options = $this->MAIN->getPremiumFunctions()->_initOptions($this->_options);
		}
	}
	public function getOptionsObject($key, $label, $desc="",$type="checkbox",$def=null,$additional=[], $isPublic=false) {
		if ($def == null) {
			switch($type) {
				case "number":
				case "checkbox":
					$def = 0;
					break;
				default:
					$def = "";
			}
		}
		return ['key'=>$key,'id'=>$this->_prefix.$key,'label'=>$label,'desc'=>$desc,'value'=>0,'type'=>$type,'default'=>$def,'additional'=>$additional, 'isPublic'=>$isPublic, '_isLoaded'=>false];
	}
	public function getOptions() {
		foreach($this->_options as $idx => $option) {
			if ($option['_isLoaded'] == false) {
				/*
				$defv = ($option['type'] == "text") ? "" : 0;
				if ($option['type'] == "number") $defv = 0;
				if (is_numeric($defv)) {
					$option['value'] = $defv;
				}
				*/
				$v = get_option( $option['id'], $option['default']);
				if (!is_array($v)) {
					$v = stripslashes($v);
				}
				$option['value'] = $v;
				$option['_isLoaded'] = true;
				$this->_options[$idx] = $option;
			}
		}
		return $this->_options;
	}
	public function getOptionsOnlyPublic() {
		$ret = [];
		$options = $this->getOptions();
		foreach($options as $option) {
			if ($option['isPublic'] == true) {
				$ret[] = $option;
			}
		}
		return $ret;
	}
	public function getOption($key) {
		$o = null;
		$key = trim($key);
		if (empty($key)) return $o;
		$options = $this->getOptions();
		foreach($options as $option) {
			if ($option['key'] === $key) {
				$o = $option;
				break;
			}
		}
		return $o;
	}
	private function _setOptionValuesByKey($key, $field, $value) {
		foreach ($this->_options as $idx => $value) {
			if ($value['key'] == $key) {
				$this->_options[$idx][$field] = $value;
				break;
			}
		}
	}
	public function changeOption($data) {
		$option = $this->getOption($data['key']);
		if ($option != null) {
			if ($option['type'] == "checkbox") {
				$v = intval($data['value']);
			} else {
				if (is_array($data['value'])) {
					array_walk($data['value'], "trim");
				} else {
					$data['value'] = trim($data['value']);
				}
				$v = $data['value'];
			}
			update_option($option['id'], $v);
			$this->_setOptionValuesByKey($data['key'], 'value', $v);
		}
		do_action( $this->MAIN->_do_action_prefix.'changeOption', $data);
	}
	public function getOptionValue($name, $def="") {
		$option = $this->getOption($name);
		if ($option == null) return $def;
		return $this->_getOptionValue($option);
	}
	private function _getOptionValue($option) {
		$ret = "";
		if (is_array($option['value'])) {
			$ret = $option['value'];
			if (count($option['value']) == "") $ret = $option['default'];
		} else {
			$ret = empty(trim($option['value'])) ? $option['default'] : $option['value'];
		}
		return $ret;
	}
	public function isOptionCheckboxActive($optionname) {
		$option = $this->getOption($optionname);
		if ($option == null || intval($option['value']) != 1) return false;
		return true;
	}

	public function getOptionDateFormat() {
		$date_format = $this->getOptionValue('displayCodeInfoDateFormat');
		try {
			$d = date($date_format);
		} catch(Exception $e) {
			$date_format = 'Y/m/d';
		}
		return $date_format;
	}

}
?>