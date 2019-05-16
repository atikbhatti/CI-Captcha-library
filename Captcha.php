<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Captcha
{
	private static $salt = '9ie37f';

	private static $numberI;
	private static $numberII;
	private static $answer = null;
	private static $captchaImg = null;
	// private static $captchaId;
	
	public static function init()
	{
		if (session_status() == PHP_SESSION_NONE) {
		    session_start();
		}
	}
	
	// Generates new captcha
	public static function generate()
	{
		self::init();

		self::$numberI = rand(11, 19) * rand(1, 3);
		self::$numberII = rand(1, 9) * rand(1, 3);
		
		$_operators = ['+', '-'];
		$operator = $_operators[array_rand($_operators)];
		$operation = ['+' => ' plus ', '-' => ' minus '];

		$answer = self::$salt . (eval( 'return '.self::$numberI .$operator. self::$numberII.';'));

		$_SESSION['token'] = self::$answer = hash('sha256', $answer);

		// Create a canvas
		if ( (self::$captchaImg = @imagecreatetruecolor(150, 25)) === false ) {
			throw new Exception('Creation of true color image failed');
		}
		
		// Allocate black and white colors
		$color_black = imagecolorallocate(self::$captchaImg, 0, 0, 0);
		$color_white = imagecolorallocate(self::$captchaImg, 255, 255, 255);
		
		// Make the background of the image white
		imagecolortransparent(self::$captchaImg, 0);
		
		// Draw the math question on the image using black color
		imagestring(self::$captchaImg, 10, 0, 10,  self::$numberI . $operation[$operator] . self::$numberII , $color_white);	
	}
	
	// Outputs captcha png
	public static function output()
	{	
		if ( self::$captchaImg === null ) {
			throw new Exception('Captcha image has not been generated');
		}
		
		// header('Content-Type: image/png');
		ob_start();
		imagepng(self::$captchaImg);
		$data = ob_get_contents();
		ob_end_clean();
		
		imagedestroy(self::$captchaImg);

		echo '<img src="data:image/png;base64,' . base64_encode($data).'" style="border-radius: 3px;"/>';
	}
	
	// Verifies captcha
	public static function verify( $answer )
	{
		self::init();

		// Check if math captcha has been generated
		if ( empty($answer) )
			return false;

		// Validates captcha
		$ans = (int) trim($answer);
		$ans = self::$salt . $answer;
		$ans = hash('sha256', $ans);

		if ($_SESSION['token'] ==  $ans) {
			return true;
		} else {
			return false;
		}
	}
}