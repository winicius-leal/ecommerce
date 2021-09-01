<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h2><?php echo htmlspecialchars( $product["desproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-content-right">
                    <div class="product-breadcroumb">
                        <a href="/">Home</a>
                        <a href=""><?php echo htmlspecialchars( $product["desproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?></a>
                    </div>

                    <div class="row">

                        <div class="col-sm-6">
                            <div class="product-images">
                                <div class="product-main-img">
                                    <img src="<?php echo htmlspecialchars( $product["photoprincipal"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <h2 class="title-opcoes"><?php echo htmlspecialchars( $product["desproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?></h2>
                            <div class="product-inner-price title-opcoes">
                                <ins>R$<?php echo formatarPreco($product["vlprice"]); ?></ins>
                            </div>

                            <form action="/cart/<?php echo htmlspecialchars( $product["idproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/add" class="cart">
                                <div class="quantity">
                                    <input type="number" size="4" class="input-text qty text" title="Qty" value="1" name="qtd" min="1" step="1">
                                    <label>Quantidade</label>
                                </div>
                                <div>
                                    <br> <br>
                                    <input type="radio" class="btn-check" name="morango" id="option1" autocomplete="off">
                                    <label class="btn btn-secondary" for="option1">morango</label>
                                    <br>
                                    <input type="radio" class="btn-check" name="baunilha" id="option2" autocomplete="off">
                                    <label class="btn btn-secondary" for="option2">baunilha</label>
                                    <br>
                                    <input type="radio" class="btn-check" name="chocolate" id="option3" autocomplete="off">
                                    <label class="btn btn-secondary" for="option3">chocolate</label>
                                    <br>
                                    <input type="radio" class="btn-check" name="cookies" id="option4" autocomplete="off">
                                    <label class="btn btn-secondary" for="option4">cookies</label>
                                    <br><br>
                                </div>

                                <button class="add_to_cart_button" type="submit">COMPRAR</button>
                            </form>

                            <div class="product-inner-category ">
                                <p>Categorias:
                                    <?php $counter1=-1;  if( isset($categories) && ( is_array($categories) || $categories instanceof Traversable ) && sizeof($categories) ) foreach( $categories as $key1 => $value1 ){ $counter1++; ?>

                                    <a href="/categories/<?php echo htmlspecialchars( $value1["idcategory"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><?php echo htmlspecialchars( $value1["descategory"], ENT_COMPAT, 'UTF-8', FALSE ); ?></a>.
                                    <?php } ?>

                            </div>

                        </div>
                    </div>




                    <div class="product-inner">

                        <div role="tabpanel">

                            <ul class="product-tab" role="tablist">
                                <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Descrição</a></li>
                                <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Avaliações</a></li>
                            </ul>

                            <div class="tab-content" align="center">

                                <!--painel das descrições-->
                                <div role="tabpanel" class="tab-pane fade in active" id="home">

                                    <h2 class="title-descricao"> SOBRE O PRODUTO</h2>
                                    <p class="text-descricao"><?php echo htmlspecialchars( $product["descriproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?></p>

                                    <h2 class="title-descricao">RECOMENDAÇÕES</h2>
                                    <p class="text-descricao" > <?php echo htmlspecialchars( $product["recommendationproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?></p>

                                    <h2 class="title-descricao">PORQUE USAR</h2>
                                    <p class="text-descricao" > <?php echo htmlspecialchars( $product["useproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?></p>

                                    <h2 class="title-descricao" >SUGESTÃO DE USO</h2>
                                    <p class="text-descricao" ><?php echo htmlspecialchars( $product["suggestionproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?></p>

                                    <h2 class="title-descricao" >TABELA NUTRICIONAL</h2>
                                    <div class="product-main-img">
                                        <img src="<?php echo htmlspecialchars( $product["phototabela"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                                    </div>

                                </div>
                                <!--FIM painel das descrições-->

                                <!--painel das avaliações-->
                                <div role="tabpanel" class="tab-pane fade" id="profile">
                                    <h2>Reviews</h2>
                                    <div class="submit-review">
                                        <p><label for="name">Name</label> <input name="name" type="text"></p>
                                        <p><label for="email">Email</label> <input name="email" type="email"></p>
                                        <div class="rating-chooser">
                                            <p>Your rating</p>

                                            <div class="rating-wrap-post">
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                            </div>
                                        </div>
                                        <p><label for="review">Your review</label> <textarea name="review" id="" cols="30" rows="10"></textarea></p>
                                        <p><input type="submit" value="Submit"></p>
                                    </div>
                                </div>
                                <!--FIM painel das avaliações-->
                            </div>
                        </div>

                    </div>
                    
                </div>                    
            </div>
        </div>
    </div>
</div>