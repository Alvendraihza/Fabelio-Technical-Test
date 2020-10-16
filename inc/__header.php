<?php
session_start();
if (!isset($_SESSION['alexey'])) {
  $_SESSION['alexey'] = array();
}

if (!isset($_SESSION['alexey']['shopping_cart'])) {
  set_session('shopping_cart', array());
}

if (!isset($_SESSION['alexey']['notif'])) {
  set_session('notif','');
}


function set_session($key, $val) {
  $_SESSION['alexey'][$key] = $val;
}

function get_session($key) {
  if (isset($_SESSION['alexey'][$key])) {
    return $_SESSION['alexey'][$key];
  } else {
    die("Session key $key doesn't exists!");
  }
}
?>

<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Alvendra</title>
  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/jquery.fancybox.min.css">
  <link rel="stylesheet" href="/assets/css/myapp.css">
  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }
  </style>
</head>
<body class="d-flex flex-column h-100">

  <!-- Navigation Menu -->
  <nav class="navbar navbar-expand-md navbar-light bg-light mb-4 border-bottom shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="index.php">Alvendra</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto mb-2 mb-md-0">
          <li class="nav-item">
            <a class="nav-link home" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="products.php">Products</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="about.php">About</a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto mb-2 mb-md-0">
          <li class="nav-item">
            <a class="nav-link home" href="cart.php">Cart <span class="badge bg-primary"><?=count(get_session('shopping_cart'))?></span></a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Contents -->
  <main class="flex-shrink-0">
    <div class="container">