<?php if(!class_exists('Rain\Tpl')){exit;}?><br>
<div class="container">
  <div class="row" style="margin: 10px">
    <?php $counter1=-1;  if( isset($products) && ( is_array($products) || $products instanceof Traversable ) && sizeof($products) ) foreach( $products as $key1 => $value1 ){ $counter1++; ?>
    <div class="col-6 col-sm-3 single-product" style="margin-bottom: 20px">
       <img src="<?php echo htmlspecialchars( $value1["namephoto"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" width="100%" >
        <h2><a href="/product/<?php echo htmlspecialchars( $value1["desurl"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><?php echo htmlspecialchars( $value1["desproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?></a></h2>
        <div class="product-carousel-price">
            <ins>R$<?php echo formatarPreco($value1["vlprice"]); ?></ins>
        </div>
        <div>
            <a href="/product/<?php echo htmlspecialchars( $value1["desurl"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" class="btn btn-success ">Ver detalhes</a>
            <a href="/cart/<?php echo htmlspecialchars( $value1["idproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/add" class="btn btn-success">Comprar</a>
        </div>
    </div>
    <?php } ?>
  </div>
</div>
<br>
