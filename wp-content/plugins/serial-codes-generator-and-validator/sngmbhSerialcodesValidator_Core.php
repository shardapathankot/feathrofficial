<?php
include(plugin_dir_path(__FILE__)."init_file.php");
class sngmbhSerialcodesValidator_Core {
	private $MAIN;

	private $_CACHE_list = [];

	public $ticket_url_path_part = "ticket";

	public function __construct($MAIN) {
		if ($MAIN->getDB() == null) throw new Exception("#9999 DB needed");
		$this->MAIN = $MAIN;
	}

	private function getBase() {
		return $this->MAIN->getBase();
	}
	private function getDB() {
		return $this->MAIN->getDB();
	}

	public function clearCode($code) {
		return trim(urldecode(strip_tags(str_replace(" ","",str_replace(":","",str_replace("-", "", $code))))));
	}

	public function getListById($id) {
		$sql = "select * from ".$this->getDB()->getTabelle("lists")." where id = ".intval($id);
		$ret = $this->getDB()->_db_datenholen($sql);
		if (count($ret) == 0) throw new Exception("#9232 not found");
		return $ret[0];
	}
	public function getListByName($name) {
		$name = $this->getDB()->reinigen_in($name);
		$sql = "select * from ".$this->getDB()->getTabelle("lists")." where name = '".$name."'";
		$ret = $this->getDB()->_db_datenholen($sql);
		if (count($ret) == 0) throw new Exception("#9234 not found");
		return $ret[0];
	}

	public function getCodesByRegUserId($user_id) {
		$user_id = intval($user_id);
		if ($user_id <= 0) return [];
		$sql = "select a.* from ".$this->getDB()->getTabelle("codes")." a where user_id = ".$user_id;
		return $this->getDB()->_db_datenholen($sql);
	}

	public function getCodesByProductId($product_id) {
		$sql = "select a.*, order_id as o, b.name as list_name ";
		$sql .= " from ".$this->getDB()->getTabelle("codes")." a left join ".$this->getDB()->getTabelle("lists")." b on a.list_id = b.id";
		$sql .= " where";
		$sql .= " json_extract(a.meta, '$.woocommerce.product_id') = ".intval($product_id);
		$sql .= " order by a.time";
		$daten = $this->getDB()->_db_datenholen($sql);
		foreach($daten as $key => $item) {
			$daten[$key]['_customer_name'] = "";
			$metaObj = $this->encodeMetaValuesAndFillObject($item['meta']);
			$daten[$key]["metaObj"] = $metaObj;
			$order_id = intval($metaObj['woocommerce']['order_id']);
			if ($order_id > 0) {
				$order = wc_get_order( $order_id );
				if ($order != null) {
					$daten[$key]['_customer_name'] = $order->get_billing_first_name()." ".$order->get_billing_last_name();
				}
			}
		}
		return $daten;
	}

	public function retrieveCodeByCode($code, $mitListe=false) {
		$code = $this->clearCode($code);
		$code = $this->getDB()->reinigen_in($code);
		if (empty($code)) throw new Exception("#203 code empty");
		if ($mitListe) {
			$sql = "select a.*, b.name as list_name from ".$this->getDB()->getTabelle("codes")." a
					left join ".$this->getDB()->getTabelle("lists")." b on a.list_id = b.id
					where code = '".$code."'";
		} else {
			$sql = "select a.* from ".$this->getDB()->getTabelle("codes")." a where code = '".$code."'";
		}
		$ret = $this->getDB()->_db_datenholen($sql);
		if (count($ret) == 0) throw new Exception("#204 code not found");
		return $ret[0];
	}

	public function checkCodesSize() {
		if (!$this->getBase()->premiumCheck_isAllowedAddingCode($this->getDB()->getCodesSize())) throw new Exception("#208 too many codes. Unlimited codes only with premium");
	}

	public function retrieveCodeById($id, $mitListe=false) {
		$id = intval($id);
		if ($id == 0) throw new Exception("#220 id of code empty");
		if ($mitListe) {
			$sql = "select a.*, b.name as list_name from ".$this->getDB()->getTabelle("codes")." a
					left join ".$this->getDB()->getTabelle("lists")." b on a.list_id = b.id
					where a.id = ".$id;
		} else {
			$sql = "select a.* from ".$this->getDB()->getTabelle("codes")." a where a.id = ".$id;
		}
		$ret = $this->getDB()->_db_datenholen($sql);
		if (count($ret) == 0) throw new Exception("#221 code not found");
		return $ret[0];
	}

	public function getMetaObject() {
		$metaObj = [
			'validation'=>['first_success'=>'', 'first_ip'=>'', 'last_success'=>'', 'last_ip'=>'']
			,'user'=>['reg_approved'=>0,'reg_request'=>'','value'=>'','reg_ip'=>'', 'reg_userid'=>0, '_reg_username'=>'']
			,'used'=>['reg_ip'=>'', 'reg_request'=>'', 'reg_userid'=>0, '_reg_username'=>'']
			,'confirmedCount'=>0
			,'woocommerce'=>['order_id'=>0, 'product_id'=>0, 'creation_date'=>0, 'item_id'=>0, 'user_id'=>0] // product serial for sale
			,'wc_rp'=>['order_id'=>0, 'product_id'=>0, 'creation_date'=>0, 'item_id'=>0, 'extend_expiration_executed_at'=>'', 'extend_expiration_days'=>0, 'extend_expiration_history'=>[]] // restriction purchase used
			,'wc_ticket'=>['is_ticket'=>0, 'ip'=>'', 'userid'=>0, '_username'=>'', 'redeemed_date'=>'', 'redeemed_by_admin'=>0, 'set_by_admin'=>0, 'set_by_admin_date'=>'', 'idcode'=>'', '_url'=>''] // ticket purchase
			];

		if ($this->MAIN->isPremium() && method_exists($this->MAIN->getPremiumFunctions(), 'getMetaObject')) {
			$metaObj = $this->MAIN->getPremiumFunctions()->getMetaObject($metaObj);
		}

		return $metaObj;
	}
	public function encodeMetaValuesAndFillObject($metaValuesString, $codeObj=null) {
		$metaObj = $this->getMetaObject();
		if (!empty($metaValuesString)) {
			$metaObj = array_replace_recursive($metaObj, json_decode($metaValuesString, true));
		}
		if (isset($metaObj['user']['reg_userid']) && $metaObj['user']['reg_userid'] > 0) {
			$u = get_userdata($metaObj['user']['reg_userid']);
			if ($u === false) {
				$metaObj['user']['_reg_username'] = "USERID DO NOT EXISTS";
			} else {
				$metaObj['user']['_reg_username'] = $u->first_name." ".$u->last_name." (".$u->user_login.")";
			}
		} else {
			$metaObj['user']['_reg_username'] = "";
		}
		if (isset($metaObj['used']['reg_userid']) && $metaObj['used']['reg_userid'] > 0) {
			$u = get_userdata($metaObj['used']['reg_userid']);
			if ($u === false) {
				$metaObj['used']['_reg_username'] = "USERID DO NOT EXISTS";
			} else {
				$metaObj['used']['_reg_username'] = $u->first_name." ".$u->last_name." (".$u->user_login.")";
			}
		} else {
			$metaObj['used']['_reg_username'] = "";
		}
		if (isset($metaObj['wc_ticket']['userid']) && $metaObj['wc_ticket']['userid'] > 0) {
			$u = get_userdata($metaObj['wc_ticket']['userid']);
			if ($u === false) {
				$metaObj['wc_ticket']['_username'] = "USERID DO NOT EXISTS";
			} else {
				$metaObj['wc_ticket']['_username'] = $u->first_name." ".$u->last_name." (".$u->user_login.")";
			}
		} else {
			$metaObj['wc_ticket']['_username'] = "";
		}
		if (isset($metaObj['wc_ticket']['redeemed_by_admin']) && $metaObj['wc_ticket']['redeemed_by_admin'] > 0) {
			$u = get_userdata($metaObj['wc_ticket']['redeemed_by_admin']);
			if ($u === false) {
				$metaObj['wc_ticket']['_redeemed_by_admin_username'] = "USERID DO NOT EXISTS";
			} else {
				$metaObj['wc_ticket']['_redeemed_by_admin_username'] = $u->first_name." ".$u->last_name." (".$u->user_login.")";
			}
		} else {
			$metaObj['wc_ticket']['_redeemed_by_admin_username'] = "";
		}
		if (isset($metaObj['wc_ticket']['set_by_admin']) && $metaObj['wc_ticket']['set_by_admin'] > 0) {
			$u = get_userdata($metaObj['wc_ticket']['set_by_admin']);
			if ($u === false) {
				$metaObj['wc_ticket']['_set_by_admin_username'] = "USERID DO NOT EXISTS";
			} else {
				$metaObj['wc_ticket']['_set_by_admin_username'] = $u->first_name." ".$u->last_name." (".$u->user_login.")";
			}
		} else {
			$metaObj['wc_ticket']['_set_by_admin_username'] = "";
		}
		if ($metaObj['wc_ticket']['is_ticket'] == 1 && $codeObj != null && is_array($codeObj)) {
			if (empty($metaObj['wc_ticket']['idcode']))	$metaObj['wc_ticket']['idcode'] = crc32($codeObj['id']."-".time());
			$metaObj['wc_ticket']['_url'] = $this->getTicketURL($codeObj, $metaObj);
		}

		// update validation fields
		if ($metaObj['confirmedCount'] > 0) {
			if (empty($metaObj['validation']['first_success'])) {
				// check used wert
				if ( !empty($metaObj['used']['reg_request']) ) {
					$metaObj['validation']['first_success'] = $metaObj['used']['reg_request'];
					$metaObj['validation']['first_ip'] = $metaObj['used']['reg_ip'];
				} elseif (!empty($metaObj['user']['reg_request'])) { // check user reg wert
					$metaObj['validation']['first_success'] = $metaObj['user']['reg_request'];
					$metaObj['validation']['first_ip'] = $metaObj['user']['reg_ip'];
				}
			}
		}

		$url = $this->MAIN->getOptions()->getOptionValue("qrDirectURL");
		if (!empty($url)) {
			$codeObj["metaObj"] = $metaObj;
			$url = $this->replaceURLParameters($url, $codeObj); // loop danger - is calling the same function
			$metaObj['_QR']['directURL'] = trim($url);
		}

		if ($this->MAIN->isPremium() && method_exists($this->MAIN->getPremiumFunctions(), 'encodeMetaValuesAndFillObject')) {
			$felder = $this->MAIN->getPremiumFunctions()->encodeMetaValuesAndFillObject($metaObj, $codeObj);
		}
		return $metaObj;
	}

	public function getMetaObjectKeyList($metaObj, $prefix="META_") {
		$keys = [];
		$prefix = strtoupper(trim($prefix));
		foreach(array_keys($metaObj) as $key) {
			$tag = $prefix.strtoupper($key);
			if (is_array($metaObj[$key])) {
				$_keys = $this->getMetaObjectKeyList($metaObj[$key], $tag."_");
				$keys = array_merge($keys, $_keys);
			} else {
				$keys[] = $tag;
			}
		}
		return $keys;
	}

	public function getMetaObjectAllowedReplacementTags() {
		$tags = [];
		$allowed_tags = [
			"USER_VALUE"=>"Value given by the user during the code registration.",
			"USER_REG_IP"=>"IP address of the user, register to a code.",
			"USER_REG_USERID"=>"User id of the registered user to a code. Default will be 0.",
			"USED_REG_IP"=>"IP addres of the user that used the code.",
			"CONFIRMEDCOUNT"=>"Amount of how many times the code was validated successfully.",
			"WOOCOMMERCE_ORDER_ID"=>"WooCommerce order id assigned to the code.",
			"WOOCOMMERCE_PRODUCT_ID"=>"WooCommerce product id assigned to the code.",
			"WOOCOMMERCE_CREATION_DATE"=>"Creation date of the WooCommerce sales date.",
			"WOOCOMMERCE_USER_ID"=>"User id of the WooCommerce sales.",
			"WC_RP_ORDER_ID"=>"WooCommerce order id, that was purchases using this serial code as an allowance to purchase a restricted product.",
			"WC_RP_PRODUCT_ID"=>"WooCommerce product id that was restricted with this serial.",
			"WC_RP_CREATION_DATE"=>"Creation date of the WooCommerce purchase using the allowance code."
		];
		foreach($allowed_tags as $key => $value) {
			$tags[] = ["key"=>$key, "label"=>$value];
		}
		return $tags;
	}

	// returns a default meta object for a code list
	public function getMetaObjectList() {
		$metaObj = [
			'desc'=>'',
			'redirect'=>['url'=>''],
			'formatter'=>[
				'active'=>1,
				'format'=>'' // JSON mit den Format Werten
				]
		];
		if ($this->MAIN->isPremium() && method_exists($this->MAIN->getPremiumFunctions(), 'getMetaObjectList')) {
			$metaObj = $this->MAIN->getPremiumFunctions()->getMetaObjectList($metaObj);
		}
		return $metaObj;
	}

	public function encodeMetaValuesAndFillObjectList($metaValuesString) {
		$metaObj = $this->getMetaObjectList();
		if (!empty($metaValuesString)) {
			$metaObj = array_replace_recursive($metaObj, json_decode($metaValuesString, true));
		}
		return $metaObj;
	}

	public function json_encode_with_error_handling($object) {
		$json = json_encode($object, JSON_NUMERIC_CHECK);
		if (json_last_error() !== JSON_ERROR_NONE) {
			throw new Exception(json_last_error_msg());
		}
		return $json;
	}

	public function getRealIpAddr() {
	    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
	    {
	      $ip=sanitize_text_field($_SERVER['HTTP_CLIENT_IP']);
	    }
	    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
	    {
	      $ip=sanitize_text_field($_SERVER['HTTP_X_FORWARDED_FOR']);
	    }
	    else
	    {
	      $ip=sanitize_text_field($_SERVER['REMOTE_ADDR']);
	    }
	    return $ip;
	}

	public function triggerWebhooks($status, $codeObj) {
		$options = $this->MAIN->getOptions();
		if ($options->isOptionCheckboxActive('webhooksActiv')) {
			$optionname = "";
			switch($status) {
				case 0:
					$optionname = "webhookURLinvalid";
					break;
				case 1:
					$optionname = "webhookURLvalid";
					break;
				case 2:
					$optionname = "webhookURLinactive";
					break;
				case 3:
					$optionname = "webhookURLisregistered";
					break;
				case 4:
					$optionname = "webhookURLexpired";
					break;
				case 5:
					$optionname = "webhookURLmarkedused";
					break;
				case 6:
					$optionname = "webhookURLsetused";
					break;
				case 7:
					$optionname = "webhookURLregister";
					break;
				case 8:
					$optionname = "webhookURLipblocking";
					break;
				case 9:
					$optionname = "webhookURLipblocked";
					break;
				case 10:
					$optionname = "webhookURLaddwcinfotocode";
					break;
				case 11:
					$optionname = "webhookURLwcremove";
					break;
				case 12:
					$optionname = "webhookURLaddwcticketinfoset";
					break;
				case 13:
					$optionname = "webhookURLaddwcticketredeemed";
					break;
				case 14:
					$optionname = "webhookURLaddwcticketunredeemed";
					break;
				case 15:
					$optionname = "webhookURLaddwcticketinforemoved";
					break;
				case 16:
					$optionname = "webhookURLrestrictioncodeused";
					break;
			}
			if (!empty($optionname)) {
				$url = $options->getOption($optionname)['value'];
				if (!empty($url)) {
					$url = $this->replaceURLParameters($url, $codeObj);
					wp_remote_get($url);
				}
			}
		}
	}

	private function _getCachedList($list_id) {
		if (isset($this->_CACHE_list[$list_id])) return $this->_CACHE_list[$list_id];
		$this->_CACHE_list[$list_id] = $this->getListById($list_id);
		return $this->_CACHE_list[$list_id];
	}

	public function replaceURLParameters($url, $codeObj) {
		$url = str_replace("{CODE}", isset($codeObj['code']) ? $codeObj['code'] : '', $url);
		$url = str_replace("{CODEDISPLAY}", isset($codeObj['code_display']) ? $codeObj['code_display'] : '', $url);
		$url = str_replace("{IP}", $this->getRealIpAddr(), $url);
		$userid = '';
		if (is_user_logged_in()) {
			$userid = get_current_user_id();
		}
		$url = str_replace("{USERID}", $userid, $url);

		$listname = "";
		if (isset($codeObj['list_id']) && $codeObj['list_id'] > 0 && strpos($url, "{LIST}") !== false) {
			try {
				$listObj = $this->_getCachedList($codeObj['list_id']);
				$listname = $listObj['name'];
			} catch (Exception $e) {
			}
		}
		$url = str_replace("{LIST}", urlencode($listname), $url);

		$listdesc = "";
		if (isset($codeObj['list_id']) && $codeObj['list_id'] > 0 && strpos($url, "{LIST_DESC}") !== false) {
			try {
				$listObj = $this->_getCachedList($codeObj['list_id']);
				$metaObj = [];
				if (!empty($listObj['meta'])) $metaObj = $this->encodeMetaValuesAndFillObjectList($listObj['meta']);
				if (isset($metaObj['desc'])) $listdesc = $metaObj['desc'];
			} catch (Exception $e) {
			}
		}
		$url = str_replace("{LIST_DESC}", urlencode($listdesc), $url);

		$metaObj = [];
		if (!empty($codeObj['meta'])) {
			if (isset($codeObj['metaObj'])) { // will be set by the encodeMetaValuesAndFillObject to prevent a loop
				$metaObj = $codeObj['metaObj'];
			} else {
				$metaObj = $this->encodeMetaValuesAndFillObject($codeObj['meta'], $codeObj);
			}
		}
		if (count($metaObj) > 0) $url = $this->_replaceTagsInTextWithMetaObjectsValues($url, $metaObj, "META_");

		return $url;
	}

	private function _replaceTagsInTextWithMetaObjectsValues($text, $metaObj, $prefix="") {
		$prefix = strtoupper(trim($prefix));
		foreach(array_keys($metaObj) as $key) {
			$tag = $prefix.strtoupper($key);
			if (is_array($metaObj[$key])) {
				$text = $this->_replaceTagsInTextWithMetaObjectsValues($text, $metaObj[$key], $tag."_");
			} else {
				$text = str_replace("{".$tag."}", urlencode($metaObj[$key]), $text);
			}
		}
		return $text;
	}

	public function checkCodeExpired($codeObj) {
		if ($this->MAIN->isPremium() && method_exists($this->MAIN->getPremiumFunctions(), 'checkCodeExpired')) {
			if ($this->MAIN->getPremiumFunctions()->checkCodeExpired($codeObj)) {
				return true;
			}
		}
		return false;
	}
	public function isCodeIsRegistered($codeObj) {
		$meta = [];
		if (!empty($codeObj['meta'])) $meta = $this->encodeMetaValuesAndFillObject($codeObj['meta'], $codeObj);
		if (isset($meta['user']) && isset($meta['user']['value']) && !empty($meta['user']['value'])) {
			return true;
		}
		return false;
	}

	public function getTicketURLBase() {
		return plugin_dir_url(__FILE__).$this->ticket_url_path_part."/";
	}
	public function getTicketId($codeObj, $metaObj) {
		if (isset($codeObj['code']) && isset($codeObj['order_id']) && isset($metaObj['wc_ticket']['idcode'])) {
			return $metaObj['wc_ticket']['idcode']."-".$codeObj['order_id']."-".$codeObj['code'];
		} else {
			return "";
		}
	}
	public function getTicketURL($codeObj, $metaObj) {
		$ticket_id = $this->getTicketId($codeObj, $metaObj);
		$baseURL = $this->getTicketURLBase();
		$url = $baseURL.$ticket_id;
		if ($this->MAIN->getOptions()->isOptionCheckboxActive('wcTicketCompatibilityMode')) {
			$url = $baseURL."?code=".$ticket_id;
		}
		return $url;
	}
	public function getTicketURLPath() {
		$p = $this->getTicketURLBase();
		$teile = parse_url($p);
		return $teile['path'];
	}
	public function getTicketURLComponents($url) {
		$teile = explode("/", $url);
		$teile = array_reverse($teile);
		$ret = "";
		$request = "";
		$is_pdf_request = false;
		foreach($teile as $teil) {
			$teil = trim($teil);
			if (empty($teil)) continue;
			if ($teil == $this->ticket_url_path_part) break;
			$ret = $teil;
			break;
		}
		if (isset($_GET['code'])) {
			$parts = explode("-", trim($_GET['code']));
			$t = explode("?", $url);
			if (count($t) > 1) {
				unset($t[0]);
				$request = join("&", $t);
			}
			$is_pdf_request = in_array("pdf", $t);
		} else {
			if (empty($ret)) throw new Exception("#9301 ticket id not found");
			$parts = explode("-", $ret);
			$t = explode("?", $parts[2]);
			$parts[2] = $t[0];
			if (count($t) > 1) {
				unset($t[0]);
				$request = join("&", $t);
			}
			$is_pdf_request = in_array("pdf", $t);
		}
		if (count($parts) != 3) throw new Exception("#9302 ticket id not correct");
		$parts[2] = str_replace("?pdf", "", $parts[2]);
		$parts_assoc = [
			"idcode"=>$parts[0],
			"order_id"=>$parts[1],
			"code"=>$parts[2],
			"_request"=>$request,
			"_isPDFRequest"=>$is_pdf_request
		];
		return $parts_assoc;
	}

	public function my_upgrade_function( $upgrader_object, $options ) {
    	$current_plugin_path_name = plugin_basename( __FILE__ );
    	if ($options['action'] == 'update' && $options['type'] == 'plugin' ) {
       		foreach($options['plugins'] as $each_plugin) {
          		if ($each_plugin==$current_plugin_path_name) {
             	// .......................... YOUR CODES .............

          		}
       		}
    	}
	}

}
?>