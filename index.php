<?php
include 'inc/__header.php';

echo '<div class="bg-light p-5 rounded text-center">';

switch (get_session('notif')):
  case 'checkout':?>
    <h1>Checkout Success</h1>
    <p class="lead">Thank you for shopping in our Store, your order will be process within 1 day from now.</p>
    <?php break;
  case 'success':
  case 'error':
  default:?>
    <h1>Alvendra Store</h1>
    <p class="lead">Welcome in our store..</p>
    <a class="btn btn-lg btn-primary" href="/products.php" role="button">See our products &raquo;</a>
    <?php break;
endswitch;

echo '</div>';
set_session('notif','');
include 'inc/__footer.php';