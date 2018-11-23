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
      
 }