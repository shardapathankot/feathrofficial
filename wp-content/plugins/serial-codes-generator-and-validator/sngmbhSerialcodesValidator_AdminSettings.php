<?php
include(plugin_dir_path(__FILE__)."init_file.php");
class sngmbhSerialcodesValidator_AdminSettings {
	private $MAIN;

	public function __construct($MAIN) {
		$this->MAIN = $MAIN;
	}
	public static function plugin_uninstall(){
		//delete_option
		//delete tabellen
	}
	public function getCore() {
		return $this->MAIN->getCore();
	}
	private function getBase() {
		return $this->MAIN->getBase();
	}
	private function getDB() {
		return $this->MAIN->getDB();
	}
	public function executeJSON($a, $data=[], $just_ret=false) {
		$ret = "";
		$justJSON = false;
		try {
			switch (trim($a)) {
				case "getLists":
					$ret = $this->getLists();
					break;
				case "getList":
					$ret = $this->getList($data);
					break;
				case "addList":
					$ret = $this->addList($data);
					break;
				case "editList":
					$ret = $this->editList($data);
					break;
				case "removeList":
					$ret = $this->removeList($data);
					break;
				case "getCodes":
					$ret = $this->getCodes($data, $_GET);
					$justJSON = true;
					break;
				case "addCode":
					$ret = $this->addCode($data);
					break;
				case "addCodes":
					$ret = $this->addCodes($data);
					break;
				case "editCode":
					$ret = $this->editCode($data);
					break;
				case "removeCode":
					$ret = $this->removeCode($data);
					break;
				case "removeCodes":
					$ret = $this->removeCodes($data);
					break;
				case "emptyTableLists":
					$ret = $this->emptyTableLists($data);
					break;
				case "emptyTableCodes":
					$ret = $this->emptyTableCodes($data);
					break;
				case "exportTableCodes":
					$ret = $this->exportTableCodes($data);
					break;
				case "importLists":
					$ret = $this->importLists($data);
					break;
				case "premium":
					$ret = $this->executeJSONPremium($data);
					break;
				case "removeWoocommerceOrderInfoFromCode":
					$ret = $this->removeWoocommerceOrderInfoFromCode($data);
					break;
				case "removeWoocommerceRstrPurchaseInfoFromCode":
					$ret = $this->removeWoocommerceRstrPurchaseInfoFromCode($data);
					break;
				case "setWoocommerceTicketForCode":
					$ret = $this->setWoocommerceTicketForCode($data);
					break;
				case "redeemWoocommerceTicketForCode":
					$ret = $this->redeemWoocommerceTicketForCode($data);
					break;
				case "removeRedeemWoocommerceTicketForCode":
					$ret = $this->removeRedeemWoocommerceTicketForCode($data);
					break;
				case "removeWoocommerceTicketForCode":
					$ret = $this->removeWoocommerceTicketForCode($data);
					break;
				case "getOptions":
					$ret = $this->getOptions();
					break;
				case "changeOption":
					$ret = $this->changeOption($data);
					break;
				case "getMetaOfCode":
					$ret = $this->getMetaOfCode($data);
					break;
				case "removeUserRegistrationFromCode":
					$ret = $this->removeUserRegistrationFromCode($data);
					break;
				case "editUseridForUserRegistrationFromCode":
					$ret = $this->editUseridForUserRegistrationFromCode($data);
					break;
				case "removeUsedInformationFromCode":
					$ret = $this->removeUsedInformationFromCode($data);
					break;
				case "editUseridForUsedInformationFromCode":
					$ret = $this->editUseridForUsedInformationFromCode($data);
					break;
				case "repairTables":
					$ret = $this->repairTables($data);
					break;
				case "getSupportInfos":
					$ret = $this->getSupportInfos($data);
					break;
				case "testing":
					$ret = $this->testing($data);
					break;
				default:
					throw new Exception("function '".$a."' not implemented");
			}
		} catch(Exception $e) {
			if ($just_ret) throw $e;
			return wp_send_json_error ($e->getMessage());
		}
		if ($just_ret) return $ret;
		if ($justJSON) return wp_send_json($ret);
		else return wp_send_json_success( $ret );
	}
	private function executeJSONPremium($data) {
		if (!$this->MAIN->isPremium()) throw new Exception("#9001 premium is not active");
		if (!isset($data['c'])) throw new Exception("#9002 premium action is missing");
		return $this->MAIN->getPremiumFunctions()->executeJSON($data['c'], $data);
	}

	private function repairTables() {
		$this->getDB()->installiereTabellen(true);
		do_action($this->MAIN->_do_action_prefix."repairTables", true);

		// aktualisiere nicht erfasste userid für orders
		$sql = "select * from ".$this->getDB()->getTabelle("codes")." where
			order_id != 0 and
			json_extract(meta, '$.wc_ticket.is_ticket') = 1 and
			json_extract(meta, '$.woocommerce.user_id') is null
		";
		$d = $this->getDB()->_db_datenholen($sql);
		if (count($d) > 0) {
			foreach($d as $codeObj) {
				$metaObj = $this->getCore()->encodeMetaValuesAndFillObject($codeObj['meta'], $codeObj);
				$order = wc_get_order( $codeObj['order_id'] );
				$metaObj['woocommerce']['user_id'] = intval($order->get_user_id());
				$codeObj['meta'] = $this->getCore()->json_encode_with_error_handling($metaObj);
				$this->getDB()->update("codes", ["meta"=>$codeObj['meta']], ['id'=>$codeObj['id']]);
			}
		}

		return "tables repair executed at ".date("Y/m/d H:i:s");
	}

	private function getSupportInfos($data) {
		$codes_size = $this->getDB()->getCodesSize();
		$lists_size = $this->getDB()->_db_getRecordCountOfTable('lists');
		return [
			"amount"=>["codes"=>$codes_size, "lists"=>$lists_size]
		];
	}

	public function getOptions() {
		global $wpdb, $wp_version ;
		$options = $this->MAIN->getOptions()->getOptions();

		$metaObj = $this->getCore()->getMetaObject();
		$keys = $this->getCore()->getMetaObjectKeyList($metaObj, "");
		$tags = $this->getCore()->getMetaObjectAllowedReplacementTags();

		$pversions = $this->MAIN->getPluginVersions();
		$premium_db_version = '';
		if ($this->MAIN->isPremium() && method_exists($this->MAIN->getPremiumFunctions(), 'getDBVersion')) {
			$premium_db_version = $this->MAIN->getPremiumFunctions()->getDBVersion();
		}
		$current = get_site_transient( 'update_core' );
		$mysql_version = 'N/A';
		if ( method_exists( $wpdb, 'db_version' ) ) {
			$mysql_version = preg_replace( '/[^0-9.].*/', '', $wpdb->db_version() );
		}
		$versions = [
			'php'=>phpversion(),
			'wp'=>$wp_version,
			'mysql'=>$mysql_version,
			'db'=>$this->getDB()->dbversion,
			'premium_db'=>$premium_db_version,
			'basic'=>$pversions['basic'],
			'premium'=>$pversions['premium'] != "" ? $pversions['premium'] : '',
			'is_wc_available'=>class_exists( 'WooCommerce' ) ? 1 : 0
		];
		$infos = [
			'ticket'=>['ticket_base_url'=>$this->getCore()->getTicketURLBase(), 'ticket_detail_path'=>$this->getCore()->getTicketURLPath(), 'ticket_scanner_path'=>$this->getCore()->getTicketURLPath().'scanner/'],
			'site'=>['is_multisite'=>is_multisite() ? 1 : 0, 'home'=>home_url(), 'network_home'=>network_home_url(), 'site_url'=>site_url()]
		];
		if (is_admin()) {
			return ['options'=>$options, 'meta_tags_keys'=>$tags, 'versions'=>$versions, 'infos'=>$infos];
		}
		return ['options'=>$options, 'meta_tags_keys'=>$tags, 'versions'=>[], 'infos'=>[]];
	}
	public function changeOption($data) {
		$this->MAIN->getOptions()->changeOption($data);
	}
	public function getOptionValue($name, $defvalue="") {
		return $this->MAIN->getOptions()->getOptionValue($name, $defvalue);
	}
	public function isOptionCheckboxActive($name) {
		return $this->MAIN->getOptions()->isOptionCheckboxActive($name);
	}

	private function importLists($data) {
		if (!isset($_FILES)) throw new Exception("#9401 CSV file for list is missing");
		set_time_limit(0);

		$total = 0; // zeilen
		$lines = 0; // nicht leere zeilen
		$imported = 0;
		$counter_codes = 0;
		$counter_codes_created = 0;
		$counter_codes_updated = 0;
		$counter_list_created = 0;
		$counter_list_updated = 0;

		$delimiter = isset($data['delimiter']) ? trim($data['delimiter']) : ",";
		if (!in_array($delimiter, [';', ','])) $delimiter = ',';

		$columns_pos = ["name"=>-1, "desc"=>-1, "codes"=>-1];

		$csvfile = $_FILES['file'];
		if (($handle = fopen($csvfile['tmp_name'], "r")) !== FALSE) {
			ob_start();
			while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
				$total++;
				if ($total == 1) {
					// header
					foreach($data as $idx => $column) {
						$column = strtolower(trim($column));
						$columns_pos[$column] = $idx;
					}
					if ($columns_pos["name"] < 0) throw new Exception("#9403 name colum missing");
					continue;
				}
				if (count($data)>0) {
					$lines++;
					$list_name = trim($data[$columns_pos["name"]]);
					if (empty($list_name)) continue;
					$list_desc = $columns_pos["desc"] >= 0 ?  trim($data[$columns_pos["desc"]]) : "";
					$list_codes = $columns_pos["codes"] >= 0 ?  trim($data[$columns_pos["codes"]]) : "";
					$codes = [];
					if (!empty($list_codes)) {
						$code_parts = explode(";", $list_codes);
						foreach($code_parts as $code) {
							$codes[] = $this->getCore()->clearCode($code);
						}
					}
					// prepare metadata values
					$meta = ["desc"=>$list_desc];
					foreach (array_keys($columns_pos) as $key) {
						if (strlen($key) > 5 && substr($key, 0, 5) == "meta_") {
							$parts = explode("_", $key);
							if (count($parts) == 3 && isset($columns_pos[$key]) && isset($data[$columns_pos[$key]])) {
								$meta[trim($parts[1])][trim($parts[2])] = trim($data[$columns_pos[$key]]);
							}
						}
					}
					// update or create list
					$list_id = 0;
					try {
						$listObj = $this->getCore()->getListByName($list_name);
						$list_id = $listObj['id'];
						$_data = ["id"=>$list_id, "name"=>$list_name, 'meta'=>$meta];
						$this->_editList($_data);
						$counter_list_updated++;
					} catch(Exception $e) {
						$_data = ["name"=>$list_name, 'meta'=>$meta];
						$list_id = $this->_addList($_data);
						$counter_list_created++;
					}
					if ($list_id == 0) throw new Exception("#9402 List could not be created or loaded");

					// codes?
					if (count($codes)) {
						foreach($codes as $code) {
							if (empty($code)) continue;
							// create if needed
							$counter_codes++;
							try {
								$codeObj = $this->getCore()->retrieveCodeByCode($code);
								// code assign
								$_data = ["code"=>$code, "list_id"=>$list_id];
								$this->editCode($_data);
								$counter_codes_updated++;
							} catch (Exception $e) {
								$this->_addCode($code, $list_id);
								$counter_codes_created++;
							}
						}
					}

					$imported++;
				}
			}
			ob_end_clean();
			@fclose($handle);
			@unlink($csvfile['tmp_name']);
		}

		return ["total"=>$total, "imported"=>$imported,
		"lines"=>$lines,
		"counter_codes_updated"=>$counter_codes_updated,
		"counter_codes_created"=>$counter_codes_created,
		"counter_codes"=>$counter_codes,
		"counter_list_created"=>$counter_list_created, "counter_list_updated"=>$counter_list_updated];
	}

	public function getMetaOfCode($data) {
		if (!isset($data['code'])) throw new Exception("#9101 code is missing");
		$codeObj = $this->getCore()->retrieveCodeByCode($data['code']);
		$metaObj = $this->getCore()->encodeMetaValuesAndFillObject($codeObj['meta'], $codeObj);

		if (class_exists( 'WooCommerce' )) {
			// load user info of woocommerce sale
		}

		/* is now in core->encodeMetaValuesAndFillObject
		$url = $this->getOptionValue("qrDirectURL");
		if (!empty($url)) {
			$url = $this->getCore()->replaceURLParameters($url, $codeObj);
			$metaObj['_QR']['directURL'] = trim($url);
		}
		*/

		return $metaObj;
	}

	private function removeUserRegistrationFromCode($data) {
		if(!isset($data['code'])) throw new Exception("#9221 code missing");
		$codeObj = $this->getCore()->retrieveCodeByCode($data['code']);
		$metaObj = $this->getCore()->encodeMetaValuesAndFillObject($codeObj['meta'], $codeObj);
		$metaObj['user']['value'] = "";
		$metaObj['user']['reg_ip'] = "";
		$metaObj['user']['reg_approved'] = 0;
		$metaObj['user']['reg_request'] = "";
		$metaObj['user']['reg_userid'] = 0;
		$codeObj['meta'] = $this->getCore()->json_encode_with_error_handling($metaObj);
		$this->getDB()->update("codes", ["meta"=>$codeObj['meta'], "user_id"=>0], ['id'=>$codeObj['id']]);
		do_action( $this->MAIN->_do_action_prefix.'removeUserRegistrationFromCode', $data, $codeObj );
		return $codeObj;
	}
	public function registerUserIdToCode($data) {
		$codeObj = $this->getCore()->retrieveCodeByCode($data['code']);
		// speicher neue registrierung
		$metaObj = $this->getCore()->encodeMetaValuesAndFillObject($codeObj['meta'], $codeObj);
		if (isset($data['value'])) $metaObj['user']['value'] = htmlentities(trim($data['value']));
		$metaObj['user']['reg_ip'] = $this->getCore()->getRealIpAddr();
		$metaObj['user']['reg_approved'] = 1; // auto approval
		if (empty($metaObj['user']['reg_request'])) $metaObj['user']['reg_request'] = date("Y-m-d H:i:s");
		if (isset($data['reg_userid'])) $metaObj['user']['reg_userid'] = intval($data['reg_userid']);
		$codeObj['meta'] = $this->getCore()->json_encode_with_error_handling($metaObj);
		$this->getDB()->update("codes", ["meta"=>$codeObj['meta'], "user_id"=>$metaObj['user']['reg_userid']], ['id'=>$codeObj['id']]);
		// send webhook if activated
		$this->getCore()->triggerWebhooks(7, $codeObj);
		return $codeObj;
	}
	private function editUseridForUserRegistrationFromCode($data) {
		if(!isset($data['code'])) throw new Exception("#9222 code missing");
		//if(!isset($data['value'])) throw new Exception("#9223 value missing");
		$codeObj = $this->registerUserIdToCode($data);
		$metaObj = $this->getCore()->encodeMetaValuesAndFillObject($codeObj['meta'], $codeObj);
		do_action( $this->MAIN->_do_action_prefix.'editUseridForUserRegistrationFromCode', $data, $codeObj, $metaObj );
		return $codeObj;
	}
	public function removeUsedInformationFromCode($data) {
		if(!isset($data['code'])) throw new Exception("#9231 code missing");
		$codeObj = $this->getBase()->getCORE($this->getDB())->retrieveCodeByCode($data['code']);
		$metaObj = $this->getBase()->getCORE($this->getDB())->encodeMetaValuesAndFillObject($codeObj['meta'], $codeObj);
		$metaObj['used']['reg_ip'] = "";
		$metaObj['used']['reg_request'] = "";
		$metaObj['confirmedCount'] = 0;
		$codeObj['meta'] = $this->getBase()->getCORE($this->getDB())->json_encode_with_error_handling($metaObj);
		$this->getDB()->update("codes", ["meta"=>$codeObj['meta']], ['id'=>$codeObj['id']]);
		do_action( $this->MAIN->_do_action_prefix.'removeUsedInformationFromCode', $data, $codeObj );
		return $codeObj;
	}
	private function editUseridForUsedInformationFromCode($data) {
		if(!isset($data['code'])) throw new Exception("#9233 code missing");
		$codeObj = $this->getCore()->retrieveCodeByCode($data['code']);
		// speicher neue registrierung
		$metaObj = $this->getCore()->encodeMetaValuesAndFillObject($codeObj['meta'], $codeObj);
		$metaObj['used']['reg_ip'] = $this->getCore()->getRealIpAddr();
		if (empty($metaObj['used']['reg_request'])) $metaObj['used']['reg_request'] = date("Y-m-d H:i:s");
		if (isset($data['reg_userid'])) $metaObj['used']['reg_userid'] = intval($data['reg_userid']);
		$codeObj['meta'] = $this->getCore()->json_encode_with_error_handling($metaObj);
		$this->getDB()->update("codes", ["meta"=>$codeObj['meta']], ['id'=>$codeObj['id']]);
		// send webhook if activated
		$this->getCore()->triggerWebhooks(6, $codeObj);
		do_action( $this->MAIN->_do_action_prefix.'editUseridForUsedInformationFromCode', $data, $codeObj );
		return $codeObj;
	}

	private function _setMetaDataForList($data, $metaObj) {
		if (isset($data['meta'])) {
			if (isset($data['meta']['desc'])) {
				$metaObj['desc'] = trim($data['meta']['desc']);
			}
			if (isset($data['meta']['formatter']['active'])) {
				$metaObj['formatter']['active'] = 0;
				if ($data['meta']['formatter']['active'] == 1) $metaObj['formatter']['active'] = 1;
			}
			if (isset($data['meta']['formatter']['format'])) {
				$metaObj['formatter']['format'] = trim($data['meta']['formatter']['format']);
			}
			if (isset($data['meta']['redirect'])) {
				if (isset($data['meta']['redirect']['url'])) {
					$metaObj['redirect']['url'] = trim($data['meta']['redirect']['url']);
				}
				if (isset($data['meta']['redirect']['btn'])) {
					$metaObj['redirect']['btn'] = trim($data['meta']['redirect']['btn']);
				}
				if (isset($data['meta']['redirect']['btndontshow'])) {
					$metaObj['redirect']['btndontshow'] = intval($data['meta']['redirect']['btndontshow']) == 1 ? 1 : 0;
				}
				if (isset($data['meta']['redirect']['isdisabled'])) {
					$metaObj['redirect']['isdisabled'] = intval($data['meta']['redirect']['isdisabled']) == 1 ? 1 : 0;
				}
			}
		}
		return $metaObj;
	}

	public function generateFirstCodeList() {
		$lists = $this->_getLists();
		if (count($lists) == 0) {
			$data = ['name'=>'Code list'];
			$this->_addList($data);
		}
	}

	public function _getList($data) {
		if (!isset($data['id'])) throw new Exception("#104 id is missing");
		$sql = "select * from ".$this->getDB()->getTabelle("lists")." where id = ".intval($data['id']);
		$ret = $this->getDB()->_db_datenholen($sql);
		if (count($ret) == 0) throw new Exception("#105 not found");
		return $ret[0];
	}
	public function getList($data) {
		add_filter( $this->MAIN->_add_filter_prefix.'getList', [$this, '_getList'], 10, 1 );
		return apply_filters( $this->MAIN->_add_filter_prefix.'getList', $data );
	}

	public function _getLists() {
		$sql = "select * from ".$this->getDB()->getTabelle("lists")." order by name asc";
		return $this->getDB()->_db_datenholen($sql);
	}

	public function getLists() {
		add_filter( $this->MAIN->_add_filter_prefix.'getLists', [$this, '_getLists'], 10 );
		return apply_filters( $this->MAIN->_add_filter_prefix.'getLists', 1);
	}

	public function _addList($data) {
		if (!isset($data['name']) || trim($data['name']) == "") throw new Exception("#101 name missing");
		if (!$this->getBase()->premiumCheck_isAllowedAddingList($this->getDB()->_db_getRecordCountOfTable('lists'))) throw new Exception("#108 too many codes. Unlimited codes only with premium");
		$data['name'] = strip_tags($data['name']);

		$listObj = ['meta'=>''];
		$metaObj = $this->getCore()->encodeMetaValuesAndFillObjectList($listObj['meta']);

		$felder = ["name"=>$data['name'], "aktiv"=>1, "time"=>date("Y-m-d H:i:s")];

		$metaObj = $this->_setMetaDataForList($data, $metaObj);

		if ($this->MAIN->isPremium()) $felder = $this->MAIN->getPremiumFunctions()->setFelderListEdit($felder, $data, $listObj, $metaObj);
		if (isset($felder['meta']) && !empty($felder['meta'])) { // evtl gesetzt vom premium plugin
			$metaObj = array_replace_recursive($metaObj, json_decode($felder['meta'], true));
		}
		$felder["meta"] = $this->getCore()->json_encode_with_error_handling($metaObj);

		try {
			return $this->getDB()->insert("lists", $felder);
		} catch(Exception $e) {
			throw new Exception("Could not create code list. Name exists already.");
		}
	}

	private function addList($data) {
		add_filter( $this->MAIN->_add_filter_prefix.'addList', [$this, '_addList'], 10, 1 );
		return apply_filters( $this->MAIN->_add_filter_prefix.'addList', $data);
	}

	public function _editList($data) {
		if (!isset($data['name']) || trim($data['name']) == "") throw new Exception("#102 name missing");
		if (!isset($data['id']) || intval($data['id']) == 0) throw new Exception("#103 id missing");
		$data['name'] = strip_tags($data['name']);

		$listObj = $this->getList($data);
		$metaObj = $this->getCore()->encodeMetaValuesAndFillObjectList($listObj['meta']);

		$felder["name"] = $data['name'];

		$metaObj = $this->_setMetaDataForList($data, $metaObj);

		if ($this->MAIN->isPremium()) $felder = $this->MAIN->getPremiumFunctions()->setFelderListEdit($felder, $data, $listObj, $metaObj);
		if (isset($felder['meta']) && !empty($felder['meta'])) { // evtl gesetzt vom premium plugin
			$metaObj = array_replace_recursive($metaObj, json_decode($felder['meta'], true));
		}
		$felder["meta"] = $this->getCore()->json_encode_with_error_handling($metaObj);

		$where = ["id"=>intval($data['id'])];
		return $this->getDB()->update("lists", $felder, $where);
	}

	public function editList($data) {
		add_filter( $this->MAIN->_add_filter_prefix.'editList', [$this, '_editList'], 10, 1 );
		return apply_filters( $this->MAIN->_add_filter_prefix.'editList', $data);
	}

	private function removeList($data) {
		if (!isset($data['id'])) throw new Exception("#106 id is missing");
		$sql = "update ".$this->getDB()->getTabelle("codes")." set list_id = 0 where list_id = ".intval($data['id']);
		$this->getDB()->_db_query($sql);
		$sql = "delete from ".$this->getDB()->getTabelle("lists")." where id = ".intval($data['id']);
		$ret = $this->getDB()->_db_query($sql);
		do_action( $this->MAIN->_do_action_prefix.'removeList', $data );
		return $ret;
	}

	private function getCode($data) {
		if (!isset($data['code']) || trim($data['code']) == "") throw new Exception("#202 code missing");
		return $this->getCore()->retrieveCodeByCode($data['code'], true);
	}
	private function getCodes($data, $request) {
		$sql = "select a.*, order_id as o, b.name as list_name from ".$this->getDB()->getTabelle("codes")." a
				left join ".$this->getDB()->getTabelle("lists")." b on a.list_id = b.id";

		// für datatables
		$length = 0; // wieviele pro seite angezeigt werden sollen (limit)
		if (isset($request['length'])) $length = intval($request['length']);
		$draw = 1; // sequenz zähler, also fortlaufend für jede aktion auf der JS datentabelle
		if (isset($request['draw'])) $draw = intval($request['draw']);
		$start = 0;
		if (isset($request['start'])) $start = intval($request['start']);
		$order_column = "code";

		$displayAdminAreaColumnBillingName = $this->isOptionCheckboxActive('displayAdminAreaColumnBillingName');

		if (isset($request['order'])) {
			$order_columns = array('', '', 'code');
			if ($displayAdminAreaColumnBillingName) $order_columns[] = '';
			$order_columns[] = 'list_name';
			$order_columns[] = 'time';
			$order_columns[] = 'order_id';
			$order_columns[] = '';
			$order_columns[] = 'aktiv';
			$order_columns[] = "json_extract(a.meta, '$.used.reg_request')";
			$order_column = $order_columns[intval($request['order'][0]['column'])];
		}
		$order_dir = "asc";

		if (isset($request['order']) && $request['order'][0]['dir'] == 'desc') $order_dir = "desc";
		$search = "";
		if (isset($request['search'])) $search = $this->getDB()->reinigen_in($request['search']['value']);

		$where = "";
		if ($search != "") {
			$sql .= " where ";

			$nomatch = true;

			$matches = [];
			preg_match('/\s?LIST:\s*([0-9]*)/', $search, $matches);
			if ($matches && count($matches) > 1) {
				$list_id = intval($matches[1]);
				$where .= " a.list_id = ".$list_id." ";
				$nomatch = false;
			} else {
				preg_match('/\s?LIST:\s*\*/', $search, $matches);
				if ($matches && count($matches) > 1) {
					$where .= " a.list_id > 0 ";
					$nomatch = false;
				}
			}
			$matches = [];
			preg_match('/\s?ORDERID:\s*([0-9]+)/', $search, $matches);
			if ($matches && count($matches) > 1) {
				$order_id = intval($matches[1]);
				if ($nomatch == false) $where .= " and ";
				$where .= " a.order_id = ".$order_id." ";
				$nomatch = false;
			} else {
				$matches = [];
				preg_match('/\s?ORDERID:\s*\*/', $search, $matches);
				if ($matches && count($matches) > 0) {
					if ($nomatch == false) $where .= " and ";
					$where .= " a.order_id > 0 ";
					$nomatch = false;
				}
			}
			preg_match('/\s?CVV:\s*([^\s]*)/', $search, $matches);
			if ($matches && count($matches) > 1) {
				$cvv = $matches[1];
				if ($nomatch == false) $where .= " and ";
				$where .= " a.cvv = '".sanitize_text_field($cvv)."' ";
				$nomatch = false;
			}
			preg_match('/\s?STATUS:\s*([0-9]*)/', $search, $matches);
			if ($matches && count($matches) > 1) {
				$status = intval($matches[1]);
				if ($nomatch == false) $where .= " and ";
				$where .= " a.aktiv = ".$status." ";
				$nomatch = false;
			}
			preg_match('/\s?USERID:\s*([0-9]*)/', $search, $matches);
			if ($matches && count($matches) > 1) {
				$user_id = intval($matches[1]);
				if ($nomatch == false) $where .= " and ";
				$where .= " a.user_id = ".$user_id." ";
				$nomatch = false;
			}

			if ($nomatch) {
				$where .= " a.code like '%".$this->getCore()->clearCode($search)."%' or b.name like '%".$search."%' or a.time like '%".$search."%' ";
				if (intval($search) > 0) {
					$where .= " or a.order_id = ".intval($search)." ";
				}
			}

			$sql .= $where;
		}
		if ($order_column != "") $sql .= " order by ".$order_column." ".$order_dir;
		if ($length > 0)
			$sql .= " limit ".$start.", ".$length;

		$daten = $this->getDB()->_db_datenholen($sql);
		$recordsTotal = $this->getDB()->_db_getRecordCountOfTable('codes');
		$recordsTotalFilter = $recordsTotal;
		if (!empty($where)) {
			$sql = "select count(a.id) as anzahl
					from
					".$this->getDB()->getTabelle("codes")." a left join
					".$this->getDB()->getTabelle("lists")." b on a.list_id = b.id
					where ".$where;
			list($d) = $this->getDB()->_db_datenholen($sql);
			$recordsTotalFilter = $d['anzahl'];
		}

		if ($displayAdminAreaColumnBillingName) {
			foreach($daten as $key => $item) {
				$daten[$key]['_customer_name'] = $this->getCustomerName($item['order_id']);
			}
		}

		return ["draw"=>$draw,
				"recordsTotal"=>intval($recordsTotal),
				"recordsFiltered"=>intval($recordsTotalFilter),
				"data"=>$daten];
	}
	public function getCustomerName($order_id) {
		$ret = "";
		$order_id = intval($order_id);
		if ($order_id > 0) {
			try {
				$order = wc_get_order( $order_id );
				if ($order != null) {
					$ret = $order->get_billing_first_name()." ".$order->get_billing_last_name();
				}
			} catch (Exception $e) {}
		}
		return $ret;
	}
	private function _addCode($newcode, $list_id=0) {
		$cvv = "";
		$teile = explode(";", $newcode);
		if (count($teile) > 1) {
			$newcode = trim($teile[0]);
			$cvv = trim($teile[1]);
		}
		$code = $this->getCore()->clearCode($newcode);
		if (empty($code)) {
			throw new Exception("Code is empty");
		}
		try {
			$this->getCore()->retrieveCodeByCode($code, false);
		} catch(Exception $e) { // not found -> add new
			$this->getCore()->checkCodesSize();
			$felder = ["code"=>$code, "code_display"=>sanitize_text_field($newcode), "cvv"=>$cvv, "aktiv"=>1, "time"=>date("Y-m-d H:i:s"), "meta"=>"", "list_id"=>$list_id];
			return $this->getDB()->insert("codes", $felder);
		}
		throw new Exception("#205 code exists already");
	}

	public function addCodeFromListForOrder($list_id, $order_id, $product_id = 0, $item_id = 0, $formatterValues="") {
		// item_id is in der order der eintrag
		$list_id = intval($list_id);
		if ($list_id == 0) throw new Exception("#602 list id invalid");
		$listObj = $this->getList(['id'=>$list_id]); // throws #104, #105
		// uses a list to define the autogenerated code, that will be taken (if not used codes exists) or store a new generated code to the list
		$data = ["code"=>"", "list_id"=>$list_id, "order_id"=>$order_id, "semaphorecode"=>""]; // semaphorecode wird beim speichern wieder frei gemacht
		$id = 0;
		// check if option is activate to reuse not purchased codes from the code list assigned to the woocommerce product
		if ($this->isOptionCheckboxActive('wcassignmentReuseNotusedCodes')) {
			// semaphore to prevent stealing code on heavy loaded servers
			$semaphorecode = md5(SNGMBH::PasswortGenerieren() . microtime(). "_". rand());
			$rescueCounter = 0;
			while($rescueCounter < 50) {
				// get a code from the list with order_id = 0
				$sql = "select id from ".$this->getDB()->getTabelle("codes")." where
						order_id = 0 and semaphorecode = '' and
						list_id = ".$list_id."
						limit 1";
				$d = $this->getDB()->_db_datenholen($sql);
				if (count($d) == 0) {
					break; // if no unregistered code could be found => create a new one
				} else {
					//if (SNGMBH::issetRPara('a') && SNGMBH::getRequestPara('a') == 'testing') echo "<p>FOUND UNUSED</p>";
					// update it with a random code if not already a code is assigned
					$this->getDB()->update("codes", ['semaphorecode'=>$semaphorecode], ['id'=>$d[0]['id'], 'semaphorecode'=>'']);
					// retrieve the code again and check if the random code could be set
					$sql = "select id, code_display, semaphorecode from ".$this->getDB()->getTabelle("codes")." where id = ".$d[0]['id'];
					$d = $this->getDB()->_db_datenholen($sql);
					// if not managed to add the random code do it again
					if ($d[0]['semaphorecode'] == $semaphorecode) {
						if ($semaphorecode == "") break; // semaphorecode is empty => create a new serial
						// set the $id
						$id = intval($d[0]['id']);
						$data['code'] = $d[0]['code_display'];
						// too clean up again after the testing // $this->getDB()->update("codes", ['semaphorecode'=>''], ['id'=>$d[0]['id']]);
						break; // done
					}
				}
				$rescueCounter++;
			}
		}
		if (SNGMBH::issetRPara('a') && SNGMBH::getRequestPara('a') == 'testing') {
			/*
			echo($id);
			if ($id > 0) {
				print_r($this->getCore()->retrieveCodeById($id, true));
			}

			$this->getDB()->update("codes", ['semaphorecode'=>""], ['list_id'=>$list_id, 'order_id'=>0]);
			$sql = "select * from ".$this->getDB()->getTabelle("codes")." where
					order_id = 0 and
					list_id = ".$list_id;
			$d = $this->getDB()->_db_datenholen($sql);
			print_r($d);
			if ($id == 0) {
				echo "no reusabel code found";
				exit;
			}
			*/
		}

		if ($id == 0 && empty($data['code'])) {

			// check if serial code formatter is active
			if (empty($formatterValues)) {
				$metaObj = $this->getCore()->encodeMetaValuesAndFillObjectList($listObj['meta']);
				if ($metaObj['formatter']['active'] == 1) {
					$formatterValues = stripslashes($metaObj['formatter']['format']);
				}
			}

			$counter = 0;
			while($counter < 100) {
				$counter++;
				$data["code"] = $this->generateCode($formatterValues);
				try {
					$id = $this->addCode($data);
					break;
				} catch(Exception $e) {
					// code exists already, try a new one
					if (substr($e->getMessage(), 0, 5) == "#208 ") { // no premium and limit exceeded
						//$data["code"] = "Please contact our support for the serial code";
						$data["code"] = $this->getOptionValue('wcassignmentTextNoCodePossible', "Please contact our support for the serial code");
						return $data["code"];
					}
				}
			}
		}
		//if (SNGMBH::issetRPara('a') && SNGMBH::getRequestPara('a') == 'testing') exit;

		if ($id > 0) {
			$this->editCode($data); // order_id wird nicht beim anlegen gespeichert, deswegen hier nochmal ein update
			$codeObj = $this->addWoocommerceInfoToCode([
												'code'=>$data["code"],
												'list_id'=>$list_id,
												'order_id'=>$order_id,
												'product_id'=>$product_id,
												'item_id'=>$item_id
												]);
			if ($this->MAIN->isPremium() && method_exists($this->MAIN->getPremiumFunctions(), 'addCodeFromListForOrderAfter')) {
				$codeObj = $this->MAIN->getPremiumFunctions()->addCodeFromListForOrderAfter($codeObj);
			}

			return $data["code"];
		}
		throw new Exception("#601 code could not be generated and stored");
	}
	private function generateCode($formatterValues="") {
		$code = implode('-', str_split(substr(strtoupper(md5(time()."_".rand())), 0, 20), 5));
		if (!empty($formatterValues) || $this->isOptionCheckboxActive("wcassignmentUseGlobalSerialFormatter")) {
			if (empty($formatterValues)) {
				$codeFormatterJSON = $this->getOptionValue('wcassignmentUseGlobalSerialFormatter_values');
			} else {
				$codeFormatterJSON = $formatterValues;
			}
			// check ob formatter infos gespeichert
			if (!empty($codeFormatterJSON)) {
				// check ob man das JSON erstellen kann
				$obj = json_decode($codeFormatterJSON, true);
				if (is_array($obj)) {
					// bauen den code
					$laenge = 0;
					if (isset($obj['input_amount_letters'])) $laenge = intval($obj['input_amount_letters']);
					if ($laenge == 0) $laenge = 9;
					$charset = join("", range("a", "z"));
					$letterStyle = 0;
					if (isset($obj['input_letter_style'])) $letterStyle = intval($obj['input_letter_style']);
					if ($letterStyle == 1) $charset = strtoupper($charset);
					if ($letterStyle == 3) $charset .= strtoupper($charset);
					$withnumbers = 0;
					if (isset($obj['input_include_numbers'])) $withnumbers = intval($obj['input_include_numbers']);
					if ($withnumbers == 2) $charset .= '0123456789';
					if ($withnumbers == 3) $charset = '0123456789';
					$exclusion = 0;
					if (isset($obj['input_letter_excl'])) $exclusion = intval($obj['input_letter_excl']);
					if ($exclusion == 2) {
						$charset = str_ireplace(['i','l','o','p','q'], "", $charset);
					}
					$letters = str_split($charset);
					$code = "";
					for ($a=0;$a<$laenge;$a++) {
				        shuffle($letters);
				        $zufallszahl = rand(0, count($letters)-1);
				        $buchstabe = $letters[$zufallszahl];
				        $code .= $buchstabe;
					}

					// add delimiter to the code
					$serial_delimiter = 0;
					if (isset($obj['input_serial_delimiter'])) $serial_delimiter = intval($obj['input_serial_delimiter']);
					if ($serial_delimiter > 0) {
						$serial_delimiter_space = 3;
						if (isset($obj['input_serial_delimiter_space'])) $serial_delimiter_space = intval($obj['input_serial_delimiter_space']);
						$delimiter = ['','-',' ',':'][$serial_delimiter - 1];
						$codeLetters = str_split($code);
						$chunks = array_chunk($codeLetters, $serial_delimiter_space);
						$codeChunks = [];
						foreach($chunks as $chunk) {
							$codeChunks[] = join("", $chunk);
						}
						$code = join($delimiter, $codeChunks);
					}

					// prefix
					if (isset($obj['input_prefix_codes'])){
						$prefix_code = trim($obj['input_prefix_codes']);
						echo str_replace(["{Y}", "{m}", "{d}", "{H}", "{i}", "{s}", "{TIMESTAMP}"], [date("Y"), date("m"), date("d"), date("H"), date("i"), date("s"), time()], "slkd {Y} {TIMESTAMP}");
						$code = $prefix_code.$code;
					}
				}
			}
		}
		return $code;
	}
	public function addRetrictionCodeToOrder($code, $list_id, $order_id, $product_id = 0, $item_id = 0) {
		if (!empty($code)) {
			return $this->addWoocommerceInfoToCode([
										'code'=>$code,
										'list_id'=>intval($list_id),
										'order_id'=>$order_id,
										'product_id'=>$product_id,
										'item_id'=>$item_id,
										'is_restriction_purchase'=>1
										]);
		}
	}
	private function calculateExpirationDate($codeObj, $metaObj=null) { //TODO #14 move to premium plugin
		if ($metaObj == null) {
			$metaObj = $this->getCore()->encodeMetaValuesAndFillObject($codeObj['meta']);
		}
		$product_id = 0;
		if (isset($metaObj['woocommerce']) && isset($metaObj['woocommerce']['product_id'])) {
			$product_id = intval($metaObj['woocommerce']['product_id']);
		}

		$basetimestamp = time();
		$expiration_date = "";

		$endTimeOfProduct = "";
		$useEndTimeOfProduct = $this->isOptionCheckboxActive("expireWCSetDateWithSaleAndUseEndTime");
		if ($useEndTimeOfProduct) {
			$endTimeOfProduct = get_post_meta( $product_id, 'sngmbh_serial_code_ticket_end_time', true );
			if (!empty($endTimeOfProduct)) {
				$basetimestamp = strtotime(date("Y-m-d ".$endTimeOfProduct));
			}
		}

		// take from product product_id
		$v_expiration_days = intval(get_post_meta( $product_id, 'sngmbh_serial_expiration_days', true ));
		if (empty($expiration_date) && $v_expiration_days > 0) {
			$expiration_date = date("Y-m-d H:i:s", strtotime('+'.intval($v_expiration_days).' day', $basetimestamp));
		}

		// take date from code
		if (empty($expiration_date) && !empty($metaObj['expiration']['date'])) {
			$expiration_date = $metaObj['expiration']['date'];
		}

		// take days from code
		if (empty($expiration_date) && $metaObj['expiration']['days'] > 0) {
			$expiration_date = date("Y-m-d H:i:s", strtotime('+'.intval($metaObj['expiration']['days'].' day', $basetimestamp)));
		}

		// or take days from code list
		if (empty($expiration_date) && $codeObj['list_id'] > 0) {
			try {
				$listObj = $this->getCore()->getListById($codeObj['list_id']);
				$metaObjList = $this->getCore()->encodeMetaValuesAndFillObjectList($listObj['meta']);
				if ($metaObjList['expiration']['days'] > 0) {
					$expiration_date = date("Y-m-d H:i:s", strtotime('+'.intval($metaObjList['expiration']['days'].' day', $basetimestamp)));
				}
			} catch(Exception $e) {}
		}

		// or is global active and then take the global days
		if (empty($expiration_date) && $this->isOptionCheckboxActive("expireActivateForAllCodesWithoutExpDate")) {
			$days = intval($this->getOptionValue('expireDaysForNoDate'));
			$expiration_date = date("Y-m-d H:i:s", strtotime('+'.$days.' day', $basetimestamp));
		}
		return $expiration_date;
	}
	public function executeRistrictionExpirationExtensionForOrder($order_id) {
		if ($this->MAIN->isPremium()) {
			$order = wc_get_order( $order_id );
			if ($order != null) {
				foreach ( $order->get_items() as $item_id => $item ) {
					// get the used serial as the restriction code to purchase this item - if available, otherwise it is not a restriction code purchased item
					$sale_restriction_code = wc_get_order_item_meta($item_id , '_sngmbh_serial_code_list_sale_restriction', true);
					if (!empty($sale_restriction_code)) {
						// is it already executed - check meta of the purchased item?
						$_sngmbh_serial_code_extend_expiration_days = wc_get_order_item_meta($item_id , '_sngmbh_serial_code_extend_expiration_days', true);
						if (empty($_sngmbh_serial_code_extend_expiration_days)) { // no, not set yet
							// get sngmbh_serial_code_restriction_extend_expiration_days from product
							$extend_days = intval(get_post_meta($item->get_product_id(), 'sngmbh_serial_code_restriction_extend_expiration_days', true));
							// check the product, if days to extend are set
							if ($extend_days > 0) {
								// get the amount of the purchased item
								$quantity = $item->get_quantity();
								$days = $quantity * $extend_days;
								if ($days > 0) {

									// get code, get expiration, calc new expiration day
									$codeObj = $this->getCore()->retrieveCodeByCode($sale_restriction_code);
									$metaObj = $this->getCore()->encodeMetaValuesAndFillObject($codeObj['meta'], $codeObj);

									// check if the code is set to expire at all
									$expiration_date = $this->calculateExpirationDate($codeObj, $metaObj);

									if (!empty($expiration_date)) { // yes, expiration is needed for this serial
										// set to item
										$item->add_meta_data( '_sngmbh_serial_code_extend_expiration_days', $days);

										// add history entry
										if (!is_array($metaObj['wc_rp']['extend_expiration_history'])) {
											$metaObj['wc_rp']['extend_expiration_history'] = [];
										}
										$o = $metaObj['wc_rp'];
										unset($o["extend_expiration_history"]);
										$metaObj['wc_rp']['extend_expiration_history'][] = $o;

										// update meta wc_rp to reflect the extend days
										$metaObj['wc_rp']['extend_expiration_executed_at'] = date("Y-m-d H:i:s");
										$metaObj['wc_rp']['extend_expiration_days'] = $days;

										// calc new expiration
										if ($this->MAIN->getPremiumFunctions()->isCodeExpired($codeObj)) {
											$metaObj['expiration']['date'] = date("Y-m-d H:i:s", strtotime("+".$days." days"));
											$metaObj['expiration']['days'] = 0;
										} else {
											$metaObj['expiration']['date'] = date("Y-m-d H:i:s", strtotime($expiration_date." +".$days." days"));
											$metaObj['expiration']['days'] = 0;
										}

										$codeObj['meta'] = $this->getCore()->json_encode_with_error_handling($metaObj);
										$this->getDB()->update("codes", ["meta"=>$codeObj['meta']], ['id'=>$codeObj['id']]);

										// make a note to the order of the purchase
										$order->add_order_note( "Set new expiration date: ".$metaObj['expiration']['date']." to ".$codeObj['code_display']." for purchased item ".$quantity."x ".$item->get_name()."." );

										//TODO #15 call webhook for days extended
									}
								}
							}
						}
					}
				}
			}
		}
	}
	private function addWoocommerceInfoToCode($data) {
		// $data = ['code'=>$data["code"], 'list_id'=>$list_id, 'order_id'=>$order_id, 'product_id'=>$product_id, 'item_id'=>$item_id]
		if (!isset($data['code'])) throw new Exception("#9601 code is missing");
		if (!isset($data['order_id'])) throw new Exception("#9602 order id is missing");
		//if (!isset($data['product_id'])) throw new Exception("#9603 product id is missing");
		//if (!isset($data['item_id'])) throw new Exception("#9604 item id is missing"); // position within the order
		$codeObj = $this->getCore()->retrieveCodeByCode($data['code']);
		$metaObj = $this->getCore()->encodeMetaValuesAndFillObject($codeObj['meta'], $codeObj);

		$key = 'woocommerce';
		if (isset($data['is_restriction_purchase']) && $data['is_restriction_purchase'] == 1) {
			$key = 'wc_rp';
		}

		$order = wc_get_order( $data['order_id'] );
		$metaObj[$key] = ['order_id'=>$data['order_id'], 'creation_date'=>date("Y-m-d H:i:s")];
		if (isset($data['product_id'])) $metaObj[$key]['product_id'] = $data['product_id'];
		if (isset($data['item_id'])) $metaObj[$key]['item_id'] = $data['item_id'];
		$metaObj[$key]['user_id'] = intval($order->get_user_id());

		$codeObj['meta'] = $this->getCore()->json_encode_with_error_handling($metaObj);
		$this->getDB()->update("codes", ["meta"=>$codeObj['meta']], ['id'=>$codeObj['id']]);

		$this->getCore()->triggerWebhooks(10, $codeObj);

		return $codeObj;
	}
	public function removeWoocommerceOrderInfoFromCode($data) {
		if (!isset($data['code'])) throw new Exception("#9611 code is missing");
		if (class_exists( 'WooCommerce' )) {
			// include Woocommerce file for delete
			if ( !function_exists( 'wc_get_order' ) ) {
				require_once ABSPATH . PLUGINDIR . '/woocommerce/includes/wc-order-functions.php';
			}
			// include Woocommerce file for delete
			if ( !function_exists( 'wc_delete_order_item_meta' ) ) {
				require_once ABSPATH . PLUGINDIR . '/woocommerce/includes/wc-order-item-functions.php';
			}
		}
		// lade code
		$codeObj = $this->getCore()->retrieveCodeByCode($data['code']);
		$metaObj = $this->getCore()->encodeMetaValuesAndFillObject($codeObj['meta'], $codeObj);
		if ($codeObj['order_id'] > 0 || $metaObj['woocommerce']['order_id'] > 0) {

			// extrahiere item id
			$item_id = isset($metaObj['woocommerce']['item_id']) ? $metaObj['woocommerce']['item_id'] : 0;
			if ($item_id > 0) {
				if (class_exists( 'WooCommerce' )) {
					wc_delete_order_item_meta( $item_id, '_sngmbh_product_serial_code' );
					wc_delete_order_item_meta( $item_id, '_sngmbh_serial_code_list' );
				}
				$this->removeWoocommerceTicketForCode($data);
			}

			/* delete all serial codes from the whole order
			$order_id = $codeObj['order_id'];
			if (class_exists( 'WooCommerce' )) {
				$order = wc_get_order( $order_id );
				if ($order) {
					foreach ( $order->get_items() as $item_id => $item ) {
						wc_delete_order_item_meta( $item_id, '_sngmbh_product_serial_code' );
						wc_delete_order_item_meta( $item_id, '_sngmbh_serial_code_list' );
					}
				}
			}
			*/
		}
		// leere meta woocommerce
		$defMeta = $this->getCore()->getMetaObject();
		$metaObj['woocommerce'] = $defMeta['woocommerce'];
		$codeObj['order_id'] = 0;
		$codeObj['meta'] = $this->getCore()->json_encode_with_error_handling($metaObj);

		$felder = ['order_id'=>0, 'semaphorecode'=>'', "meta"=>$codeObj['meta']];
		if ($this->MAIN->isPremium() && method_exists($this->MAIN->getPremiumFunctions(), 'removeWoocommerceOrderInfoFromCode')) {
			$felder = $this->MAIN->getPremiumFunctions()->removeWoocommerceOrderInfoFromCode($codeObj, $felder, $data);
		}
		$where = ['id'=>intval($codeObj['id'])];

		$this->getDB()->update("codes", $felder, $where);
		$this->getCore()->triggerWebhooks(11, $codeObj);
		return $codeObj;
	}
	public function removeWoocommerceRstrPurchaseInfoFromCode($data) {
		if (!isset($data['code'])) throw new Exception("#9611 code is missing");
		// lade code
		$codeObj = $this->getCore()->retrieveCodeByCode($data['code']);
		$metaObj = $this->getCore()->encodeMetaValuesAndFillObject($codeObj['meta'], $codeObj);

		// purchase restrictions do not add an order_id to the code level
		if ($metaObj['wc_rp']['order_id'] > 0) {
			if (class_exists( 'WooCommerce' )) {
				// include Woocommerce file for delete
				if ( !function_exists( 'wc_get_order' ) ) {
					require_once ABSPATH . PLUGINDIR . '/woocommerce/includes/wc-order-functions.php';
				}
				if ( !function_exists( 'wc_delete_order_item_meta' ) ) {
					require_once ABSPATH . PLUGINDIR . '/woocommerce/includes/wc-order-item-functions.php';
				}
				// extrahiere item id
				$item_id = isset($metaObj['wc_rp']['item_id']) ? $metaObj['wc_rp']['item_id'] : 0;
				if ($item_id > 0) {
					wc_delete_order_item_meta( $item_id, '_sngmbh_serial_code_list_sale_restriction' );
				}
			}
		}

		// leere meta wc_rp
		$defMeta = $this->getCore()->getMetaObject();
		$metaObj['wc_rp'] = $defMeta['wc_rp'];
		$codeObj['meta'] = $this->getCore()->json_encode_with_error_handling($metaObj);

		$felder = ["meta"=>$codeObj['meta']];
		if ($this->MAIN->isPremium() && method_exists($this->MAIN->getPremiumFunctions(), 'removeWoocommerceRstrPurchaseInfoFromCode')) {
			$felder = $this->MAIN->getPremiumFunctions()->removeWoocommerceRstrPurchaseInfoFromCode($codeObj, $felder, $data);
		}
		$where = ['id'=>intval($codeObj['id'])];

		$this->getDB()->update("codes", $felder, $where);
		return $codeObj;
	}
	private function removeRedeemWoocommerceTicketForCode($data) {
		if (!isset($data['code'])) throw new Exception("#9621 code is missing");
		// lade code
		$codeObj = $this->getCore()->retrieveCodeByCode($data['code']);
		$metaObj = $this->getCore()->encodeMetaValuesAndFillObject($codeObj['meta'], $codeObj);

		// leere meta wc_ticket
		$defMeta = $this->getCore()->getMetaObject();
		$idcode = $metaObj['wc_ticket']['idcode'];
		$_url = $metaObj['wc_ticket']['_url'];
		$metaObj['wc_ticket'] = $defMeta['wc_ticket'];
		$metaObj['wc_ticket']['is_ticket'] = 1;
		$metaObj['wc_ticket']['idcode'] = $idcode;
		$metaObj['wc_ticket']['_url'] = $_url;
		$metaObj['used'] = $defMeta['used'];

		$codeObj['meta'] = $this->getCore()->json_encode_with_error_handling($metaObj);

		$felder = ["meta"=>$codeObj['meta']];
		if ($this->MAIN->isPremium() && method_exists($this->MAIN->getPremiumFunctions(), 'removeRedeemWoocommerceTicketForCode')) {
			$felder = $this->MAIN->getPremiumFunctions()->removeRedeemWoocommerceTicketForCode($codeObj, $felder, $data);
		}
		$where = ['id'=>intval($codeObj['id'])];

		$this->getDB()->update("codes", $felder, $where);
		$this->getCore()->triggerWebhooks(14, $codeObj);
		return $codeObj;
	}
	public function removeWoocommerceTicketForCode($data) {
		if (!isset($data['code'])) throw new Exception("#9625 code is missing");
		if (class_exists( 'WooCommerce' )) {
			// include Woocommerce file for delete
			if ( !function_exists( 'wc_get_order' ) ) {
				require_once ABSPATH . PLUGINDIR . '/woocommerce/includes/wc-order-functions.php';
			}
			if ( !function_exists( 'wc_delete_order_item_meta' ) ) {
				require_once ABSPATH . PLUGINDIR . '/woocommerce/includes/wc-order-item-functions.php';
			}
		}
		// lade code
		$codeObj = $this->getCore()->retrieveCodeByCode($data['code']);
		$metaObj = $this->getCore()->encodeMetaValuesAndFillObject($codeObj['meta'], $codeObj);
		if ($codeObj['order_id'] > 0 || $metaObj['woocommerce']['order_id'] > 0) {
			if (class_exists( 'WooCommerce' )) {
				// extrahiere item id
				$item_id = isset($metaObj['woocommerce']['item_id']) ? $metaObj['woocommerce']['item_id'] : 0;
				if ($item_id > 0) {
					wc_delete_order_item_meta( $item_id, '_sngmbh_serial_code_is_ticket' );
				}
			}
		}

		$order_id = intval($metaObj['woocommerce']['order_id']);
		if (class_exists( 'WooCommerce' )) {
			$order = new WC_Order( $order_id );

			// set order note
			$order->add_order_note( "Ticket number: ".$codeObj['code_display']." for order item of product #".intval($metaObj['woocommerce']['product_id'])." removed." );
		}

		// leere meta woocommerce
		$defMeta = $this->getCore()->getMetaObject();
		$idcode = $metaObj['wc_ticket']['idcode'];
		$metaObj['wc_ticket'] = $defMeta['wc_ticket'];
		$metaObj['wc_ticket']['idcode'] = $idcode;
		$codeObj['meta'] = $this->getCore()->json_encode_with_error_handling($metaObj);

		$felder = ["meta"=>$codeObj['meta']];
		if ($this->MAIN->isPremium() && method_exists($this->MAIN->getPremiumFunctions(), 'removeWoocommerceTicketForCode')) {
			$felder = $this->MAIN->getPremiumFunctions()->removeWoocommerceTicketForCode($codeObj, $felder, $data);
		}
		$where = ['id'=>intval($codeObj['id'])];

		$this->getDB()->update("codes", $felder, $where);
		$this->getCore()->triggerWebhooks(15, $codeObj);
		return $codeObj;
	}
	private function redeemWoocommerceTicketForCode($data) {
		if (!isset($data['code'])) throw new Exception("#9622 code is missing");
		// lade code
		$codeObj = $this->getCore()->retrieveCodeByCode($data['code']);
		$metaObj = $this->getCore()->encodeMetaValuesAndFillObject($codeObj['meta'], $codeObj);

		if (empty($metaObj['wc_ticket']['redeemed_date'])) {
			$metaObj['wc_ticket']['redeemed_date'] = date("Y-m-d H:i:s");
			$metaObj['wc_ticket']['ip'] = $this->getCore()->getRealIpAddr();
			if (isset($data['userid'])) $metaObj['wc_ticket']['userid'] = intval($data['userid']);
			if (is_admin() || isset($data['redeemed_by_admin'])) {
				// kann sein, dass der admin nicht eingeloggt ist (externer mitarbeiter)
				$metaObj['wc_ticket']['redeemed_by_admin'] = get_current_user_id();
			}

			$metaObj['used']['reg_ip'] = $this->getCore()->getRealIpAddr();
			$metaObj['used']['reg_request'] = date("Y-m-d H:i:s");
			if (isset($data['userid'])) $metaObj['used']['reg_userid'] = intval($data['userid']);

			$codeObj['meta'] = $this->getCore()->json_encode_with_error_handling($metaObj);

			$felder = ["meta"=>$codeObj['meta']];
			if ($this->MAIN->isPremium() && method_exists($this->MAIN->getPremiumFunctions(), 'redeemWoocommerceTicketForCode')) {
				$felder = $this->MAIN->getPremiumFunctions()->redeemWoocommerceTicketForCode($codeObj, $felder, $data);
			}
			$where = ['id'=>intval($codeObj['id'])];

			$this->getDB()->update("codes", $felder, $where);
			$this->getCore()->triggerWebhooks(13, $codeObj);
		}
		return $codeObj;
	}
	private function setWoocommerceTicketForCode($data) {
		if (!isset($data['code'])) throw new Exception("#9623 code is missing");
		$codeObj = $this->getCore()->retrieveCodeByCode($data['code']);
		$metaObj = $this->getCore()->encodeMetaValuesAndFillObject($codeObj['meta'], $codeObj);

		if ($metaObj['wc_ticket']['is_ticket'] == 1) return $codeObj; // früher abbruch

		// check order
		if (!isset($metaObj['woocommerce']) || !isset($metaObj['woocommerce']['order_id']) || $metaObj['woocommerce']['order_id'] == 0) throw new Exception("#9624 code is not bound to an order");
		// check if woocommerce exists
		if ( ! class_exists( 'WooCommerce' ) )  throw new Exception("#9625 WooCommerce missing or not active");

		if ( !function_exists( 'wc_get_order' ) ) {
		    require_once ABSPATH . PLUGINDIR . '/woocommerce/includes/wc-order-functions.php';
		}
		// include Woocommerce file for delete
		if ( !function_exists( 'wc_add_order_item_meta' ) || !function_exists('wc_delete_order_item_meta')) {
			require_once ABSPATH . PLUGINDIR . '/woocommerce/includes/wc-order-item-functions.php';
		}

		$order_id = intval($metaObj['woocommerce']['order_id']);
		$order = new WC_Order( $order_id );

		// set order note
		$order->add_order_note( "Order item serial changed to ticket with ticket number: ".$codeObj['code_display'] );

		// set
		$item_id = intval($metaObj['woocommerce']['item_id']);
		wc_delete_order_item_meta( $item_id, '_sngmbh_serial_code_is_ticket' );
		wc_add_order_item_meta( $item_id, '_sngmbh_serial_code_is_ticket', '1', true);

		// set codeobj meta and set webhook trigger

		$metaObj['wc_ticket']['set_by_admin'] = get_current_user_id();
		$metaObj['wc_ticket']['set_by_admin_date'] = date("d.m.Y H:i:s");

		$codeObj['meta'] = $this->getCore()->json_encode_with_error_handling($metaObj);

		$felder = ["meta"=>$codeObj['meta']];
		if ($this->MAIN->isPremium() && method_exists($this->MAIN->getPremiumFunctions(), 'setWoocommerceTicketForCode')) {
			$felder = $this->MAIN->getPremiumFunctions()->setWoocommerceTicketForCode($codeObj, $felder, $code);
		}
		$where = ['id'=>intval($codeObj['id'])];

		$this->getDB()->update("codes", $felder, $where);

		return $this->setWoocommerceTicketInfoForCode($data['code']);
	}
	public function setWoocommerceTicketInfoForCode($code) {
		$codeObj = $this->getCore()->retrieveCodeByCode($code);
		$metaObj = $this->getCore()->encodeMetaValuesAndFillObject($codeObj['meta'], $codeObj);

		$metaObj['wc_ticket']['is_ticket'] = 1;
		if (empty($metaObj['wc_ticket']['idcode']))	$metaObj['wc_ticket']['idcode'] = crc32($codeObj['id']."-".time());
		$metaObj['wc_ticket']['_url'] = $this->getCore()->getTicketURL($codeObj, $metaObj);

		$codeObj['meta'] = $this->getCore()->json_encode_with_error_handling($metaObj);

		$felder = ["meta"=>$codeObj['meta']];
		if ($this->MAIN->isPremium() && method_exists($this->MAIN->getPremiumFunctions(), 'setWoocommerceTicketInfoForCode')) {
			$felder = $this->MAIN->getPremiumFunctions()->setWoocommerceTicketInfoForCode($codeObj, $felder, $code);
		}
		$where = ['id'=>intval($codeObj['id'])];

		$this->getDB()->update("codes", $felder, $where);
		$this->getCore()->triggerWebhooks(12, $codeObj);
		return $codeObj;
	}
	public function addCode($data) {
		if (!isset($data['code']) || trim($data['code']) == "") throw new Exception("#201 code missing");
		$id = $this->_addCode($data['code'], isset($data['list_id']) ? intval($data['list_id']) : 0);
		if ($this->MAIN->isPremium() && method_exists($this->MAIN->getPremiumFunctions(), 'updateCodeMetas')) {
			$this->MAIN->getPremiumFunctions()->updateCodeMetas($data['code'], $data);
		}
		return $id;
	}
	public function addCodes($data) {
		if (!isset($data['codes'])) throw new Exception("#211 codes missing");
		if (!is_array($data['codes'])) throw new Exception("#212 codes must be an array");

		$ret = ['ok'=>[], 'notok'=>[]];

		set_time_limit(0);
		foreach($data['codes'] as $v) {
			$ok = false;
			try {
				$id = $this->_addCode($v, isset($data['list_id']) ? intval($data['list_id']) : 0);
				$ret['ok'][] = $v;
				$ok = true;
			} catch (Exception $e) {
				$ret['notok'][] = $v;
			}
			try {
				if ($ok && $this->MAIN->isPremium() && method_exists($this->MAIN->getPremiumFunctions(), 'updateCodeMetas')) {
					$this->MAIN->getPremiumFunctions()->updateCodeMetas($v, $data);
				}
			} catch (Exception $e) {}
		}

		$ret['total_size'] = $this->getDB()->getCodesSize();
		return $ret;
	}
	private function editCode($data) {
		if (!isset($data['code']) || trim($data['code']) == "") throw new Exception("#206 code missing");
		$data['code'] = $this->getCore()->clearCode($data['code']);
		$codeObj = $this->getCore()->retrieveCodeByCode($data['code']);
		$felder = [];
		if (isset($data['list_id'])) $felder['list_id'] = intval($data['list_id']);
		if (isset($data['cvv'])) $felder['cvv'] = trim($data['cvv']);
		if (isset($data['code_display'])) $felder['code_display'] = trim($data['code_display']);
		if (isset($data['order_id'])) $felder['order_id'] = intval($data['order_id']);
		if (isset($data['aktiv']) && (intval($data['aktiv']) == 1 || intval($data['aktiv']) == 2)) $felder['aktiv'] = intval($data['aktiv']);
		if ($this->MAIN->isPremium() && method_exists($this->MAIN->getPremiumFunctions(), 'setFelderCodeEdit')) {
			$felder = $this->MAIN->getPremiumFunctions()->setFelderCodeEdit($felder, $data, $codeObj);
		}
		if (count($felder) > 0) {
			$where = ["code"=>$this->getDB()->reinigen_in($data['code'])];
			return $this->getDB()->update("codes", $felder, $where);
		}
		return "nothing to update";
	}
	private function removeCode($data) {
		if (!isset($data['code']) && !isset($data['id'])) throw new Exception("#207 code is missing");
		$sql = "delete from ".$this->getDB()->getTabelle("codes")." where ";
		if (isset($data['code'])) {
			$data['code'] = $this->getCore()->clearCode($data['code']);
			$sql .= "code = '".$this->getDB()->reinigen_in($data['code'])."'";
		} else {
			$code = $this->getCore()->retrieveCodeById($data['id']);
			$data['code'] = $code['code'];
			$sql .= "id = '".intval($data['id'])."'";
		}
		$ret = $this->getDB()->_db_query($sql);
		// entferne code from produkt
		if (isset($data['code'])) {
			$this->removeWoocommerceOrderInfoFromCode($data);
			$this->removeWoocommerceRstrPurchaseInfoFromCode($data);
			$this->removeRedeemWoocommerceTicketForCode($data);
		}
		return $ret;
	}
	private function removeCodes($data) {
		if (!isset($data['ids'])) throw new Exception("#209 ids are missing");
		if (!is_array($data['ids'])) throw new Exception("#210 ids must be an array");
		foreach($data['ids'] as $v) {
			$sql = "delete from ".$this->getDB()->getTabelle("codes")." where id = '".intval($v)."'";
			$this->getDB()->_db_query($sql);
		}
		return count($data['ids']);
	}
	private function emptyTableLists($data) {
		$sql = "update ".$this->getDB()->getTabelle("codes")." set list_id = 0";
		$this->getDB()->_db_query($sql);
		$sql = "delete from ".$this->getDB()->getTabelle("lists");
		return $this->getDB()->_db_query($sql);
	}
	private function emptyTableCodes($data) {
		$sql = "delete from ".$this->getDB()->getTabelle("codes");
		return $this->getDB()->_db_query($sql);
	}
	private function exportTableCodes($data) {
		$delimiters = [',',';','|'];
		$filesuffixes = ['.csv','.txt'];
		$orderbys = ['time', 'code', 'code_display', 'list_name'];
		$orderbydirections = ['asc','desc'];
		$delimiter = $delimiters[0];
		$filesuffix = $filesuffixes[0];
		$orderby = $orderbys[0];
		$orderbydirection = $orderbydirections[0];

		$displayAdminAreaColumnBillingName = $this->isOptionCheckboxActive('displayAdminAreaColumnBillingName');

		$field_options = [
			"displayAdminAreaColumnBillingName"=>$displayAdminAreaColumnBillingName,
		];
		$fields = $this->getExportColumnFields();

		if (isset($data['delimiter']) && isset($delimiters[$data['delimiter']-1])) $delimiter = $delimiters[$data['delimiter']-1];
		if (isset($data['filesuffix']) && isset($filesuffixes[$data['filesuffix']-1])) $filesuffix = $filesuffixes[$data['filesuffix']-1];
		if (isset($data['orderby']) && isset($orderbys[$data['orderby']-1])) $orderby = $orderbys[$data['orderby']-1];
		if (isset($data['orderbydirection']) && isset($orderbydirections[$data['orderbydirection']-1])) $orderbydirection = $orderbydirections[$data['orderbydirection']-1];
		set_time_limit(0);

		// hole daten
		$sql = "select a.*, b.name as list_name from ".$this->getDB()->getTabelle("codes")." a left join ".$this->getDB()->getTabelle("lists")." b on a.list_id = b.id";
		if (isset($data['listchooser']) && !empty($data['listchooser']) && intval($data['listchooser']) > 0) {
			$sql .= " where a.list_id = ".intval($data['listchooser']);
		}
		$sql .= " order by ".$orderby." ".$orderbydirection;
		if (isset($data['rangestart'])) {
			$sql .= " limit ".intval($data['rangestart']);
			if (isset($data['rangeamount'])) {
				$sql .= ", ".intval($data['rangeamount']);
			}
		}
		$daten = $this->getDB()->_db_datenholen($sql);
		foreach($daten as &$row) {
			$row = $this->transformMetaObjectToExportColumn($row, $fields, $field_options);
		}
		// sende csv datei
		$filename = "export_codes_sngmbhSerialcodesValidator_".date("YmdHis").$filesuffix;
		$this->_basics_sendeDateiCSVvonDBdaten($daten, $filename, $delimiter);
		exit;
	}
	private function getExportColumnFields() {
		$fields = [
			'meta_validation', 'meta_validation_first_success', 'meta_validation_first_ip', 'meta_validation_last_success', 'meta_validation_last_ip',
			'meta_user', 'meta_user_reg_approved', 'meta_user_reg_request_date', 'meta_user_value', 'meta_user_reg_ip', 'meta_user_reg_userid',
			'meta_expireDate',
			'meta_used', 'meta_used_reg_ip', 'meta_used_reg_request_date', 'meta_used_reg_userid',
			'meta_confirmedCount',
			'meta_woocommerce', 'meta_woocommerce_order_id', 'meta_woocommerce_product_id', 'meta_woocommerce_creation_date', 'meta_woocommerce_item_id', 'meta_woocommerce_user_id', 'meta_woocommerce_customer_name',
			'meta_wc_rp', 'meta_wc_rp_order_id', 'meta_wc_rp_product_id', 'meta_wc_rp_creation_date', 'meta_wc_rp_item_id',
			'meta_wc_ticket', 'meta_wc_ticket_is_ticket', 'meta_wc_ticket_ip', 'meta_wc_ticket_userid', 'meta_wc_ticket_redeemed_date', 'meta_wc_ticket_redeemed_by_admin', 'meta_wc_ticket_set_by_admin', 'meta_wc_ticket_set_by_admin_date', 'meta_wc_ticket_idcode'
			];
		if ($this->MAIN->isPremium() && method_exists($this->MAIN->getPremiumFunctions(), 'getExportColumnFields')) {
			$fields = $this->MAIN->getPremiumFunctions()->getExportColumnFields($fields);
		}
		return $fields;
	}
	public function transformMetaObjectToExportColumn($row, $fields=null, $options=[]) {
		if ($fields == null) {
			$fields = $this->getExportColumnFields();
		}
		foreach($fields as $v) {
			$row[$v] = '';
		}
		// nehme meta object
		if (!empty($row['meta'])) {
			$metaObj = $this->getCore()->encodeMetaValuesAndFillObject($row['meta']);
			// zerlege das object und inhalte in einzelne spalten
			if (isset($metaObj['validation'])) {
				if (!empty($metaObj['validation']['first_success'])) $row['meta_validation_first_success'] = $metaObj['validation']['first_success'];
				if (!empty($metaObj['validation']['first_ip'])) $row['meta_validation_first_ip'] = $metaObj['validation']['first_ip'];
				if (!empty($metaObj['validation']['last_success'])) $row['meta_validation_last_success'] = $metaObj['validation']['last_success'];
				if (!empty($metaObj['validation']['last_ip'])) $row['meta_validation_last_ip'] = $metaObj['validation']['last_ip'];
			}
			if (isset($metaObj['user'])) {
				if (!empty($metaObj['user']['reg_approved'])) $row['meta_user_reg_approved'] = $metaObj['user']['reg_approved'];
				if (!empty($metaObj['user']['reg_request'])) $row['meta_user_reg_request_date'] = $metaObj['user']['reg_request'];
				if (!empty($metaObj['user']['reg_userid'])) $row['meta_user_reg_userid'] = $metaObj['user']['reg_userid'];
				if (!empty($metaObj['user']['value'])) $row['meta_user_value'] = $metaObj['user']['value'];
				if (!empty($metaObj['user']['reg_ip'])) $row['meta_user_reg_ip'] = $metaObj['user']['reg_ip'];
				if (!empty($row['meta_user_reg_request_date'])) $row['meta_user'] = 1;
			}
			if (isset($metaObj['expireDate'])) $row['meta_expireDate'] = $metaObj['expireDate'];
			if (isset($metaObj['used'])) {
				if (!empty($metaObj['used']['reg_ip'])) $row['meta_used_reg_ip'] = $metaObj['used']['reg_ip'];
				if (!empty($metaObj['used']['reg_request'])) $row['meta_used_reg_request_date'] = $metaObj['used']['reg_request'];
				if (!empty($metaObj['used']['reg_userid'])) $row['meta_used_reg_userid'] = $metaObj['used']['reg_userid'];
				if (!empty($row['meta_used_req_request_date'])) $row['meta_used'] = 1;
			}
			if (isset($metaObj['confirmedCount'])) $row['meta_confirmedCount'] = $metaObj['confirmedCount'];
			if (isset($metaObj['woocommerce'])) {
				if (!empty($metaObj['woocommerce']['order_id'])) $row['meta_woocommerce_order_id'] = $metaObj['woocommerce']['order_id'];
				if (!empty($metaObj['woocommerce']['product_id'])) $row['meta_woocommerce_product_id'] = $metaObj['woocommerce']['product_id'];
				if (!empty($metaObj['woocommerce']['creation_date'])) $row['meta_woocommerce_creation_date'] = $metaObj['woocommerce']['creation_date'];
				if (!empty($metaObj['woocommerce']['item_id'])) $row['meta_woocommerce_item_id'] = $metaObj['woocommerce']['item_id'];
				if (!empty($metaObj['woocommerce']['user_id'])) $row['meta_woocommerce_user_id'] = $metaObj['woocommerce']['user_id'];
			}
			if (isset($metaObj['wc_rp'])) {
				if (!empty($metaObj['wc_rp']['order_id'])) $row['meta_wc_rp_order_id'] = $metaObj['wc_rp']['order_id'];
				if (!empty($metaObj['wc_rp']['product_id'])) $row['meta_wc_rp_product_id'] = $metaObj['wc_rp']['product_id'];
				if (!empty($metaObj['wc_rp']['creation_date'])) $row['meta_wc_rp_creation_date'] = $metaObj['wc_rp']['creation_date'];
				if (!empty($metaObj['wc_rp']['item_id'])) $row['meta_wc_rp_item_id'] = $metaObj['wc_rp']['item_id'];
			}
			if (isset($metaObj['wc_ticket'])) {
				if (!empty($metaObj['wc_ticket']['is_ticket'])) $row['meta_wc_ticket_is_ticket'] = $metaObj['wc_rp']['is_ticket'];
				if (!empty($metaObj['wc_ticket']['ip'])) $row['meta_wc_ticket_ip'] = $metaObj['wc_rp']['ip'];
				if (!empty($metaObj['wc_ticket']['userid'])) $row['meta_wc_ticket_userid'] = $metaObj['wc_rp']['userid'];
				if (!empty($metaObj['wc_ticket']['redeemed_date'])) $row['meta_wc_ticket_redeemed_date'] = $metaObj['wc_rp']['redeemed_date'];
				if (!empty($metaObj['wc_ticket']['redeemed_by_admin'])) $row['meta_wc_ticket_redeemed_by_admin'] = $metaObj['wc_rp']['redeemed_by_admin'];
				if (!empty($metaObj['wc_ticket']['set_by_admin'])) $row['meta_wc_ticket_redeemed_by_admin'] = $metaObj['wc_rp']['set_by_admin'];
				if (!empty($metaObj['wc_ticket']['set_by_admin_date'])) $row['meta_wc_ticket_set_by_admin_date'] = $metaObj['wc_rp']['set_by_admin_date'];
				if (!empty($metaObj['wc_ticket']['idcode'])) $row['meta_wc_ticket_idcode'] = $metaObj['wc_rp']['idcode'];
			}
		}

		if ($options != null && is_array($options)) {
			if (isset($options["displayAdminAreaColumnBillingName"]) && $options["displayAdminAreaColumnBillingName"]) {
				$row['meta_woocommerce_customer_name'] = $this->getCustomerName($metaObj['woocommerce']['order_id']);
			}
		}

		if ($this->MAIN->isPremium() && method_exists($this->MAIN->getPremiumFunctions(), 'transformMetaObjectToExportColumn')) {
			$row = $this->MAIN->getPremiumFunctions()->transformMetaObjectToExportColumn($row);
		}
		return $row;
	}
	public function _basics_sendeDateiCSVvonDBdaten($daten, $filename, $delimiter=";") {
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Description: File Transfer');
        header('Content-type: text/csv');
    	header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Expires: 0');
        header('Pragma: public');

		ob_end_clean();
		$out = fopen('php://output', 'w');

		if (count($daten) > 0) {
			fputcsv($out, array_keys($daten[0]), $delimiter);
			foreach($daten as $value) {
				fputcsv($out, array_values($value), $delimiter);
			}
		} else {
			fputcsv($out, array("no data"), $delimiter);
		}
		fclose($out);
	}

	public function performJobsAfterDBUpgraded($dbversion="", $dbversion_pre="") {
		global $wpdb;
		if (empty($dbversion)) {
			$dbversion = $this->getDB()->dbversion;
		}

		// von 1.6 auf 1.7 update reg_user_id to code table user_id field
		//if ($dbversion_pre == "1.6") {
		if ($dbversion == "1.7" && $dbversion_pre != $dbversion) {
			$sql = "select id, meta, order_id from ".$this->getDB()->getTabelle("codes")." where user_id = 0 and meta != ''";
			$d = $this->getDB()->_db_datenholen($sql);
			// gehe durch alle codes und check ob user id reg vorhanden ist
			foreach($d as $codeObj) {
				try {
					$metaObj = $this->getCore()->encodeMetaValuesAndFillObject($codeObj['meta'], $codeObj);
					if (isset($metaObj['user']['reg_userid']) && $metaObj['user']['reg_userid'] > 0) {
						// update code
						$this->getDB()->update("codes", ["user_id"=>$metaObj['used']['reg_userid']], ['id'=>$codeObj['id']]);
					}
				} catch (Exception $e) {
					//var_dump($e->getMessage());
				}
			}
		}

		// wenn version < 1.8 war, dann alte tabellen inhalten kopieren
		// und entfernen
		// tabellen umstellen
		if ($dbversion_pre != $dbversion && version_compare($dbversion_pre, "1.8", "<" )) {
			// hole alle tabellen
			$sql = "show tables";
			$d = $this->getDB()->_db_datenholen($sql);
			// zuerst lists, um auch die list_id zu aktualisieren
			$found_tables = ["codes"=>false, "lists"=>false];
			foreach ($d as $item) {
				$v = array_values($item);
				if ($v[0] == $wpdb->prefix."codes") {
					$found_tables["codes"] = true;
				}
				if ($v[0] == $wpdb->prefix."lists") {
					$found_tables["lists"] = true;
				}
			}
			if ($found_tables["lists"]) {
				$sql = "select * from ".$wpdb->prefix."lists limit 1";
				$dd = $this->getDB()->_db_datenholen($sql);
				if (count($dd) > 0) {
					// check ob es auch meine tabelle ist
					if (isset($dd[0]['meta'])) {
						$sql = "select * from ".$wpdb->prefix."lists";
						$dd = $this->getDB()->_db_datenholen($sql);
						foreach($dd as $r) {
							$sql = "insert into ".$this->getDB()->getTabelle("lists")."
									(time, name, aktiv, meta)
									select time, name, aktiv, meta from ".$wpdb->prefix."lists where id = ".intval($r['id']);
							$id = $this->getDB()->_db_query($sql);
							if ($id > 0) {
								if ($found_tables["codes"]) {
									$sql = "update ".$wpdb->prefix."codes set list_id = ".intval($id)." where id = ".intval($r['id']);
									$this->getDB()->_db_query($sql);
								}
							}
						}
						$this->getDB()->_db_query($sql);
						$sql = "drop table ".$wpdb->prefix."lists";
						$this->getDB()->_db_query($sql);
					}
				}
			}
			// nun die codes
			if ($found_tables["codes"]) {
				$sql = "select * from ".$wpdb->prefix."codes limit 1";
				$dd = $this->getDB()->_db_datenholen($sql);
				if (count($dd) > 0) {
					// check ob es auch meine tabelle ist
					if (isset($dd[0]['semaphorecode'])) {
						$sql = "insert IGNORE into ".$this->getDB()->getTabelle("codes")."
								(time, code, code_display, cvv, meta, aktiv, list_id, order_id, semaphorecode, user_id)
								select time, code, code_display, cvv, meta, aktiv, list_id, order_id, semaphorecode, user_id
								from ".$wpdb->prefix."codes
								";
						$this->getDB()->_db_query($sql);
						$sql = "drop table ".$wpdb->prefix."codes";
						$this->getDB()->_db_query($sql);
					}
				}
			}
		}

		if ($this->MAIN->isPremium() && method_exists($this->MAIN->getPremiumFunctions(), 'performJobsAfterDBUpgraded')) {
			$this->MAIN->getPremiumFunctions()->performJobsAfterDBUpgraded($dbversion, $dbversion_pre);
		}
		do_action( $this->MAIN->_do_action_prefix.'performJobsAfterDBUpgraded', $dbversion, $dbversion_pre );
	}

	function show_user_profile($profileuser) {
		// zeigt infos im user profile an
		if ($this->isOptionCheckboxActive("userProfileDisplayRegisteredNumbers")) {
			echo "<h3>Serial Codes Registered</h3>";
			//print_r($profileuser);
			$user_id = intval($profileuser->ID);
			$ret =  $this->MAIN->getMyCodeText($user_id);
			if (empty($ret)) echo "-";
			else echo $ret;
		}
		if ($this->isOptionCheckboxActive("userProfileDisplayBoughtNumbers")) {
			echo "<h3>Serial Codes Numbers</h3>";
			$sql = "select * from ".$this->getDB()->getTabelle("codes")." where
				json_extract(meta, '$.wc_ticket.is_ticket') = 1 and
				json_extract(meta, '$.woocommerce.user_id') = ".$user_id;
			$d = $this->getDB()->_db_datenholen($sql);
			foreach($d as $item) {
				echo $item['code']."<br>";
			}
		}
	}

	private function testing($data) {
		//$this->MAIN->cronjob_daily();
		/*
		$product_id = 185;
		$v_expiration_days = "2";
		$zeit = get_post_meta( $product_id, 'sngmbh_serial_code_ticket_end_time', true );
		echo $zeit;
		if (empty($zeit)) echo "zeit ist empty";
		else {
			echo date("Y-m-d ".$zeit);
			$basetimestamp = strtotime(date("Y-m-d ".$zeit));
			echo date("Y-m-d H:i:s", $basetimestamp);
			echo "<br>";
			echo date("Y-m-d H:i:s", strtotime('+'.intval($v_expiration_days).' day', $basetimestamp));
			echo "<br>";
		}
		echo date("Y-m-d H:i:s", strtotime('+'."2".' day', null));
		$expiration_date = date("Y-m-d H:i:s", strtotime('+'.intval($v_expiration_days).' day'));
		return [$expiration_date, $v_expiration_days];
		*/
		/*
		defined('WP_DEBUG') or define( 'WP_DEBUG', true );
		ini_set('display_startup_errors', 'On');
		error_reporting(2147483647); // max future error values
		ini_set('display_errors', 'On');
		*/
		// testing addcode
		/*
		$code = "UJDWTMEGC111"; // gibts schon
		$felder = ["code"=>$code, "code_display"=>$code, "cvv"=>"", "aktiv"=>1, "time"=>date("Y-m-d H:i:s"), "meta"=>"", "list_id"=>0];
		try {
			ob_start();
			$ret = $this->getDB()->insert("codes", $felder);
			$buffer = ob_get_contents();
			ob_end_clean();
		} catch(Exception $e) {
			echo "failed";
		}
		return $ret;
		*/

		//print_r($data);

		//print_r($this->getCore()->retrieveCodeByCode("UJDWTMEGC"));

		//$this->performJobsAfterDBUpgraded("1.7", "1.6");

		// formatter vom product
		//$formatter = get_post_meta( 11, 'sngmbh_serial_code_list_formatter_values', true );

		// formatter von code list
		//$listObj = $this->getList(['id'=>2]);
		//$metaObj = $this->getCore()->encodeMetaValuesAndFillObjectList($listObj['meta']);
		//$formatter = stripslashes($metaObj['formatter']['format']);
		//return $this->generateCode($formatter);

		/*
		$code = $this->addCodeFromListForOrder(2, 1, 2, 3);

		echo $code;
		echo "<br>Display Code";
		$sql = "select * from ".$this->getDB()->getTabelle("codes")." where
				code = '". $this->getDB()->reinigen_in($code) ."'";
		$d = $this->getDB()->_db_datenholen($sql);
		print_r($d);
		echo "<br>Reverting order setting";
		$this->getDB()->update("codes", ['semaphorecode'=>"", "order_id"=>0], ["code"=>$code]);
		*/
	}
}
?>