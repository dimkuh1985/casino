<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Blackjack;
use Auth; 						// фасад для пользователя
use App\User;						
use DB;


class BlackjackController extends Controller
{
    public function index()
    {

    	$bj = new Blackjack();
    	$cards = $bj->cards; // получение всеё колоды
    	$user = Auth::user()->name; // авторизированый пользователь через фасад
    	$cash = Auth::user()->cash; //денюжки
    	$id = Auth::user()->id;


    	//добавление по 2 карты игроку из колоды
    	$tempindex = rand(1,count($cards)-1); 	//получение индекса первой карты из колоды во временную переменную c заменой 
    	$usercards = array($cards[$tempindex]); // запись первой карты из колоды
    	array_splice($cards, $tempindex, 1);    //удаление из колоды использованые карты
		$tempindex = rand(1,count($cards)-1);   //получение индекса второй карты из колоды во временную переменную c заменой    	
    	array_push($usercards, $cards[$tempindex]); //...запись второй карты
    	array_splice($cards, $tempindex, 1);    //...удаление из колоды
    	
    	//добавление по 1 картe дилеру из колоды
    	$tempindex = rand(1,count($cards)-1); 	
    	$dealercards = array($cards[$tempindex], $cards[0]);     // 1 карта + рубашка	
    	array_splice($cards, $tempindex, 1);    //удалить добавленную карту
    	array_splice($cards, 0, 1);             //удалить рубашку
    	$tempindex = null;                      //обнуление временной переменной 
    	
    	
    	return view('blackjack',['cards' => $cards, 'user' => $user, 'cash' => $cash, 'id' => $id, 'usercards' => $usercards, 'dealercards' => $dealercards]);
    }

    public function updatecash(Request $request)
    {
    	$user = User::find(Auth::id()); //получить сразу іd игрока

    	$cash=$request->cash;
    	$user->cash=$cash;
    	$user->save();

    	return ['cash' => $user->cash]; //просто прверить cash на сервере
    }


}
