<?php
/**
*@author 	semo94
*@link		http://www.semo94.com
*@link  	https://github.com/semo94developer/phpValidation
*@since		Version 1.0.0
*/
class Validation
{
	// --------------------------------------------------------------------------------------------
	/**
	*
	*/
	private $_rulse_delimiter = '&';

	// --------------------------------------------------------------------------------------------
	/**
	*
	*/
	private $_or_delimiter = '|';

	// --------------------------------------------------------------------------------------------
	/**
	*
	*/
	private $_rulse_error_message = 'rulse valid';
	
	// --------------------------------------------------------------------------------------------
	/**
	*
	*/
	private $_default_required = 'yes';
	
	// --------------------------------------------------------------------------------------------
	/**
	*
	*/
	private $_default_Max      = 1000;
	
	// --------------------------------------------------------------------------------------------
	/**
	*
	*/
	private $_default_Min      = 0;
	
    // --------------------------------------------------------------------------------------------
	/**
	*
	*/
    private $_default_error    = 'data valid!';

	// --------------------------------------------------------------------------------------------
	/**
	*
	*/
	private $_patterns 			= array(
								'Any' 				=>'(.+)',
								// 
								'Arabic_alpha'		=> '\\x{0621}-\\x{063a}\\x{0641}-\\x{064a}',
								'Arabic_number' 	=> '\\x{0660}-\\x{0669}',
								// 
								'Alpha'				=> 'a-zA-Z',
								'Spaces'            => '\\s',
								// Numbers
								'Number' 			=> '\\d',// 
								// password 
										);

	// --------------------------------------------------------------------------------------------
	/**
	*
	*/	
	private $_rulse 	 		= array(
								'required', 
								'Any',
								'Any/',
								'Arabic_alpha',
								'Arabic_number',
								'Alpha',
								'Spaces',
								'Number', 
								'Int', 
								'N+', 
								'N-' 
										);

	// --------------------------------------------------------------------------------------------
	/**
	*
	*/
	private $_one_rulse  		= array(
									'Float',
									'Email',
									'Date', 
									'Time',
									'Url',
									'Domin',
									'Ip',
									'Phone',
									'Color'
										);

	// --------------------------------------------------------------------------------------------
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
      $errors['success']  = true;
      foreach($conditions as $name => $val)
      {
          if(is_array($data[$name]))
          {
          	$status_array = array();
          	$errors_array = array();

          	foreach ($val as $key => $value)
          	{
          		$validate = $this->validate_helper($conditions[$name] , $val);
          		$errors['success'] = ($errors['success'] * $validate['error_status']);
          		$status_array[$key] = $validate['error_status'];
          		$errors_array[$key] = $validate['errors'];
          	}

          	$errors[$name] = $status_array;
          	$errors[$name .'_error'] = $errors_array;
              
          }
          else
          {

          	$validate = $this->validate_helper($val , $data[$name]);
          	$errors['success'] = ($errors['success'] * $validate['error_status']);
          	$errors[$name] = $validate['error_status'];
          	$errors[$name.'_error'] = $validate['errors'];

          }
      }

      return $errors;
	}
	// --------------------------------------------------------------------------------------------
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

	// --------------------------------------------------------------------------------------------
	/**
	*@param string
	*@param string
	*@return bool
	*/
	// --------------------------------------------------------------------------------------------
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
	} 

	// --------------------------------------------------------------------------------------------
	/**
	*@param int
	*@param int
	*@param any
	*@return bool
	*/
	public function in_range($max , $min ,$val)
	{
		return ($this->max_length($max , $val) * $this->min_length($min , $val));
	}
	// --------------------------------------------------------------------------------------------
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

	// --------------------------------------------------------------------------------------------
	/**
	*@param string like yyyy-mm-dd example 2010-05-25
	*@return bool
	*/
	public function isDate($date)
	{
		if(!is_string($date))
			return false;

		$date = explode('-', $date);
		if(count($date) != 3)
			return false;

		return checkdate($date[1], $date[2], $date[0]);
	}

	// --------------------------------------------------------------------------------------------
	/**
	*@param string like hh:ii or h:i example 20:01 or 20:1
	*@return bool
	*/
	public function isTime($time)
	{
		$time = explode(':', $time);
		if(count($time) != 2)
			return false;
		if($time[0] > 23 ||$time[0] < 0 || $time[1] > 59 ||$time[1] < 0  )
			return false;
		return true;
	}

	// --------------------------------------------------------------------------------------------
	/**
	*@param string like #ffFF00 or #FF0 
	*@return bool
	*/
	public function isColor($color)
	{
		return preg_match('/^(#([0-9a-fA-F]{6}|[0-9a-fA-F]{3}))$/', $color);
	}
	// --------------------------------------------------------------------------------------------
	/**
	* check ipv4
	*@param string
	*@return bool
	*/
	public function isIp($ip)
	{
	   $ip = explode('.',$ip);
	   if(count($ip) != 4)
	   {
	       return false;
	   }
	   foreach($ip as $val)
	   {
	       if( $val < 0 ||  $val > 255 || !is_numeric($val)) 
	       {
	           return false;
	       }
	   }
	   return true;
	}

	// --------------------------------------------------------------------------------------------
	/** 
	*@param string
	*@return bool
	*/
	public function isFloat($number)
	{
		return is_float($number);
	}
	
	// --------------------------------------------------------------------------------------------
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

	// --------------------------------------------------------------------------------------------
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

	// --------------------------------------------------------------------------------------------
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

	// --------------------------------------------------------------------------------------------
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
       return $new_pattern;
	}
	/**
	*
	*
	*/
	// --------------------------------------------------------------------------------------------
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

	// --------------------------------------------------------------------------------------------
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

	// --------------------------------------------------------------------------------------------
	/**
	*@param array  
	*/
	private function check_required($required)
	{
		if(($required !== 'yes' && $required !== 'no') )
		{
			throw new Exception($this->_rulse_error_message);
		}	
	} 
}
?>
