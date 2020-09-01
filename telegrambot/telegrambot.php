<?php

require_once(INCLUDE_DIR.'class.signal.php');
require_once(INCLUDE_DIR.'class.plugin.php');
require_once('config.php');

class TelegramPlugin extends Plugin {
    var $config_class = "TelegramPluginConfig";

	function bootstrap() {
		Signal::connect('ticket.created', array($this, 'onTicketCreated'), 'Ticket');
    }

	function onTicketCreated($ticket)
	{
		global $ost;
		$ticketLink = $ost->getConfig()->getUrl().'scp/tickets.php?id='.$ticket->getId();
		$ticketId = $ticket->getNumber();
       		$title = $ticket->getSubject() ?: 'No subject';
		$createdBy = $ticket->getName()." (".$ticket->getEmail().")";
		$dept = $ticket->getDept();
		$chats = explode(PHP_EOL,$this->getConfig()->get('chats'));
		foreach($chats as $k) {
		    $temp = explode(':',$k);
		    if (strcasecmp($temp[0],$dept)==0) {
                     $chatid = $temp[1];
		     break;
                    }.
		}
		$chatid = '-'.$chatid;

        if ($this->getConfig()->get('telegram-include-body')) {
            $body = $ticket->getLastMessage()->getMessage() ?: 'No content';
			$body = str_replace('<p>', '', $body);
			$body = str_replace('</p>', '<br />' , $body);
			$breaks = array("<br />","<br>","<br/>");
			$body = str_ireplace($breaks, "\n", $body);
			$body = preg_replace('/\v(?:[\v\h]+)/', '', $body);
            $body = strip_tags($body);
        }

		$this->sendToTelegram(
			array(
				"method" => "sendMessage",
				"chat_id" => $chatid,
				"text" => "<b>New Ticket:</b> <a href=\"".$ticketLink."\">#".$ticketId."</a>\n<b>Created by:</b> ".$createdBy."\n<b>Subject:</b> ".$title.($body?"\n<b>Message:</b>\n".$body:''),
				"parse_mode" => "html",
				"disable_web_page_preview" => "True"
			)
		);
	}

	function sendToTelegram($payload)
    {
        try {
            global $ost;

            $data_string = utf8_encode(json_encode($payload));
	    $tor_proxy = $this->getConfig()->get('telegram-tor-proxy');
	    $tor_proxy = 'socks5://'.$tor_proxy.'"';
	    $token =  $this->getConfig()->get('telegram-token');

            $ch = curl_init();
	    curl_setopt( $ch, CURLOPT_URL, 'https://api.telegram.org/bot' . $token . '/sendMessage' );
            curl_setopt($ch, CURLOPT_POST,true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string)
                )
            );
	    curl_setopt($ch,CURLOPT_PROXYTYPE,CURLPROXY_SOCKS5);
	    curl_setopt($ch,CURLOPT_PROXY,$tor_proxy);
	    $result=curl_exec($ch);
            curl_close($ch);
	    if ($this->getConfig()->get('debug')) {
		    error_log ($result);
	    }
        } catch(Exception $e) {
            error_log('Error posting to Telegram. '. $e->getMessage());
        }
    }

    function escapeText($text)
    {
        $text = str_replace('&', '&amp;', $text);
        $text = str_replace('<', '&lt;', $text);
        $text = str_replace('>', '&gt;', $text);

        return $text;
    }
}
?>
