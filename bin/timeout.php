<?php
function timer(){
	session_start();    

	$time=1000;

	$_SESSION[timeout]=time()+$time;

}

function cek_login(){

	$timeout=$_SESSION[timeout];

	if(time()<$timeout){

		timer();

		return true;

	}else{

		unset($_SESSION[timeout]);

		return false;
		
	}
}
?>
