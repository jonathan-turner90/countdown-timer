<?php

	//Leave all this stuff as it is
	date_default_timezone_set('Europe/London');
	include 'GIFEncoder.class.php';
	include 'php52-fix.php';
	$time = $_GET['time'];
	$date = '2020-05-15 13:04';
	$future_date = new DateTime(date($date,strtotime($time)));
	$time_now = time();
	$now = new DateTime(date('r', $time_now));
	$frames = array();	
	$delays = array();



	// Your image link
	$image = imagecreatefrompng('images/countdown_new.png');

	$delay = 100;// milliseconds

	$font = array(
		'size' => 45, // Font size, in pts usually.
		'angle' => 0, // Angle of the text
		'x-offset' => 78, // The larger the number the further the distance from the left hand side, 0 to align to the left.
		'y-offset' => 55, // The vertical alignment, trial and error between 20 and 60.
		'file' => __DIR__ . DIRECTORY_SEPARATOR . 'Montserrat-Regular.otf', // Font path
		'color' => imagecolorallocate($image, 255, 0, 0), // RGB Colour of the text
	);
	$fallback = array(
		'size' => 30,
		'x-offset' => 100,
		'y-offset' => 50,
	);
	$day = array(
		'x-offset' => 78, // The larger the number the further the distance from the left hand side, 0 to align to the left.
	);

	$hour = array(
		'x-offset' => 255, // The larger the number the further the distance from the left hand side, 0 to align to the left.
	);
	$minute = array(
		'x-offset' => 428, // The larger the number the further the distance from the left hand side, 0 to align to the left.
	);
	$second = array(
		'x-offset' => 596, // The larger the number the further the distance from the left hand side, 0 to align to the left.
	);
	$firstdivider = array(
		'x-offset' => 192, // The larger the number the further the distance from the left hand side, 0 to align to the left.
		'y-offset' => 50, // The vertical alignment, trial and error between 20 and 60.
	);
	$seconddivider = array(
		'x-offset' => 364, // The larger the number the further the distance from the left hand side, 0 to align to the left.
		'y-offset' => 50, // The vertical alignment, trial and error between 20 and 60.
	);
	$thirddivider = array(
		'x-offset' => 539, // The larger the number the further the distance from the left hand side, 0 to align to the left.
		'y-offset' => 50, // The vertical alignment, trial and error between 20 and 60.
	);
	for($i = 0; $i <= 60; $i++){
		
		$interval = date_diff($future_date, $now);
		
		if($future_date < $now){
			// Open the first source image and add the text.
			$image = imagecreatefrompng('images/countdown_new.png');
			;
			$text = $interval->format('The countdown has ended');
			imagettftext ($image , $fallback['size'] , $font['angle'] , $fallback['x-offset'] , $fallback['y-offset'] , $font['color'] , $font['file'], $text );
			ob_start();
			imagegif($image);
			$frames[]=ob_get_contents();
			$delays[]=$delay;
			$loops = 1;
			ob_end_clean();
			break;
		} else {
			// Open the first source image and add the text.
			$image = imagecreatefrompng('images/countdown_new.png');
			;
			$days = $interval->format('0%a');
			$dividerone = $dividertwo = $dividerthree = ':';
			$hours = $interval->format('%H');
			$minutes = $interval->format('%I');
			$seconds = $interval->format('%S');
			imagettftext ($image , $font['size'] , $font['angle'] , $day['x-offset'] , $font['y-offset'] , $font['color'] , $font['file'],  $days );
			imagettftext ($image , $font['size'] , $font['angle'] , $hour['x-offset'] , $font['y-offset'] , $font['color'] , $font['file'],  $hours );
			imagettftext ($image , $font['size'] , $font['angle'] , $minute['x-offset'] , $font['y-offset'] , $font['color'] , $font['file'],  $minutes );
			imagettftext ($image , $font['size'] , $font['angle'] , $second['x-offset'] , $font['y-offset'] , $font['color'] , $font['file'],  $seconds );
			imagettftext ($image , $font['size'] , $font['angle'] , $firstdivider['x-offset'] , $firstdivider['y-offset'] , $font['color'] , $font['file'],  $dividerone );
			imagettftext ($image , $font['size'] , $font['angle'] , $seconddivider['x-offset'] , $seconddivider['y-offset'] , $font['color'] , $font['file'],  $dividertwo );
			imagettftext ($image , $font['size'] , $font['angle'] , $thirddivider['x-offset'] , $thirddivider['y-offset'] , $font['color'] , $font['file'],  $dividerthree );

			ob_start();
			imagegif($image);
			$frames[]=ob_get_contents();
			$delays[]=$delay;
			$loops = 0;
			ob_end_clean();
		}

		$now->modify('+1 second');
	}

	//expire this image instantly
	header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
	header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
	header( 'Cache-Control: no-store, no-cache, must-revalidate' );
	header( 'Cache-Control: post-check=0, pre-check=0', false );
	header( 'Pragma: no-cache' );
	$gif = new AnimatedGif($frames,$delays,$loops);
	$gif->display();


