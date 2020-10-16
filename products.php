<?php
# template header
include 'inc/__header.php';

# we need DB Connection here
require 'inc/connection.php';?>

<h1 class="mt-2 mb-5 pb-1 text-center">Our Products</h1>

<?php
if (isset($_GET['id'])):

  $result = pg_query($conn, "select * from products where id='".$_GET['id']."'");
  $rs = pg_fetch_assoc($result);

  $shopping_cart = get_session('shopping_cart');
  if (isset($_GET['order'])) {
    $cart = get_session('shopping_cart');
    $new_cart = array('id' => $rs['id'], 'name' => $rs['name'], 'qty' => 1, 'price' => $rs['price']);
    if (count($cart) > 0) {
      $exists = false;
      for($i=0; $i < count($cart); $i++) {
        if ($cart[$i]['id'] == $rs['id']) {
          $cart[$i]['qty'] += 1;
          $exists = true;
          break;
        }
      }
      if (!$exists) {
        array_push($cart, $new_cart);
      }
    } else {
      array_push($cart, $new_cart);
    }
    set_session('shopping_cart', $cart);
    header('location: cart.php');
  }

  $products = pg_query($conn, "select * from products where id not in('".$rs['id']."') limit 1000");

  $similiar = array();
  $temp_array = array();

  while ($product = pg_fetch_assoc($products)) {
    $name = similar_text($rs['name'], $product['name'], $by_name);
    $price = similar_text($rs['price'], $product['price'], $by_price);
    $material = similar_text($rs['material'], $product['material'], $by_material);
    $dimension = similar_text($rs['dimension'], $product['dimension'], $by_dimension);
    $color = similar_text($rs['colors'], $product['colors'], $by_color);

    if ($by_name > 75) {
      array_push($temp_array, $product);
      if ($by_price > 80) {
        array_push($temp_array, $product);
        if ($by_material > 80) {
          array_push($temp_array, $product);
          if ($by_dimension > 80) {
            array_push($temp_array, $product);
            if ($by_color > 80) {
              array_push($temp_array, $product);
            }
          }
        }
      }
    }
  }
  $similiar = array_unique($temp_array, SORT_REGULAR);
?>

  <div class="row">
    <div class="col-lg-9">
      <div class="card mb-5">
        <div class="card-body">
          <div class="row">
            <div class="col-md-5">
              <p class="text-center">
                <a href="/assets/img/<?=basename($rs['image'])?>" data-fancybox="gallery" data-caption="<?=$rs['name']?>" title="Klik untuk memperbesar"><img src="/assets/img/<?=basename($rs['image'])?>" class="img-fluid zoom" alt="..."></a>
              </p>
            </div>
            <div class="col-md-7 pl-lg-3">
              <h4><?=$rs['name']?></h4>
              <h4 class="text-primary"> IDR <?=number_format($rs['price'])?></h4>
              <ul>
                <li>Dimension: <?=$rs['dimension']?></li>
                <li>Colors: <?=$rs['colors']?></li>
                <li>Material: <?=$rs['material']?></li>
                <li>Stock: <span class="text-primary font-weight-bold"><?=$rs['stock']?></span> item</li>
              </ul>
              <?php if($rs['stock'] > 0):?>
                <a href="products.php?id=<?=$rs['id']?>&order=1" class="btn btn-success btn-block">Order Now</a>
              <?php else:?>
                <div class="alert alert-info" role="alert">
                  Sorry for the inconvenience.. This product is <strong>Out of Stock</strong>, please look at our similiar products below.
                </div>
                <a href="javascript:void(0)" class="btn btn-secondary btn-block disabled" tabindex="-1" role="button" aria-disabled="true">Out of stock</a>
              <?php endif;?>
            </div>
          </div>
        </div>
      </div>
      <h4 class="border-bottom my-3 pb-1">Similiar Products</h4>
      <div class="row">
        <?php if (count($similiar) > 0):
          foreach($similiar as $sim):?>
            <div class="col-sm-4 mb-3">
              <div class="card h-100 card-product">
                <img src="/assets/img/<?=basename($sim['image'])?>" class="card-img-top img-fluid h-100" alt="...">
                <div class="card-body">
                  <p class="card-title"><a href="products.php?id=<?=$sim['id']?>" class="text-decoration-none"><?=$sim['name']?></a></p>
                  <p class="card-text">IDR <?=number_format($sim['price'])?></p>
                </div>
              </div>
            </div>
          <?php endforeach;
        else:?>
          <div class="col-md-12">
            <p class="lead text-center text-muted">No similiar products found.</p>
          </div>
        <?php endif;?>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="card">
        <div class="card-header">Cart</div> 
        <div class="card-body">
          <ul>
            <?php
            if (count($shopping_cart) > 0):
              foreach($shopping_cart as $cart):?>
                <li><?=$cart['name']?> @<?=$cart['qty']?></li>
              <?php
              endforeach;
            else:?>
              <li>Cart is empty.</li>
            <?php
            endif;?>
          </ul>
        </div>
      </div>
    </div>
  </div>

<?php
else:
  $result = pg_query($conn, "select * from products");
  if (!$result) {
    echo "data not found";
    exit;
  }
?>

  <div class="row">
    <?php while ($rs = pg_fetch_object($result)):
      ?>
      <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
        <div class="card h-100 card-product">
          <img src="assets/img/<?=basename($rs->image)?>" class="card-img-top img-fluid h-100" alt="...">
          <div class="card-body text-center">
            <h6 class="card-title"><?=$rs->name?></h6>
            <?php if ($rs->stock > 0):?>
              <p class="card-text text-primary font-weight-bold">
                IDR <?=number_format($rs->price)?><br>
                <small><span class="badge bg-success">Available <?=$rs->stock?></span></small>
              </p>
            <?php else:?>
              <p class="card-text text-muted font-weight-bold">
                IDR <?=number_format($rs->price)?><br>
                <small><span class="badge bg-danger">SOLD OUT</span></small>
              </p>
              <del>IDR <?=number_format($rs->price)?></del><br>
            <?php endif;?>
            <p class="card-text text-muted small"><small><code>colors</code> : <?=$rs->colors?> | <code>dimension</code> : <?=$rs->dimension?></small></p>
            <a href="products.php?id=<?=$rs->id?>" class="btn btn-sm btn-primary">View Detail</a>
            <a href="products.php?id=<?=$rs->id?>&order=1" class="btn btn-sm btn-success">Order Now</a>
          </div>
        </div>
      </div>
    <?php endwhile;?>
  </div>

<?php
endif;

# template footer
include 'inc/__footer.php';