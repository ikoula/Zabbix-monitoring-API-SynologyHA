<?php

// Synology Master IP / VIP HA admin 
$ip = $argv[1];

//DSM Server URL
$server = "https://$ip:5001";

//Authorized user login
$login = "admin";

//Authorized user password
$pass = $argv[2];

// Create context
$arrContextOptions=array(
                "ssl"=>array(
                        "verify_peer"=>false,
                        "verify_peer_name"=>false,
                        ),
                );

//SYNO.API.Auth
$vAuth = 2;
$vApi = 1;

//Get SYNO.API.Auth Path 
$json = file_get_contents($server.'/webapi/query.cgi?api=SYNO.API.Info&method=Query&version=1&query=SYNO.API.Auth',false, stream_context_create($arrContextOptions));
$obj = json_decode($json);
$path = $obj->data->{'SYNO.API.Auth'}->path;

//Login and creating SID
$json = file_get_contents($server.'/webapi/'.$path.'?api=SYNO.API.Auth&method=Login&version='.$vAuth.'&account='.$login.'&passwd='.$pass.'&session=SurveillanceStation&format=sid',false, stream_context_create($arrContextOptions));
$obj = json_decode($json);
	
// Verify object
if(!$obj->success)
{
        echo "error_login\n";
        exit;
}
else
{

	//authentification successful
	$sid = $obj->data->sid;

	//Get SYNO Core SHA Panel Overview
	$json = file_get_contents($server.'/webapi/query.cgi?api=SYNO.API.Info&method=query&version=1&query=SYNO.Core.SHA.Panel.Overview',false, stream_context_create($arrContextOptions));
	$obj = json_decode($json);

        // Verify object
        if(!$obj->success)
        {
                echo "error_info_panel\n";
                exit;
        }
        else
        {
		//Get SYNO Core SHA Panel Overview
		$path = $obj->data->{'SYNO.Core.SHA.Panel.Overview'}->path;
		$json = file_get_contents($server.'/webapi/'.$path.'?api=SYNO.Core.SHA.Panel.Overview&version='.$vApi.'&method=load&_sid='.$sid,false, stream_context_create($arrContextOptions));
		$obj = json_decode($json);
	
                // Verify object
                if(!$obj->success)
                {
                        // On a détécté une erreur, on relance le script
                        $scriptName = "/usr/bin/php ".$_SERVER["SCRIPT_NAME"]." ".$ip." ".$pass;
                        exec($scriptName, $output);
                        echo $output[0]."\n";
                        exit;
                }
                else
                {
                        // Verify status
			//$Rnode = $obj->data->rnode;
			//if(!empty($Rnode_status = $Rnode->status;))
			if(!empty($obj->data->rnode->status))
			//	echo "". $Rnode_status ."\n";
				echo "". $obj->data->rnode->status ."\n";
			else
				echo "unknown_status\n";
			exit;
		}
	}	
}
//Get SYNO API Auth Path 
$json = file_get_contents($server.'/webapi/query.cgi?api=SYNO.API.Info&method=Query&version=1&query=SYNO.API.Auth',false, stream_context_create($arrContextOptions));
$obj = json_decode($json);
$path = $obj->data->{'SYNO.API.Auth'}->path;

//Logout and destroying SID
$json = file_get_contents($server.'/webapi/'.$path.'?api=SYNO.API.Auth&method=Logout&version='.$vAuth.'&session=SurveillanceStation&_sid='.$sid,false, stream_context_create($arrContextOptions));
$obj = json_decode($json);

// Close script
exit;

?>
