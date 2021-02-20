<?php

function formatarPreco(float $vlprice){
		return number_format($vlprice, 2, ",", ".");//essa funcao é chamada no template 
}

?>