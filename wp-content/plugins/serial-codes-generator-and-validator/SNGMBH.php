<?php
include(plugin_dir_path(__FILE__)."init_file.php");
if (!class_exists('SNGMBH', false)) {
    class SNGMBH {
		static $DB;
		/**
		 * @param $plugin_dir_path plugin_dir_path(__FILE__)
		 */
		public static function getDB($plugin_dir_path, $className, $MAIN) {
			if (self::$DB == null) {
				if (!class_exists($className)) {
					include_once $plugin_dir_path."db.php";
				}
				self::$DB = new $className($MAIN);
				self::$DB->installiereTabellen(); // schützt sich selbst mit eigener option-var
			}
			return self::$DB;
		}
		public static function getRequestPara($name, $def=null) {
			$ret = null;
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				if (isset($_POST[$name])) $ret = $_POST[$name];
				if ($ret == null && isset($_GET[$name])) $ret = $_GET[sanitize_text_field($name)];
			}
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_GET[$name])) $ret = $_GET[sanitize_text_field($name)];
			}
			if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
				$putdata = fopen("php://input", "r");
				$para = parse_str($putdata);
				if (isset($para[$name])) $ret = $para[sanitize_text_field($name)];
				else $ret = $para;
			}
			return $ret;
		}
		public static function issetRPara($name) {
			if ($_SERVER['REQUEST_METHOD'] === 'POST') {
				if (isset($_POST[$name])) return true;
				if (isset($_GET[$name])) return true;
				return false;
			}
			if ($_SERVER['REQUEST_METHOD'] === 'GET') {
				if (isset($_GET[$name])) return true;
				return false;
			}
			return false;
		}
		public static function PasswortGenerieren($anzahl=8) {
			$werte = array_merge(array(2,3,4,5,6,7,8,9), array("a","b","c","d","e","f","g","h","j","k","m","n","p","q","r","s","t","w","x","y","z"));
			$pw = "";
			for ($a=0;$a<$anzahl;$a++):
				shuffle($werte);
				$zufallszahl = rand(0, count($werte)-1);
				$buchstabe = $werte[$zufallszahl];
				if ($a == 0 && $buchstabe == ".")
					$buchstabe = "a"; // weil man den Punkt am Anfang nicht sieht
				$pw .= $buchstabe;
			endfor;
			return $pw;
		}
		public static function isOrderPaid($order) {
			$order_status = $order->get_status();
			//$ok_order_statuses = ['wc-completed', 'completed'];
			$ok_order_statuses = wc_get_is_paid_statuses();
			return in_array($order_status, $ok_order_statuses);
		}
	}
}
?>