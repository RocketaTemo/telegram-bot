<?php
require_once("lib/telegram_bot.php");
class TestBot extends TelegramBot{

	protected $token = ".......";
	/**			
	 * Предустановленные варианты команд
	 * команда => метод для обработки команды
	 */
	protected $commands = [
			"/start" => "cmd_start",
			"🔙Назад" => "cmd_start",
			"/help" => "cmd_help",
			"💰Продать" => "cmd_sell",
			"🛒Купить" => "cmd_buy",
			"💵Контакты" => "cmd_contacts",
			"✌️Отзывы" => "cmd_reviews",
			"Новое" => "cmd_new",
			"Б/у" => "cmd_old",
			"Добавить" => "cmd_sendAlbum",
			"/d" => "cmd_description"
		];

	/**
	 * Предустановленные клавиатуры
	 *
	 * Справка по клавитурам: https://core.telegram.org/bots/api#replykeyboardmarkup
	 * 
	 */
	public $keyboard1 = array(
	'keyboard' => array(
		array(
		array('text' => "💰Продать"),
		array('text' => "🛒Купить"),
	),			array(
			array('text' => "💵Контакты"),
			array('text' => "✌️Отзывы")
			)
	),'resize_keyboard' => true);	
	public $keyboard2 = array(
	"keyboard" => array(
		array(
		array("text" => "Новое"),
		array("text" => "Б/у"),
		),		array(
			array("text" => "🔙Назад"),
		)),'one_time_keyboard' => true,'resize_keyboard' => true);
	public $keyboard3 = array(
		'keyboard' => array(
			array(
			array("text" => "Добавить описание"),
			array("text" => "🔙Назад"),
			)),'one_time_keyboard' => true,'resize_keyboard' => true);

	public $cloth_list = [
		'inline' => [
			// Две кнопки в ряд
			[
				['text' => "👟 Кроссовки", 'callback_data'=> "act кроссовки"],
				['text' => "👖 Штаны", 'callback_data'=> "act штаны"]
				
			],
			[
				['text' => "👕 Футболка", 'callback_data'=> "act футболка"],
				
				['text' => "🕶 Аксессуары", 'callback_data'=> "act аксессуары"]
			],
			[
				['text' => "Худи", 'callback_data'=> "act худи"],
				['text' => "Лонгслив", 'callback_data'=> "act лонгслив"]
			],
			// Кнопка на всю ширину
		],

	];
	/**
	 * Обработка ввода команды "Купить"
	 */
	function cmd_buy(){
		$filename = $this->result["message"]["chat"]["id"];
		file_put_contents($filename.'.txt','');
		$this->api->sendMessage([
			'text' => "Вы можете ознакомиться с нашим ассортиментом в Instagram:  https://www.instagram.com/legitplace/
			\n
			Наши контакты
			@Yung_Lean2
			@buduvsegdal
			\n
			📭Telegram-канал
			https://t.me/legitplacemarket
			",
			]);
	}

	/**
	 * Обработка ввода команды "/start"
	 */
	function cmd_start(){
		$filename = $this->api->chatId;
		file_put_contents($filename.'.txt','');
		$this->api->sendMessage([
			'text' => "
			🔥🔥🔥*Legitplace*🔥🔥🔥
		Добро пожаловать в первый бот-маркетплейс в Украине.\nМы продаём только оригинальные брендовые вещи.\nДля покупки или продажи, воспользуйтесь функционалом бота.\nПриятного пользования.",
			'parse_mode' => "Markdown", 
			'reply_markup' => json_encode($this->keyboard1),
		]);
	}

	/**
	 * Обработка ввода команды "Продать"
	 */
	function cmd_sell(){
		$this->api->sendMessage([
			'text' => "Укажите состояние одежды",
			'reply_markup' => json_encode($this->keyboard2)
		]);
	}
	function cmd_new(){
		$filename = $this->result["message"]["chat"]["id"];
		file_put_contents($filename.'.txt' ,"От: @".$this->result["message"]["from"]["username"]."\nПредложение: новое ", FILE_USE_INCLUDE_PATH);
		$this->api->sendMessage([
			'text'=>"Вы выбрали: *новое*\nЧто вы хотите продать?",
			'parse_mode' => "Markdown", 
			'reply_markup' => json_encode([
				'inline_keyboard'=> $this->cloth_list['inline']
			] )
		]);
	}
	function cmd_old(){
		$filename = $this->result["message"]["chat"]["id"];
		file_put_contents($filename.'.txt' , "От: @".$this->result["message"]["from"]["username"]."\nПредложение: б/у ", FILE_USE_INCLUDE_PATH);
		$this->api->sendMessage([
			'text'=>"Вы выбрали: *б/у*\nЧто вы хотите продать?",
			'parse_mode' => "Markdown", 
			'reply_markup' => json_encode( [
				'inline_keyboard'=> $this->cloth_list['inline']
			] )
		]);
	}

	function cmd_contacts(){
		$this->api->sendMessage([
			'text' => "
			Если у Вас возникли какие-либо вопросы, Вы можете связаться с нами в Telegram, Instagram.
			
			
			📭@Yung_Lean2 
			📭@buduvsegdal

			📸https://www.instagram.com/legitplace
			"
		]);
		
	}
	function cmd_reviews(){
		$this->api->sendMessage([
			'text' => "✅Отзывы о нас ищите в Instagram highlights.\n

			https://www.instagram.com/stories/highlights/18087466135048198/
			https://www.instagram.com/stories/highlights/18050389591005276/
			https://www.instagram.com/stories/highlights/18014711626004061/
			https://www.instagram.com/stories/highlights/17888980393173366/
			",
		]);
	}
	function callback_act($query){
		$query = explode(" ", $query);
		$filename = $this->api->chatId;
		file_put_contents($filename.'.txt' ,$query[1], FILE_USE_INCLUDE_PATH|FILE_APPEND);
		$text = "Вы выбрали: *{$query[1]}* \n\n";
		$text .= "Бот ожидает фото товара с описанием.\n1️⃣Загрузите не менее *3* фото\n\n2️⃣Добавьте описание к фотографиям\n
		https://telegra.ph/Manual-po-zagruzke-fotografij-08-19
		";
		$this->callbackAnswer( $text);
		$this->api->answerCallbackQuery( $this->result['callback_query']["id"] );

			switch($query[1]){
				case 'кроссовки':
					$cloth='кроссовок';
						break;
				case 'футболка':
					$cloth='футболки';
						break;
				case 'худи':
					$cloth='худи';
						break;
				case 'штаны':
					$cloth='штанов';
						break;
				case 'аксессуары':
					$cloth='аксессуара';
						break;
				case 'лонгслив':
					$cloth='лонгслива';
						break;
			}
		$this->api->sendMessage([
			'text' => "Загрузите фотографии " . $cloth ." при помощи скрепки 📎, затем нажмите кнопку \"Добавить описание\"",
			'reply_markup' => json_encode($this->keyboard3)
		]);
		$this->api->sendPhoto( "https://ibb.co/fCvnW3P", "Кнопка находится тут." );
		exit;
	}
		// Ответ на кнопку "В начало" выводит начальную клавиатуру
		function callback_back(){
			$filename = $this->api->chatId;
			file_put_contents($filename.'.txt','');
			$this->api->sendMessage([
				'text' => "
				🔥🔥🔥*Legitplace*🔥🔥🔥
			Добро пожаловать в первый бот-маркетплейс в Украине.\nМы продаём только оригинальные брендовые вещи.\nДля покупки или продажи, воспользуйтесь функционалом бота.\nПриятного пользования.",
				'parse_mode' => "Markdown", 
				'reply_markup' => json_encode($this->keyboard1),
			]);
		}

	function cmd_sendAlbum(){
		$media_group_id = file_get_contents(($this->result["message"]["from"]["username"]).'.txt');
		$photoCount = count(file($media_group_id.'.txt'));
		if($photoCount < 3){
			$this->api->sendMessage([
				'text' => "Загрузите не менее *3* фото\n",
				'parse_mode' => "Markdown" 
			]);
		}
		elseif($photoCount >= 3 && trim(file_get_contents($this->api->chatId.'.txt'))){
			$this->api->sendMessage(['text' => "✅*Фотографии удачно загружены!*✅\n\nТеперь опишите товар, используя команду /d _ОПИСАНИЕ_.\n❗_Обязательно начинайте текст с команды_ /d\n❗_У вас должен быть никнейм в телеграме, что бы бот мог видеть кто присылает вещь на продажу_ \n\nНиже наведен шаблон с рядом правил, придерживайтесь им!\n❗️*При отклонении от шаблона ваша вещь не будет опубликована.*\n\n📜Шаблон:\n1. Полное название вещи\n2. Состояние от 1 до 10 (~9/10)\n3. Размер вещи от XXS до XXL\n4. Город в котором находится вещь\n5. Цена в гривнах (остальная валюта по курсу)\n6. Личная встреча (где?), почта (какая?)",
					'parse_mode' => "Markdown"
					]);
					exit;
		}
		elseif($photoCount >= 10){
			$this->api->sendMessage([
				'text' => "Загрузите менее 10 фотографий!\n",
				'parse_mode' => "Markdown"
			]);
		}
		else {$this->api->sendMessage([
				'text' => "*Error\nТы меня пытаешься сломать? :)* \n /start - начало",
				'parse_mode' => "Markdown"]);exit;}
	}
	//Отправка фото и описания в чат админа
	function cmd_description(){
		if(!empty($this->api->chatId.'.txt') && !empty($this->result["message"]["from"]["username"]).'.txt'){
		$msg = "*". file_get_contents($this->api->chatId.'.txt') ."*\n";
		$msg .= "*Описание:*\n" . str_replace("/d","",($this->result["message"]["text"])) . "\n\n";
		$msg .= "*➖➖➖🔥🔥🔥➖➖➖*\n";

		$media_group_id = file_get_contents(($this->result["message"]["from"]["username"]).'.txt');
		$array_of_file_id = file($media_group_id.'.txt');
		foreach ($array_of_file_id as $file_id) {
			$file_id = rtrim($file_id, "\n\r");
			$InputMediaPhoto[] = ['type' => 'photo', 'media' => $file_id];
		}

		file_put_contents(($this->result["message"]["from"]["username"]).'.txt', $media_group_id);
		$InputMediaPhoto=json_encode($InputMediaPhoto);
		unlink($media_group_id.'.txt');
		unlink(($this->result["message"]["from"]["username"]).'.txt');
		file_put_contents($this->api->chatId.'.txt', '');

		//в чат админов
		$this->api->sendMediaGroup([
			'chat_id' => '-1001160009464',
			'media' => $InputMediaPhoto,
			'disable_notification' => 'false'
		]);
		$this->api->sendMessage([
			'text' => $msg,
			'chat_id' => -1001160009464,
			'parse_mode' => "Markdown",
		]);
		//в чат юзера
		$this->api->sendMessage([
			'chat_id' => $this->api->chatId,
			'text' => "*✅Ваше предложение отправлено на рассмотрение!\nВ ближайшее время наш менеджер свяжется с Вами.*\n\n /start - начало",
			'parse_mode' => "Markdown",
			]);
		}
		else {$this->api->sendMessage([
			'text' => "*Error\nТы меня пытаешься сломать? :)* \n /start - начало",
			'parse_mode' => "Markdown"]);exit;}

	}
	/**
	 * Ответ на ввод, не распознанный как команда
	 */
	function cmd_default(){
		if( stripos( $this->result["messages"]["text"], "/struct" ) !== false ){
			$this->api->sendMessage([
				'text' => "Cтруктуру сообщения:\n<pre>" . print_r( $this->result, 1) . "</pre>",
				'parse_mode'=> 'HTML'
			]);
			exit;
		}
		if(isset($this->result["message"]["media_group_id"])){
				$media_group_id = $this->result["message"]["media_group_id"];
				$photo = $this->result["message"]["photo"];
				$file_id = $photo[count($photo) - 1]['file_id'];
				file_put_contents($media_group_id.'.txt', $file_id."\r\n", FILE_APPEND);
				file_put_contents(($this->result["message"]["from"]["username"]).'.txt', $media_group_id);
				exit;
			}
		if(isset($this->result["message"]["photo"])){
			$this->api->sendMessage([
				'text' => "Загрузите не менее 3 фото",
			]);
		exit;}
		}
	}
