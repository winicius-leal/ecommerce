<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="footer-top-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="footer-about-us">
                        <h2>LEAL ECOMMERCE</h2>
                        <p>DESCRICAO DA EMPRESA</p>
                        <div class="footer-social">
                            <a href="https://www.facebook.com/hcodebr" target="_blank"><i class="fa fa-facebook"></i></a>
                            <a href="https://twitter.com/hcodebr" target="_blank"><i class="fa fa-twitter"></i></a>
                            <a href="https://www.youtube.com/channel/UCjWENuSH2gX55-y7QSZiWxA" target="_blank"><i class="fa fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <div class="footer-menu">
                        <h2 class="footer-wid-title">Navegação </h2>
                        <ul>
                            <li><a href="#">Minha Conta</a></li>
                            <li><a href="#">Meus Pedidos</a></li>
                            <li><a href="#">Lista de Desejos</a></li>
                        </ul>                        
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <div class="footer-menu">
                        <h2 class="footer-wid-title">Categorias</h2>
                        <ul>
                            <?php require $this->checkTemplate("categories-menu");?> <!--faz um include de uma pagina html separa-->
                        </ul>                        
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <div class="footer-newsletter">
                        <h2 class="footer-wid-title">Newsletter</h2>
                        <p>Faça parte da nossa lista de transmissão e receba nossas promoções.</p>
                        <div class="newsletter-form">
                            <form action="#">
                                <input type="email" placeholder="Seu email">
                                <input type="submit" value="Inscrever-se">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End footer top area -->
    
    <div class="footer-bottom-area">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="copyright">
                        <p>&copy; Leal <a href="" target="_blank"></a></p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="footer-card-icon">
                        <i class="fa fa-cc-discover"></i>
                        <i class="fa fa-cc-mastercard"></i>
                        <i class="fa fa-cc-paypal"></i>
                        <i class="fa fa-cc-visa"></i>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End footer bottom area -->
   
    <!-- Latest jQuery form server -->
    <script src="https://code.jquery.com/jquery.min.js"></script>
    
    <!-- Bootstrap JS form CDN -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    
    <!-- jQuery sticky menu -->
    <script src="/resoucers/site/js/owl.carousel.min.js"></script>
    <script src="/resoucers/site/js/jquery.sticky.js"></script>
    
    <!-- jQuery easing -->
    <script src="/resoucers/site/js/jquery.easing.1.3.min.js"></script>
    <!-- Handlerbars -->
    <script type="text/javascript" src="/resoucers/site/js/handlebars-v4.0.10.js"></script>
    
    <!-- Main Script -->
    <script src="/resoucers/site/js/main.js"></script>
    
    <!-- Slider -->
    <script type="text/javascript" src="/resoucers/site/js/bxslider.min.js"></script>
	<script type="text/javascript" src="/resoucers/site/js/script.slider.js"></script>

    <script>

        $(function(){

            if (scripts instanceof Array) {

                $.each(scripts, function(index, fn){

                    if (typeof fn === 'function') fn();

                });
            }
        });
    </script>
  </body>
</html>