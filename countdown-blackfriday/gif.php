<?php
	date_default_timezone_set('America/Sao_Paulo');
	include '../commons/GIFEncoder.class.php';
	include '../commons/php52-fix.php';
	$time = $_GET['time'];
	$future_date = new DateTime(date('r',strtotime($time)));
	$time_now = time();
	$now = new DateTime(date('r', $time_now));
	$frames = array();	
	$delays = array();

	$image = imagecreatefrompng('images/countdown.png');
	$delay = 100;// milliseconds
	$font = array(
		'size' => 30, // Font size, in pts usually.
		'angle' => 0, // Angle of the text
		'x-offset' => 40, // The larger the number the further the distance from the left hand side, 0 to align to the left.
		'y-offset' => 70, // The vertical alignment, trial and error between 20 and 60.
		'file' => __DIR__ . DIRECTORY_SEPARATOR . 'HelveticaNeue.otf', // Font path
		'color' => imagecolorallocate($image, 255, 108, 0), // RGB Colour of the text
	);

	for($i = 0; $i < 60; $i++){
		
		$interval = date_diff($future_date, $now);
		
		if($now > $future_date){
			$image = imagecreatefrompng('images/countdown.png');

			$text = $interval->format('Começou');

			imagettftext ($image , 40 , $font['angle'] , $font['x-offset'] , $font['y-offset'] , $font['color'] , $font['file'], $text );
			ob_start();

			imagegif($image);

			$frames[]=ob_get_contents();
			$delays[]=$delay;

			ob_end_clean();
		} else {
			$image = imagecreatefrompng('images/countdown.png');

			$text = $interval->format('0%a : %H : %I : %S');

			imagettftext ($image , 32, $font['angle'] , 16, $font['y-offset'] , $font['color'] , $font['file'], $text );
			ob_start();

			imagegif($image);

			$frames[]=ob_get_contents();
			$delays[]=$delay;
			ob_end_clean();
		}
		$now->modify('+1 second');
	}

	header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
	header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
	header( 'Cache-Control: no-store, no-cache, must-revalidate' );
	header( 'Cache-Control: post-check=0, pre-check=0', false );
	header( 'Pragma: no-cache' );
	$gif = new AnimatedGif($frames,$delays,1);
	$gif->display();