<?php
require 'validation.php';


$conditions = [					
		'username'  	=> array('Alpha&Number','yes', 50 , 5 , 'username filed' ),
		'email' 	 	=> array('Email', 'yes' , 100 , 10 , 'email error' ),
		'first_name' 	=> array('Arabic_alpha&Alpha', 'yes' , 100 , 10 , 'email error' ),
		'last_name' 	=> array('Arabic_alpha&Alpha', 'yes' , 100 , 10 , 'email error' ),
		'ip'            => array('Ip' , 'yes',15,7),
		'date'			=> array('Date','yes'),
		'time'			=> array('Time' , 'yes'),
		'color' 		=> array('Color','yes')
];

// for print error
$un_error_class 	= '';
$un_error_text  	= '';

$email_error_class 	= '';
$email_error_text  	= '';

$fn_error_class		= '';
$fn_error_text 		= '';

$ln_error_class 	= '';
$ln_error_text 		= '';

$ip_error_class 	= '';
$ip_error_text 		= '';

$date_error_class 	= '';
$date_error_text 	= '';

$time_error_class 	= '';
$time_error_text 	= '';

$color_error_class 	= '';
$color_error_text 	= '';



if (isset($_POST['submit']))
{
	$e = new Validation();
	$validate = $e->validate_all($conditions , $_POST);
	
	/*
	* $validate['success'] return bool , true if the validate is success 
	*/
	if ($validate['success'])
	{
		echo "<center><h2> the validation is successful </h2></center>";
		exit();
	}
	else
	{
		if(!$validate['username'])
		{
			$un_error_class 	= 'has-error';
		}
		if(!$validate['email'])
		{
			$email_error_class 	= 'has-error';
		}
		if(!$validate['first_name'])
		{
			$fn_error_class 	= 'has-error';
		}
		if(!$validate['last_name'])
		{
			$ln_error_class 	= 'has-error';
		}
		if(!$validate['ip'])
		{
			$ip_error_class 	= 'has-error';
		}
		if(!$validate['date'])
		{
			$date_error_class 	= 'has-error';
		}
		if(!$validate['time'])
		{
			$time_error_class 	= 'has-error';
		}
		if(!$validate['color'])
		{
			$color_error_class 	= 'has-error';
		}
	}
}

?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP validation Example one</title>
     
        <!-- plugin styles -->
     	<link href="https://fonts.googleapis.com/css?family=Cairo|Montserrat" rel="stylesheet">
     	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!-- styles file -->
        <link rel="stylesheet" href="css/main.css">
        <!-- my plugins styles -->
        <link rel="stylesheet" href="css/myplugin/forms.css">
    </head>
<body>
<br /><br /><br />
<div class="row">
	<div class="col-sm-8 col-sm-offset-2">
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method='post'>

		  <div class="form-group <?php echo $un_error_class; ?>">
		    <label for="">user name</label>
		    <input type="text" name='username' class="form-control"  placeholder="username">
		    <?php echo $un_error_text; ?>
		  </div>

		  <div class="form-group <?php echo $email_error_class; ?>">
		    <label for="">Email address</label>
		    <input type="email" name="email" class="form-control"  placeholder="Email">
		  	<?php echo $email_error_text; ?>
		  </div>

		  <div class="form-group <?php echo $fn_error_class; ?>">
		    <label for="">first name</label>
		    <input type="text" name="first_name" class="form-control"  placeholder="first name">
		  	<?php echo $fn_error_text; ?>
		  </div>

		  <div class="form-group <?php echo $ln_error_class; ?>">
		    <label for="">Last name</label>
		    <input type="text" name="last_name" class="form-control"  placeholder="last name">
		  	<?php echo $ln_error_text; ?>
		  </div>

		  <div class="form-group <?php echo $ip_error_class; ?>">
		    <label for="">Ip</label>
		    <input type="text" name="ip" class="form-control"  placeholder="___.___.___.___">
		  	<?php echo $ip_error_text; ?>
		  </div>

		  <div class="form-group <?php echo $date_error_class; ?>">
		    <label for="">Date</label>
		    <input type="date" name="date" class="form-control"  placeholder="date">
		  	<?php echo $date_error_text; ?>
		  </div>

		  <div class="form-group <?php echo $time_error_class; ?>">
		    <label for="">Time</label>
		    <input type="time" name="time" class="form-control"  placeholder="">
		  	<?php echo $time_error_text; ?>
		  </div>

		   <div class="form-group <?php echo $color_error_class; ?>">
		    <label for="">Color</label>
		    <input type="color" name="color" class="form-control"  placeholder="">
		  	<?php echo $color_error_text; ?>
		  </div>

		  <button type="submit" name="submit" class="btn btn-default">Submit</button>
		</form>
	</div>
</div>

</body>
</html>
