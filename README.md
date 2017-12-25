## Php data validation Class

***
its easy to used and you can validate so mach data types
## _Requirment_
* just  php 5.5+
## _usag_
 ### you can validate th data in three ways
 1- can validate data into array like $_POST,$_REQEST or any array you whant using the _validate_all_ function
 the _validate_all_ function has a two prameters
 Parameter one is an array contains yor conditions , for example:

`<?php
 $myconditions = array(
'username' => array('Alpha&Number' , 'yes' , 50 , 6 , 'your text error'),
'email' => array('Email','yes',100,10 , 'your text error')
);
   `
