<?php
include(plugin_dir_path(__FILE__)."init_file.php");
class vollstart_PDF {
    private $parts = [];
    private $filemode;
    private $filepath;
    private $filename;
    private $orientation = "P";
    private $page_format = 'A4';
	private $qr;
	private $isRTL = false;

    public function __construct($parts=[], $filemode="I", $filename="PDF.pdf") {
        if (is_array($parts)) $this->setParts($parts);
		// $this->initVars();
		$this->setFilemode($filemode);
		$this->setFilename($filename);
        $this->_loadLibs();
		$this->initQR();
    }

	private function initVars() {
		/*
		$this->filepath = __DIR__."/pdfouts/";
		if (!file_exists($this->filepath)) {
			//mkdir($this->filepath, 0777);
			//chmod($this->filepath, 0777);
		}
		*/
	}

	public function initQR() {
		$this->qr = [
			"text"=>"",
			"type"=>"QRCODE,Q",
			"size"=>["width"=>50, "height"=>50],
			"pos"=>["x"=>150, "y"=>10],
			"align"=>"N",
			"style"=> [
				'border' => 0,
				'vpadding' => 'auto',
				'hpadding' => 'auto',
				'fgcolor' => array(0,0,0),
				'bgcolor' => false, //array(255,255,255)
				'module_width' => 1, // width of a single module in points
				'module_height' => 1 // height of a single module in points
			]
		];
	}

	public function setRTL($rtl=false) {
		$this->isRTL = $rtl;
	}
	public function setQRCodeContent($qr) {
		foreach ($qr as $key => $value) {
			$this->qr[$key] = $value;
		}
	}
    public function setPageFormat($format) {
        $this->page_format = trim($format);
    }
	public function setOrientation($value){
		// L oder P
		$this->orientation = addslashes(trim($value));
	}
	public function setFilemode($m) {
		$this->filemode = $m;
	}
	public function setFilename($p) {
		$this->filename = trim($p);
	}
    public function setParts($parts=[]) {
		$this->parts = [];
		foreach($parts as $part) {
			$this->addPart($part);
		}
	}
	public function addPart($part) {
		$teile = explode('{PAGEBREAK}', $part);
		foreach($teile as $teil) {
			$this->parts[] = $teil;
		}
	}

	private function getParts() {
		return $this->parts;
	}

    private function _loadLibs() {
		// Include the main TCPDF library (search the library on the following directories).
		/*
		spl_autoload_register(function($class_name){
			$datei = "vendors/TCPDF/".$class_name.".php";
			if (!file_exists($datei)) {
				$datei = "vendors/TCPDF/".strtolower($class_name).".php";
				if (!file_exists($datei)) {
					$datei = "vendors/TCPDF/".str_replace("\\", "/", $class_name).".php";
					if (!file_exists($datei)) throw new Exception("class not found for autoloading: ".$class_name." in ".$datei);
				}
			}
			include_once($datei);
		});
		*/

		// always load alternative config file for examples
		require_once('vendors/TCPDF/config/tcpdf_config.php');

		// Include the main TCPDF library (search the library on the following directories).
		$tcpdf_include_dirs = array(
			plugin_dir_path(__FILE__).'vendors/TCPDF/tcpdf.php',
			realpath(dirname(__FILE__) . '/vendors/TCPDF/tcpdf.php'),// True source file
			realpath('vendors/TCPDF/tcpdf.php'),// Relative from $PWD
			'/usr/share/php/tcpdf/tcpdf.php',
			'/usr/share/tcpdf/tcpdf.php',
			'/usr/share/php-tcpdf/tcpdf.php',
			'/var/www/tcpdf/tcpdf.php',
			'/var/www/html/tcpdf/tcpdf.php',
			'/usr/local/apache2/htdocs/tcpdf/tcpdf.php'
		);
		foreach ($tcpdf_include_dirs as $tcpdf_include_path) {
			if (@file_exists($tcpdf_include_path)) {
				require_once($tcpdf_include_path);
				break;
			}
		}
	}

    public function render() {
		$pdf = new TCPDF($this->orientation, PDF_UNIT, $this->page_format, true, 'UTF-8', false, false);
        $preferences = [
            //'HideToolbar' => true,
            //'HideMenubar' => true,
            //'HideWindowUI' => true,
            //'FitWindow' => true,
            'CenterWindow' => true,
            //'DisplayDocTitle' => true,
            //'NonFullScreenPageMode' => 'UseNone', // UseNone, UseOutlines, UseThumbs, UseOC
            //'ViewArea' => 'CropBox', // CropBox, BleedBox, TrimBox, ArtBox
            //'ViewClip' => 'CropBox', // CropBox, BleedBox, TrimBox, ArtBox
            'PrintArea' => 'CropBox', // CropBox, BleedBox, TrimBox, ArtBox
            //'PrintClip' => 'CropBox', // CropBox, BleedBox, TrimBox, ArtBox
            'PrintScaling' => 'None', // None, AppDefault
            'Duplex' => 'DuplexFlipLongEdge', // Simplex, DuplexFlipShortEdge, DuplexFlipLongEdge
            'PickTrayByPDFSize' => true,
            //'PrintPageRange' => array(1,1,2,3),
            //'NumCopies' => 2
        ];
        if ($this->orientation == "L") $preferences['Duplex'] = "DuplexFlipShortEdge";
        $pdf->setViewerPreferences($preferences);
		$pdf->SetAutoPageBreak(TRUE, 5);
		//$pdf->SetFont('helvetica', '', "12pt");
		$pdf->SetFont('dejavusans', '', "12pt");

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setJPEGQuality(90);

		// set margins
		//$pdf->SetMargins(0, 0, 0);
		//$pdf->SetMargins(PDF_MARGIN_LEFT, 17, 10);
		//$pdf->SetHeaderMargin(10);
		//$pdf->SetFooterMargin(10);

		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);

		$page_parts = $this->getParts();
		// Print text using writeHTMLCell()
		$pdf->AddPage();

		$w_image = $this->orientation == "L" ? 297 : 210;
		$h_image = $this->orientation == "L" ? 210 : 210;

		if ($this->isRTL) {
			$pdf->setRTL(true);
		}

		foreach($page_parts as $p) {
			try {
				if ($p == "{PAGEBREAK}") {
					$pdf->AddPage();
					continue;
				}
				$teile = explode('{PAGEBREAK}', $p);
				$counter = 0;
				foreach($teile as $teil) {
					$counter++;
					if ($counter > 1) $pdf->AddPage();
					if ($teil == "{QRCODE}") {
						if (!empty($this->qr['text'])) {
							$pdf->write2DBarcode($this->qr['text'], $this->qr['type'], $this->qr['pos']['x'], $this->qr['pos']['y'], $this->qr['size']['width'], $this->qr['size']['height'], $this->qr['style'], $this->qr['align']);
						}
					} else {
						//$pdf->writeHTMLCell(0, 0, '', '', $teil, 0, 1, 0, true, '', true);
						$pdf->writeHTML($teil, true, 0, true, 0);
					}
				}
			} catch (Exception $e) {}
		}

		if ($this->filemode == "F") {
			$pdf->Output($this->filepath.$this->filename, $this->filemode);
		} else {
			$pdf->Output($this->filename, $this->filemode);
		}
    }

}
?>