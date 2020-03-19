<?php 

/* Corona Virus Info Telegram Bot [Version: 1.0]
 *
 * Coded By kihanb [ kihanb.ir | @kihanb_ir ]
 *
 * Api Manager : one api [ one-api.ir | @One_apis ] ( You should sign up here & use your own token ! )
 *
 */
ob_start();
error_reporting(0);
date_default_timezone_set('Asia/Tehran');

//-----------------------------------------
$admin = 615724046; // *** Admin Id Number
$channel = "kihanb_ir"; // *** Your Channel Id
$token = ''; // *** Your Token ( https://one-api.ir )
//-----------------------------------------
define('API_KEY',''); // *** Your Bot Token
//-----------------------------------------
function Bot($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
       return json_decode($res);
    }
}
//-----------------------------------------
function SendMessage($chat_id,$text,$mode,$reply = null,$keyboard = null){
	Bot('SendMessage',[
	'chat_id'=>$chat_id,
	'text'=>$text,
	'parse_mode'=>$mode,
	'reply_to_message_id'=>$reply,
	'reply_markup'=>$keyboard
	]);
}
function answerCallbackQuery($callback_query_id,$text,$show_alert)
{
    bot('answerCallbackQuery', [
        'callback_query_id' => $callback_query_id,
        'text' => $text,
        'show_alert' => $show_alert,
    ]);
}
function EditMessage_inline($chat_id,$message_id,$text,$keyboard){
	Bot('editMessagetext',[
    //'chat_id'=>$chat_id,
	'inline_message_id'=>$message_id,
    'text'=>$text,
    'reply_markup'=>$keyboard
	]);
	}
	function EditMessage($chat_id,$message_id,$text,$keyboard){
	Bot('editMessagetext',[
    'chat_id'=>$chat_id,
	'message_id'=>$message_id,
    'text'=>$text,
    'reply_markup'=>$keyboard
	]);
	}
function SendDocument($chatid,$document,$caption = null){
	Bot('SendDocument',[
	'chat_id'=>$chatid,
	'document'=>$document,
	'caption'=>$caption
	]);
}
function ForwardMessage($chatid,$from_chat,$message_id){
	bot('ForwardMessage',[
	'chat_id'=>$chatid,
	'from_chat_id'=>$from_chat,
	'message_id'=>$message_id
	]);
	
}
function Download($link, $path){
    $file = fopen($link, 'r') or die("Can't Open Url !");
    file_put_contents($path, $file);
    fclose($file);
    return is_file($path);
  }
function GetChat($chatid){
	$get =  Bot('GetChat',['chat_id'=>$chatid]);
	return $get;
}
function GetMe(){
	$get =  Bot('GetMe',[]);
	return $get->result->username;
} 
$botid = "@" . getMe();


function getChatMember($channel, $id = ""){
    $forchannel = json_decode(file_get_contents("https://api.telegram.org/bot".API_KEY."/getChatMember?chat_id=@$channel&user_id=".$id));
    $tch = $forchannel->result->status;

     if($tch == 'member' or $tch == 'creator' or $tch == 'administrator'){
         return true;
     }else{
         return false;
     }
}

//-----------------------------------------
$update = json_decode(file_get_contents('php://input'));
$jh = file_get_contents('php://input');
if(isset($update->message)){
    $message = $update->message; 
    $chat_id = $message->chat->id;
    $text = $message->text;
    $message_id = $message->message_id;
    $textmessage = $message->text;
    $from_id = $message->from->id;
    $tc = $message->chat->type;
    $first_name = $message->from->first_name;
    $last_name = $message->from->last_name;
    $username = $message->from->username;
    $caption = $message->caption;
    $reply = $message->reply_to_message->forward_from->id;
    $reply_id = $message->reply_to_message->from->id;
    $data = $message->data;
    $lang = $message->from->language_code;
}
if(isset($update->callback_query)){
    $data = $update->callback_query->data;
    $data_id = $update->callback_query->id;
    $chatid = $update->callback_query->message->chat->id;
    $fromid = $update->callback_query->from->id;
    $tccall = $update->callback_query->chat->type;
    $messageid = $update->callback_query->inline_message_id;
    $lang = $update->callback_query->from->language_code;
}
//--------------------------

if(isset($chatid)){
    $chat = $chatid;
}elseif(isset($chat_id)){
    $chat = $chat_id;
}

if(getChatMember($channel,$chat) == false){
    bot('SendMessage',[
        'chat_id'=>$chat,
        'text'=>"📣کاربر گرامی
جهت استفاده از خدمات این ربات، ابتدا در کانال ما عضو شوید:
        
@$channel

@$channel

سپس دستور /start را مجددا ارسال نمایید!",
        	 ]);
}elseif($textmessage == '/start'){

        $txt = "💠به ربات کرونا ویروس خوش آمدید!

〽️با این ربات قادر خواهید بود آمار لحظه ای این ویروس را در جهان مشاهده نمایید!
@$channel";

     $lic = false;
$read = file("users.txt");
foreach($read as $name){
    $name2 = STR_REPLACE("\n","",$name);
    if($name2 == $chat_id){
        $lic = true;
    }
}
	if($lic == false){
    $myfile2 = fopen("users.txt", "a") or die("Unable to open file!");
        fwrite($myfile2, "$chat_id\n");
        fclose($myfile2);
}
	    bot('sendMessage',[
         'chat_id'=>$chat_id,
          'text'=>"$txt",
          'reply_markup'=> json_encode([
             'inline_keyboard'=>[
[['text'=>'وضعیت سایر کشورها','callback_data'=>"corona"],['text'=>'وضعیت ایران','callback_data'=>"iran"]]
],
'resize_keyboard'=>true
])
	 ]);
}
if($update->callback_query->data == 'corona'){
    
    $datas = json_decode(file_get_contents("https://one-api.ir/corona/?token=$token"),true);
    
    $txt = '';
    $txt2 = '';
    $txt3 = '';
    $n = 0;
    foreach($datas['result']['entries'] as $data){
        $n++;
        $country = $data['country'];
        $cases = $data['cases'];
        $deaths = $data['deaths'];
        $recovered = $data['recovered'];
        if($n < 60){
        
        
        $txt .= "🚩کشور :  $country
🦠مبتلایان: $cases
☠️مرگ: $deaths / 💉 درمان یافته: $recovered

";
        }elseif($n >= 60 && $n < 120){
            $txt2 .= "🚩کشور :  $country
🦠مبتلایان: $cases
☠️مرگ: $deaths / 💉 درمان یافته: $recovered

";
        }else{
            $txt3 .= "🚩کشور :  $country
🦠مبتلایان: $cases
☠️مرگ: $deaths / 💉 درمان یافته: $recovered

";
        }
    }
    Bot('SendMessage',[
    'chat_id'=>$chat,
    'text'=>"$txt $botid",
	]);
    Bot('SendMessage',[
    'chat_id'=>$chat,
    'text'=>"$txt2 $botid",
	]);
    Bot('SendMessage',[
    'chat_id'=>$chat,
    'text'=>"$txt3 $botid",
	]);

}
if($update->callback_query->data == 'iran'){
    
    $datas = json_decode(file_get_contents("https://one-api.ir/corona/?token=$token"),true);
    
    foreach($datas['result']['entries'] as $data){

        if($data['country'] == 'Iran'){
            
        $country = $data['country'];
        $cases = $data['cases'];
        $deaths = $data['deaths'];
        $recovered = $data['recovered'];
        
        $txt = "🚩کشور :  $country
🦠مبتلایان: $cases
☠️مرگ: $deaths / 💉 درمان یافته: $recovered

";
break;
        }
    }

	Bot('SendMessage',[
    'chat_id'=>$chat,
    'text'=>"$txt $botid",
    'reply_markup'=>json_encode(['inline_keyboard'=>[
[['text'=>'بروزرسانی','callback_data'=>"iran"]],
],'resize_keyboard'=>true
])
	]);
}

if($textmessage=="/panel" && $chat_id == $admin){
    file_put_contents("data/$chat_id/stats.txt","admin");
    bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"ادمین عزیز به پنل مدیریت ربات خوش آمدید💎",
  'reply_markup'=>json_encode([
                      'keyboard'=>[
  [['text'=>"آمار ربات"]],
  [['text'=>"️💠فروارد همگانی💠"],['text'=>"💠ارسال همگانی💠"]],
  [['text'=>"🔙"]],
	],
		"resize_keyboard"=>true,
	 ])
	 ]);
}
elseif($textmessage=="آمار ربات" && $chat_id == $admin){
   $txtt = file_get_contents('users.txt');
    $member_id = explode("\n",$txtt);
    $amar = count($member_id) -1;
    $tc= file_get_contents('data/channels.txt');
    $mc = explode("\n",$tc);
    $amarc = count($mc) -1;
     bot('sendMessage',[
 'chat_id'=>$chat_id,
 'text'=>"Users: <code>$amar</code>",
 'parse_mode'=>"HTML",
  ]);
}


// Admin Panel
if($textmessage=="💠ارسال همگانی💠" && $chat_id == $admin ){     
     file_put_contents("data/$chat_id/stats.txt","send2all");
      bot('sendMessage',[
 'chat_id'=>$chat_id,
 'text'=>"برای ارسال به همه اعضا لطفا پیام خود را ارسال کنید💣",
 'parse_mode'=>"MarkDown",
  ]);
}  
elseif($chat_id == $admin && $stats == "send2all" ){  
    file_put_contents("data/$chat_id/stats.txt","none");
    $text1 = $message->text;
    $all_member = fopen( "data/users.txt", 'r');
		while( !feof( $all_member)) {
 			$user = fgets( $all_member);
 			 bot('sendMessage',[
 'chat_id'=>$user,
 'text'=>$text1,
 'parse_mode'=>"MarkDown",
  ]);
}  }
elseif($textmessage=="️💠فروارد همگانی💠" && $chat_id == $admin ){           
     file_put_contents("data/$chat_id/stats.txt","f2all");
      bot('sendMessage',[
 'chat_id'=>$chat_id,
 'text'=>"برای فروارد به همه اعضا لطفا پیام خود را فروارد کنید💣",
 'parse_mode'=>"MarkDown",
  ]);
}  
elseif($chat_id == $admin && $stats == "f2all" ){  
    file_put_contents("data/$chat_id/stats.txt","none");
    $all_member = fopen( "data/users.txt", 'r');
		while( !feof( $all_member)) {
 			$user = fgets( $all_member);
ForwardMessage($user,$admin,$message_id);
		}    
		}
    

?>