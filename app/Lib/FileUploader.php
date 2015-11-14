<?php //FILEUPLOADER 1.0.2 - LAURENT JERBER

class FileUploader extends FileTools {
	const VERSION = "1.0.2";
	
	private $path;
	private $name;
	private $file;
	private $extensions = array();
	private $resize = false;
	private $copy = false;
	private $dimensions = array();
	private $maximumDimensions = array();
	private $minimumDimensions = array();
	private $maxSize;
	private $added = 0;
	
	private $available = array(
		FileTools::JPG, FileTools::PNG, FileTools::GIF, FileTools::BMP,
		FileTools::PHP, FileTools::HTML, FileTools::CSS, FileTools::JS,
		FileTools::TXT, FileTools::PDF,
		FileTools::ZIP, FileTools::RAR, FileTools::APK, FileTools::ISO, FileTools::GZ,
		FileTools::DOC, FileTools::DOCX, FileTools::ODT,
		FileTools::PPT
	);
	private $resizable = array(FileTools::JPG, FileTools::PNG, FileTools::GIF);
	
	const NO_DEFINED_PATH = "FUERROR::NO_DEFINED_PATH";
	const FILE_TYPE_NOT_SUPPORTED = "FUERROR::FILE_TYPE_NOT_SUPPORTED";
	const FILE_TYPE_NOT_RESIZABLE = "FUERROR::FILE_TYPE_NOT_RESIZABLE";
	const FILE_IS_TOO_BIG = "FUERROR::FILE_IS_TOO_BIG";
	const MAX_WIDTH_EXCEEDED = "FUERROR::MAX_WIDTH_EXCEEDED";
	const MAX_HEIGHT_EXCEEDED = "FUERROR::MAX_HEIGHT_EXCEEDED";
	const MIN_WIDTH_UNREACHED = "FUERROR::MIN_WIDTH_UNREACHED";
	const MIN_HEIGHT_UNREACHED = "FUERROR::MIN_HEIGHT_UNREACHED";
	const IMAGECREATEFROM_FAILED = "FUERROR::IMAGECREATEFROM_FAILED";
	const IMAGECREATETRUECOLOR_FAILED = "FUERROR::IMAGECREATETRUECOLOR_FAILED";
	const IMAGECOPYRESAMPLED_FAILED = "FUERROR::IMAGECOPYRESAMPLED_FAILED";
	const IMAGECREATION_FAILED = "FUERROR::IMAGECREATION_FAILED";
	
	function __construct() {
		$args = func_get_args();
		
		$this -> setPath($args[0]);
		if (isset($args[1])) $this -> setMaxSize($args[1]);
	}
	
	public function setPath($path) {
		if (is_string($path))
			$this -> path = $path;
	}
	
	public function setFile($file) {
		if (is_array($file))
			$this -> file = $file;
	}
	
	public function setExtensions() {
		$args = func_get_args();

		if (is_array($args)) {
			foreach ($args as $extension) {
				if (in_array($extension, $this -> available))
					$this -> extensions[] = $extension;
			}
		}
	}
	
	public function setCopy() {
		$args = func_get_args();
		if (is_boolean($args[0])) $this -> copy = $args[0];
		else $this -> copy = true;
	}
	
	public function setMaxSize($size) {
		$this -> maxSize = self::sizeToOctet($size);
	}
	
	public function setMaximumDimensions($width, $height) {
		if (is_numeric($width) && is_numeric($height))
			$this -> maximumDimensions = array('width' => $width, 'height' => $height);
	}
	
	public function setMinimumDimensions($width, $height) {
		if (is_numeric($width) && is_numeric($height))
			$this -> minimumDimensions = array('width' => $width, 'height' => $height);
	}
	
	public function setDimensionsLimit($minWidth, $minHeight, $maxWidth, $maxHeight) {
		if (is_numeric($minWidth) && is_numeric($minHeight))
			$this -> minimumDimensions = array('width' => $minWidth, 'height' => $minHeight);
		if (is_numeric($maxWidth) && is_numeric($maxHeight))
			$this -> maximumDimensions = array('width' => $maxWidth, 'height' => $maxHeight);
	}
	
	public function add() {
		$args = func_get_args();
		
		if (isset($args[0])) $this -> setFile($args[0]);
		if (isset($args[1])) $this -> name = $args[1]; else $this -> name = null;
		if (isset($args[2])) $this -> setExtensions($args[2]);
		$this -> added++;
	}
	
	public function resize($width) {
		$args = func_get_args();
		if (isset($this -> path)) {
			if (isset($args[1]) && intval($args[1])) $height = $args[1];
			$file = $this -> file;
			$extension = preg_replace("#^(.+)\.([a-zA-Z0-9]{2,4})$#", "$2", $file['name']);
			if (self::acceptedExtension($extension, $this -> resizable)) {
				if ($file['size'] <= $this -> maxSize) {
					$dimensions = getimagesize($file['tmp_name']);
					if (isset($this -> maximumDimensions['width']) && $dimensions[0] <= $this -> maximumDimensions['width'] || !isset($this -> maximumDimensions['width'])) {
						if (isset($this -> maximumDimensions['height']) && $dimensions[1] <= $this -> maximumDimensions['height'] || !isset($this -> maximumDimensions['height'])) {
							if (isset($this -> maximumDimensions['width']) && $dimensions[0] >= $this -> minimumDimensions['width'] || !isset($this -> minimumDimensions['width'])) {
								if (isset($this -> maximumDimensions['height']) && $dimensions[1] >= $this -> minimumDimensions['height'] || !isset($this -> minimumDimensions['height'])) {
									if (!isset($height)) $height = round($dimensions[1] * $width / $dimensions[0]);
									
									if (isset($args[2])) $name = FileTools::treatName(FileTools::extractFilename($args[2]));
									elseif (strlen($this -> name) > 0) $name = FileTools::treatName(FileTools::extractFilename($this -> name) . "_" . $width . "x" . $height);
									else $name = FileTools::extractFilename($file['name']) . "_" . $width . "x" . $height;

									if (preg_match(FileTools::JPG, $extension)) $builder = imagecreatefromjpeg($file['tmp_name']);
									elseif (preg_match(FileTools::PNG, $extension)) $builder = imagecreatefrompng($file['tmp_name']);
									elseif (preg_match(FileTools::GIF, $extension)) $builder = imagecreatefromgif($file['tmp_name']);
									
									if (!$builder) return self::IMAGECREATEFROM_FAILED;
									
									$resized = imagecreatetruecolor($width, $height);
									
									if (preg_match(FileTools::PNG, $extension)) {
										imagealphablending($resized, false);
										imagesavealpha($resized, true);
									}
									if (!$resized) return self::IMAGECREATETRUECOLOR_FAILED;
										
									if (!imagecopyresampled($resized, $builder, 0, 0, 0, 0, $width, $height, $dimensions[0], $dimensions[1]))
										return self::IMAGECOPYRESAMPLED_FAILED;
									
									$destination = $this -> path . $name . "." . $extension;
									
									if (preg_match(FileTools::JPG, $extension)) $result = imagejpeg($resized, $destination);
									elseif (preg_match(FileTools::PNG, $extension)) $result = imagepng($resized, $destination);
									elseif (preg_match(FileTools::GIF, $extension)) $result = imagegif($resized, $destination);
									
									if ($result) return $destination;
									else return self::IMAGECREATION_FAILED;
								} else return self::MIN_HEIGHT_UNREACHED;
							} else return self::MIN_WIDTH_UNREACHED;
						} else return self::MAX_HEIGHT_EXCEEDED;
					} else return self::MAX_WIDTH_EXCEEDED;
				} else return self::FILE_IS_TOO_BIG;
			} else return self::FILE_TYPE_NOT_SUPPORTED;
		} else return self::NO_DEFINED_PATH;
	}
	
	public function upload() {
		if ($this -> path) {
			$file = $this -> file;
			$extension = preg_replace("#^(.+)\.([a-zA-Z0-9]{2,4})$#", "$2", $file['name']);
			if (self::acceptedExtension($extension, $this -> extensions) || count($this -> extensions) == 0) {
				if ($file['size'] <= $this -> maxSize) {
					$dimensions = getimagesize($file['tmp_name']);
					if (isset($this -> maximumDimensions['width']) && $dimensions[0] <= $this -> maximumDimensions['width'] || !isset($this -> maximumDimensions['width'])) {
						if (isset($this -> maximumDimensions['height']) && $dimensions[1] <= $this -> maximumDimensions['height'] || !isset($this -> maximumDimensions['height'])) {
							if (isset($this -> minimumDimensions['width']) && $dimensions[0] <= $this -> minimumDimensions['width'] || !isset($this -> minimumDimensions['width'])) {
								if (isset($this -> minimumDimensions['height']) && $dimensions[1] <= $this -> minimumDimensions['height'] || !isset($this -> minimumDimensions['height'])) {
									if (strlen($this -> name) > 0) $name = self::treatName(FileTools::extractFilename($this -> name));
									else $name = preg_replace("#^(.+)\.([a-zA-Z0-9]{2,4})$#", "$1", $file['name']);
									if (move_uploaded_file($file['tmp_name'], $this -> path . $name . "." . $extension)) return $this -> path . $name . "." . $extension;
									else return false;
								} else return self::MIN_HEIGHT_UNREACHED;
							} else return self::MIN_WIDTH_UNREACHED;
						} else return self::MAX_HEIGHT_EXCEEDED;
					} else return self::MAX_WIDTH_EXCEEDED;
				} else return self::FILE_IS_TOO_BIG;
			} else return self::FILE_TYPE_NOT_RESIZABLE;
		} else return self::NO_DEFINED_PATH;
	}
	
	public static function HTMLForm() {
		$args = func_get_args();
		if (isset($args[0])) {
			$form = '<input type="file" name="' . $args[0] . '" id= "' . $args[0] . '"/>';
			if (isset($args[1]))
				$form .= '<input type="hidden" name="MAX_FILE_SIZE" value="' . self::sizeToOctet($args[1]) . '"/>';
			return $form;
		}
	}
		
		
	public static function sizeToOctet($size) {
		$units = array("K" => 1, "M" => 2, "G" => 3, "T" => 4);
		$value = preg_replace("#^([0-9]{1,})([KkMmGgTt])o$#", "$1", $size);
		$unit = preg_replace("#^([0-9]{1,})([KkMmGgTt])o$#", "$2", $size);

		$tours = $units[$unit];
		for ($i = 0; $i < $tours; $i++) {
			$value *= 1024;
		} 
		return $value;
	}
	
	public static function acceptedExtension($extension, $regexList) {
		foreach ($regexList as $regex) {
			if (preg_match($regex, $extension)) return true;
		} return false;
	}
	
	public static function isError($test) {
		if (substr($test, 0, 9) == "FUERROR::") return true;
		return false;
	}
}	
		
		
		
		
		