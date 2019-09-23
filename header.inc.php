<?php
/**
 * Created by PhpStorm.
 * User: rcwethey
 * Date: 9/18/2019
 * Time: 9:27 PM
 */

session_start();
$currentfile = basename($_SERVER['PHP_SELF']); //get current filename
$rightnow = time(); //set current time

//turn on error reporting for debugging - Page 699
error_reporting(E_ALL);
ini_set('display_errors','1'); //change this after testing is complete

//set the time zone
ini_set( 'date.timezone', 'America/New_York');
date_default_timezone_set('America/New_York');

//required files
require_once "connect.inc.php";
require_once "functions.inc.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>See the Vision</title>
    <link href="http://fonts.googleapis.com/css?family=PT+Sans+Narrow" rel="stylesheet" type="text/css" />
    <link href="http://fonts.googleapis.com/css?family=Lobster" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="style.css"/>
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=5o7mj88vhvtv3r2c5v5qo4htc088gcb5l913qx5wlrtjn81y"></script>
    <script>tinymce.init({ selector:'textarea' });</script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<div id="bg">
    <div id="outer">
        <body>

            <header id="header">
                <div id="logo">
                    <h1 class="primary_heading">See the Vision</h1>
                </div>
                <nav id="nav">
                <?php require_once "nav.inc.php"; ?>
                </nav>
            </header>
        <main id="main">


