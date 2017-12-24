
<html>
	<head>
		<title>
		</title>

		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	</head>
<?php 




class Validation
{


	private $_rulse_delimiter = '&';

	private $_or_delimiter = '|';
	
	private $_rulse_error_message = 'rulse valid';

	private $_error = array();
	
	//
	private $_default_required = 'yes';
	
	private $_default_Max      = 100;
	
	private $_default_Min      = 10;
	
    private $_default_error    = 'data valid!';

	private $_patterns = [
		'Any' 				=>'(.+)',
		// حروف اللغات الاخرى
		'Arabic_alpha'		=> '\\x{0621}-\\x{063a}\\x{0641}-\\x{064a}',
		'Arabic_number' 	=> '\\x{0660}-\\x{0669}',
		// 
		'Alpha'				=> 'a-zA-Z',
		'Spaces'            => '\\s',
		// Numbers
		'Numeric' 			=> '0-9',// 
		// password 
			];

	//
	private $_rulse  = [
		'required', //
		'Any',
		'Any/',
		//
		'Arabic_alpha',
		'Arabic_number',
		//
		'Alpha',
		'Spaces',
		// Numbers
		'Numeric', // 
		'Int', //
		'N+', // Any number betwen [0 , infinty[
		'N-'  // Any number betwen ]infinty , 0]
	];
	//

	private $_one_rulse  = [
	// اختيار واحد
		'Float',//
		'Email',
		'Url',
		'Domin',
		'Ip',
		'Phone'
	];

	public function __construct()
	{
	

	}
	/**
	* validate array
	* @param array
	* @param array
	* @return array
	*/
	public function validate_all($conditions,$data)
	{
      // check Exception
      $this->check_all($conditions);
      
      if(!is_array($data))
      {
          throw new Exception($this->_rulse_error_message);
      }
      $errors = array();
      foreach($conditions as $name => $val)
      {
          if(is_array($data[$name]))
          {
          	$status_array = array();
          	$errors_array = array();

          	foreach ($val as $key => $value)
          	{
          		$validate = $this->validate_helper($conditions[$name] , $val);
          		$status_array[$key] = $validate['error_status'];
          		$errors_array[$key] = $validate['errors'];
          	}

          	$errors[$name] = $status_array;
          	$errors[$name .'_error'] = $errors_array;
              
          }
          else
          {

          	$validate = $this->validate_helper($val , $data[$name]);
          	$errors[$name] = $validate['error_status'];
          	$errors[$name.'_error'] = $validate['errors'];

          }
      }

      return $errors;
	}

	/**
	*@param array
	*@param any
	*@return array
	*/
	private function validate_helper($conditions , $val)
	{
		$pattern       = $conditions[0];
        $required      = (isset($conditions[1])) ? $conditions[1] : $this->_default_required;
        $max_length    = (isset($conditions[2])) ? $conditions[2] : $this->_default_Max;
		$min_length    = (isset($conditions[3])) ? $conditions[3] : $this->_default_Min;
		$error_message = (isset($conditions[4])) ? $conditions[4] : $this->_default_error;
        
        $errors = array();     
        if($required == 'yes' || !empty($val))
      	{
			$pattern   = $this->is($pattern,$val);
			$required  = true;
			$max_length= $this->max_length($max_length , $val);
			$min_length= $this->min_length($min_length , $val);

			$error_status 	= ( $pattern * $required * $max_length * $min_length );
			$errors 		= array(
                  				'required' => $required , 'pattern' => $pattern ,
              					'max' => $max_length , 'min' => $min_length , 'error_message' => $error_message  
              					);       
                
		}
		else if($required == 'no')
		{
            $error_status = true; 

		}

		$ret['error_status'] = $error_status;
		$ret['errors'] 		 = $errors;
		return $ret;
	}
	/**
	*@param string
	*@param string
	*@return bool
	*/
	public function is($pattern,$data)
	{
		if (strpbrk($pattern , $this->_rulse_delimiter))
		{
			$new_pattern = $this->create_pattern($pattern);
			if (preg_match($new_pattern, $data))
			{
				return true;
			}
			return false;
		}
		else
		{
			if (array_key_exists($pattern , $this->_patterns))
			{
				$new_pattern = $this->create_pattern($pattern);
				if (preg_match($new_pattern , $data))
				{
					return true;
				}
				return false;
			}
			//
			return $this->{'is'.$pattern}($data);
		}	
	} #

	/** 
	*@param string
	*@return bool
	*/
	public function isEmail($email)
	{
		if( is_bool(filter_var($email , FILTER_VALIDATE_EMAIL) ) )
		{
			return false;
		}
		return true;
	}
	/**
	*@param string
	*@return bool
	*/
	public function isIp($ip)
	{
	   // ipv4 0.0.0.0 - 255.255.255.255
	   $ip = explode('.',$ip);
	   if(count($ip) != 4)
	   {
	       return false;
	   }
	   foreach($ip as $val)
	   {
	       if(!$this->min_length(0 , $val) || !$this->max_length(255 , $val) )
	       {
	           return false;
	       }
	   }
	   return true;
	}
	/** 
	*@param string
	*@return bool
	*/
	public function isFloat($number)
	{
		return is_float($number);
	}
	
	/**
	*@param int
	*@param any
	*@return bool
	*/
	private function max_length($max , $val)
	{
		if(!is_int($max))
		{
			throw new Exception($this->_rulse_error_message);		
		}
		if( strlen($val) > $max )
		{
			return false;
		}
		return true;
	}
	/**
	*@param int
	*@param any
	*@return bool
	*/
	private function min_length($min , $val)
	{
		if(!is_int($min))
		{
			throw new Exception($this->_rulse_error_message);		
		}
		if( strlen($val) < $min )
		{

			return false;
		}
		return true;
	}
	/**
	* @param string
	* @return
	*/
	 private function is_required($val)
	 {
	     if($val == 'yes')
	     {
	         return true;
	     }
	     return false;
	 }
	/**
	*@param string
	*@return string
	*/
	private function create_pattern($pattern)
	{
       $patterns = explode($this->_rulse_delimiter , $pattern);

       $new_pattern = '/^[';
       foreach($patterns as $val)
       {
           $new_pattern .= $this->_patterns[$val];
       }
       $new_pattern = $new_pattern .']+$/u'; 
       var_dump($new_pattern);
       return $new_pattern;
	}
	
	private function check_all($rulse)
	{
		if(is_array($rulse))
		{
			foreach($rulse as $val)
			{
				if(count($val) < 1 or !is_array($val))
				{
					throw new Exception($this->_rulse_error_message);
				}
				//
				$this->check_pattern($val[0]);
				//
				$this->check_required($val[1]);		
			}
		}
		else
		{
			throw new Exception($this->_rulse_error_message);	
		}
	}
	/*
	*
	*/
	private function check_pattern($rulse)
	{
		if(isset($rulse) && !empty($rulse) )
		{
			$rulse_arr = explode( $this->_rulse_delimiter , $rulse );
			if(count($rulse_arr) > 1 )
			{	
				foreach($rulse_arr as $val)
				{
					if(!in_array($val , $this->_rulse) )
					{
						throw new Exception($this->_rulse_error_message);
					}
				}
			}
			else if(!(in_array($rulse , $this->_rulse ) or in_array($rulse , $this->_one_rulse)))
			{
				throw new Exception($this->_rulse_error_message);
			}	
		}
		else
		{
			throw new Exception($this->_rulse_error_message);
		}	
	}
	/**
	*@param array  
	*/
	private function check_required($required)
	{
		if(($required !== 'yes' && $required !== 'no') )
		{
			throw new Exception($this->_rulse_error_message);
		}	
	} #
	/**
	*@param array  
	*/
	
}


$ex = [					// required ? , patterns , Max lenght , Min length , error message or true
		'username'  	=> array('Alpha&Numeric','yes', 50 , 5 , 'username filed' ),
		'email' 	 	=> array('Email', 'yes' , 100 , 10 , 'email error' ),
		'first_name' 	=> array('Arabic_alpha&Alpha', 'yes' , 100 , 10 , 'email error' ),
		'ip'            => array('Ip' , 'yes',15,7)
];
// ex
$error = [

	'username' => 'bool' ,
	'username_error' => array( 'required' => 'bool' , 'pattern' => 'bool' , 'Max' => 'bool' , 'Min'=>'bool' , 'message' =>'your error text '),	 

];





if (isset($_POST['sub']))
{
	$e = new Validation();
	$validate = $e->validate_all($ex , $_POST);
	echo "<pre>";
	print_r($validate);
}


//var_dump(preg_match("/^([\x{0621}-\x{063a}\x{0641}-\x{064a}a-zA-Z]+)+$/ui", 'سسسسسس'));






?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method='post' >

Email : <input type="text" name="email">
<br />
user name :<input type="text" name="username">
<br />
first name :<input type="text" name="first_name">
<br />
ip :<input type="text" name="ip">

<input type="submit" name="sub">	
</form>




