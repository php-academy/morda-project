<?php>
function print_city ($s,$d)
{
$z = NULL;
foreach ($s as $key => $value) {
 	if ($d==$value['code'])
 		$z=$value['name']
	} 
return echo "$z"
}




	
