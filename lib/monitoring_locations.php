<?php
// 2010-02-22 - testing simplexml
// 2010-05-26 - begin abstraction
// 2013-08-19 - seperated out wind locations

/*
 - ellwood: http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=MSBELL
 - bel air: http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=KCASANTA122
 - mesa   : http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=MAS714
 - cumbre : http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=MAS303
 - airport: http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=MKSBA
 */

// setup variables

$debug   = 1;
$w_array = array();

// santa barbara
$w_array['ellwood']  = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=MSBELL';
$w_array['belair']   = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=KCASANTA122';
$w_array['mesa']     = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=MAS714';
$w_array['lacumbre'] = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=MAS303';
$w_array['airport']  = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=MKSBA';
$w_array['ecb']      = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=M46053';
$w_array['gap']      = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=KCASANTA198';
$w_array['lospri']   = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=MLPOC1';
$w_array['par']      = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=MCQ107';
$w_array['par2']     = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=MOXPAR';
$w_array['bacara']   = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=MSBVEN';
$w_array['246']      = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=KCASANTA1';
$w_array['sy']       = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=MKIZA';
// el capitan
$w_array['elcap']    = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=MOXELC';
// santa ynez airport
// jalama
$w_array['vanden']   = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=KCAVANDE3';


// sedona
$w_array['manana']    = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=KAZSEDON3';
$w_array['sedona']    = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=KAZSEDON1';
$w_array['oakcreek']  = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=KAZSEDON5';
$w_array['oakcreek2'] = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=MQOCA3';
$w_array['sliderock'] = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=MTS558';

// long beach
$w_array['belmont']   = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=KCALONGB7';

// inland
$w_array['cabazon']   = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=MTS046';
$w_array['banning']   = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=KCABANNI1';
// kanan
$w_array['kanan']     = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=KCAWESTL2';
$w_array['acton']     = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=KCAACTON3';
// ortega (lake elsinore)
// los banos
$w_array['losbanos']  = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=KCALOSBA2';
$w_array['losbanos2'] = 'http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=MSLRC1';

?>
