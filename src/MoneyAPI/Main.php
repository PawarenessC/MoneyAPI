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


class Main extends pluginBase implements Listener {

	/** @var Config */
	private $money;

	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
 		$this->money = new Config($this->getDataFolder() ."money.yml", Config::YAML);//Configを作る
 		$this->getLogger()->info("MoneyAPIを読み込みました。");
	}

	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$name = $player->getName();
		if(!$this->money->exists($name, true)){//Configに小文字大文字区別せず名前が存在しなかったら
      		$this->money->set($name, 0);// Configにセットする、初期金額は0
			$this->money->save();// 反映を忘れずに
			$player->sendMessage("{$name} さんの経済データを生成しました。");//これいる?笑
    	}
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
		if ($sender instanceof Player) {
			$name = $sender->getName();//名前取得
			$sender->sendMessage(
				"§e{$name}さんの§aステータス\n".
				"§b所持金§f:§6".$this->mymoney($name)."円持ってるぜwwww"
			);
		}else {
			$this->getLogger()->warning("エラー: ゲーム内から実行して下さい");
		}
		return true;
	}

	/**
	 * 金額の取得
	 * @param string $name
	 * @return int
	 */
	public function mymoney(string $name): int {
		if($this->money->exists($name, true)){// Configに大文字小文字区別せずその名前($name)があったら...
			return $this->money->get($name);// 金額を返す
		}else{//もしもなかったら...
			$this->money->set($name,"0");//Configにセット
			$this->money->save();
			return 0;// 0とする
		}
	}

	/**
	 * 金額の追加
	 * @param string $name
	 * @param int $add
	 */
	public function addmoney(string $name, int $add) {
		$money = $this->mymoney($name);// 現在の金額の取得
		$this->money->set($name, $money + $add);// 増やす
		$this->money->save();// ファイルに反映
	}
}