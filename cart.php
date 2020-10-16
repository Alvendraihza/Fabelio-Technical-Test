<?php
include 'inc/__header.php';

$shopping_cart = get_session('shopping_cart');

if (isset($_GET['id']) && $_GET['id'] != '') {
  if (isset($_GET['act']) && $_GET['act'] == 'remove') {
    for ($c=0; $c < count($shopping_cart); $c++) {
      if ($shopping_cart[$c]['id'] == $_GET['id']) {
        unset($shopping_cart[$c]);
        break;
      }
    }
    set_session('shopping_cart', $shopping_cart);
    header('location: cart.php');
  }
}

if (isset($_GET['act']) && $_GET['act'] == 'checkout') {
  # we need DB Connection here
  require 'inc/connection.php';
  $sql = "update products set stock=stock-%s where id='%s'";
  foreach ($shopping_cart as $cid) {
    $result = pg_query($conn, sprintf($sql, $cid['qty'], $cid['id']));
    if ($result) {
      continue;
    } else {
      echo pg_last_error($conn);
      break;
    }
  }
  set_session('notif','checkout');
  set_session('shopping_cart', array());
  header('location: /');
}
?>

<h1 class="mt-2 mb-5 pb-1 text-center">Shopping Cart</h1>
<table class="table table-striped table-sm">
  <thead>
    <tr>
      <th>Act</th>
      <th>Product Name</th>
      <th class="text-right">Price</th>
      <th class="text-right">Qty</th>
      <th class="text-right">Subtotal</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $sum_qty = 0;
    $sum_subtotal = 0;
    if (count($shopping_cart) > 0):
      foreach ($shopping_cart as $cart):
        $sum_qty += $cart['qty'];
        $sum_subtotal += $cart['price'] * $cart['qty']; ?>
        <tr>
          <td>
            <a href="cart.php?id=<?=$cart['id']?>&act=remove" 
            title="Remove from cart" 
            onclick="return confirm('Are you sure want to remove this item from Cart?')">DEL</a>
          </td>
          <td><a href="products.php?id=<?=$cart['id']?>" class="text-decoration-none"><?=$cart['name']?></a></td>
          <td class="text-right"><?=number_format($cart['price'])?></td>
          <td class="text-right"><?=$cart['qty']?></td>
          <td class="text-right"><?=number_format($cart['qty'] * $cart['price'])?></td>
        </tr>
      <?php
      endforeach;
    else: ?>
      <tr><td colspan="5" class="text-center text-muted">Your cart is empty..</td></tr>
    <?php endif; ?>
  </tbody>
  <tfoot>
    <tr>
      <th colspan="3" class="text-right">Total</th>
      <th class="text-right"><?=number_format($sum_qty)?></th>
      <th class="text-right"><?=number_format($sum_subtotal)?></th>
    </tr>
  </tfoot>
</table>
<br>
<div class="row">
  <div class="col-md-8">
    <div class="alert alert-info" role="alert">
      Just for simulate order, when <strong>Checkout Order</strong> button pressed.
      <ul>
        <li>Cart will be removed from session.</li>
        <li>Products Stock will be reduced from database.</li>
        <li>You will be redirecting into homepage.</li>
      </ul>
    </div>
  </div>
  <div class="col-md-4">
    <?php if (count($shopping_cart) > 0):?>
      <p class="text-right">
        <a href="cart.php?act=checkout" 
          class="btn btn-primary"
          onclick="return confirm('Are you sure want to checkout now?')">Checkout Order</a>
      </p>
    <?php endif;?>
  </div>
</div>
<?php include 'inc/__footer.php'; ?>