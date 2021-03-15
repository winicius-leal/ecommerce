<?php

use \Principal\Model\User;
use \Principal\Model\Cart;


function formatarPreco(float $vlprice){
		return number_format($vlprice, 2, ",", ".");//essa funcao é chamada no template 
}

function formatDate($date)
{

	return date('d/m/Y', strtotime($date));

}

function checkLogin($inadmin = true)
{

	return User::checkLogin($inadmin);

}

function getUserName()
{

	$user = User::getFromSession();

	return $user->getdesperson();

}

function getCartNrQtd()
{

	$cart = Cart::getFromSession();

	$totals = $cart->getProductsTotals();

	return $totals['nrqtd'];

}

function getCartVlSubTotal()
{

	$cart = Cart::getFromSession();

	$totals = $cart->getProductsTotals();

	return formatarPreco($totals['vlprice']);

}

?>