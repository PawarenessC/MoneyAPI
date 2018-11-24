<?php

namespace MoneyAPI;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\utils\textFormat;
use pocketmine\utils\Config;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;


class Main extends pluginBase implements Listener{




public function onEnable() {
 $this->getServer()->getPluginManager()->registerEvents($this, $this);
 $this->Server = $this->getServer();
 $this->money = new Config($this->getDataFolder() ."money.yml", Config::YAML);//Configを作る
 $this->join = new Config($this->getDataFolder() ."join.yml", Config::YAML);//Configを作る
 $this->getLogger()->notice("MoneyAPIを読み込みました。");

}

public function onJoin(PlayerJoinEvent $event){
 $player = $event->getPlayer();
 $name = $player->getName();
 
     if(!$this->money->exists($name)){//Configに名前が存在しなかったら
      $this->money->set($name, "0");//Configにセットする、初期金額は0円
      $this->money->save();//セーブを忘れずに
      $player->sendMessage("{$name}さんの経済データを生成しました。");//これいる?笑
    }
     if(!$this->join->exists($name)){//上と同じく
      $this->join->set($name, "1");//サーバー参加回数をその名前で作る
      $this->join->save();//保存
      $player->sendMessage("初めてサーバーに入ってくれてありがとう！{$name}さん");
    
    }else{//もしも存在していたら
   
    $this->addJoin($name);//API使えばこれだけでOK!
    $join = $this->getJoin($name);//参加回数を取得
    $player->sendMessage("{$name}さんの累計サーバー参加回数は{$join}回です");
  }  
}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) :bool{
if(count($args) === 0);
 switch ($command->getName()){
  
  case "money";//コマンド名
  	$name = $sender->getName();//名前取得
  	$sender->sendMessage("§e{$name}さんの§aステータス");
	$sender->sendMessage("§b所持金§f:§6".$this->mymoney($name)."円持ってるぜwwww");
     return true;
     break;
 
  case "join";//コマンド名
  	 $name = $sender->getName();//名前取得
     $join = $this->getJoin($name);//参加回数を取得
  	 $sender->sendMessage("§e{$name}さんは今までに{$join}回サーバーに入っています");
      return true;
      break;
 }
}

   public function mymoney($name){//金額取得
     if($this->money->exists($name)){//Configにその名前($name)があったら...
     return $this->money->get($name);//金額を返す
     }else{//もしもなかったら...
         $this->money->set($name,"0");//Configにセット
         $this->money->save();
          return 0;//0円だったよー
   }
  }
   public function addmoney($name,$money){//金額を増やす
    $this->money->set($name,$this->money->get($name) + $money);//取得して増やす
    $this->money->save();//セーブじゃ
     }
     
   public function setMoney($name,$money){//金額をセットする。
		$this->money->set($name, $money);//セット
		$this->money->save();//セーブj(ry
	}
   
   public function cutMoney($name,$money){//金額を減らす
		$this->money->set($name, $this->money->get($name) - $money);//$moneyに入った数値を引いてセット
		$this->money->save();//セーb(ry
	}
	
   public function addJoin($name){//Joinの数を増やす
		$this->join->set($name,$this->join->get($name) + 1);//Joinの参加数を1増やす
        $this->join->save();
	}
	
   public function getJoin($name){//Joinの数を取得する
		if($this->join->exists($name)){//Configにその名前($name)があったら...
        return $this->join->get($name);//回数を返す
         }else{//もしもなかったら...
            $this->join->set($name,"0");//Configにセット
            $this->join->save();
            return 0;//0回だったよー
	}
 }
}