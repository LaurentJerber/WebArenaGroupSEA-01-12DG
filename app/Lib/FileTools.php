<?php

class FileTools {
	const VERSION = "1.0";
	
	const JPG = "#^jpg$|^jpeg$#";
	const PNG = "#^png$#";
	const GIF = "#^gif$#";
	const BMP = "#^bmp$#";
	const PHP = "#^php$#";
	const HTML = "#^html$#";
	const CSS = "#^css$#";
	const JS = "#^js$#";
	const TXT = "#^txt$#";
	const ZIP = "#^zip$#";
	const RAR = "#^rar$#";
	const APK = "#^apk$#";
	const ISO = "#^iso$#";
	const GZ = "#^gz$#";
	const PDF = "#^pdf$#";
	const DOC = "#^doc$#";
	const DOCX = "#^docx$#";
	const ODT = "#^odt$#";
	const PPT = "#^ppt$#";
	const PPTX = "#^pptx$#";
	
	public static function extractFilename($file) {
		return preg_replace("#(.+)\.([0-9a-zA-Z]{1,5})$#", "$1", $file);
	}
	
	public static function extractExtension($file) {
		return preg_replace("#(.*)\.([0-9a-zA-Z]{1,5})$#", "$2", $file);
	}
	
	public static function createArrayPath($file) {
		$arr = kp_explode('/', $file);
		if (count($arr) == 1) $arr = kp_explode('\\', $file);
		return $arr;
	}
	
	public static function treatName($name) {
		$name = preg_replace("#[èéêë]#", "e", $name);
		$name = preg_replace("#[àäâ]#", "a", $name);
		$name = preg_replace('/([^.a-z0-9]+)/i', "-", $name);
		$name = preg_replace("#[¨¨=<>\"']#", "", $name);
		return $name;
	}
}