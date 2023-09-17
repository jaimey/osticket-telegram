<?php

require_once(INCLUDE_DIR . 'class.signal.php');
require_once(INCLUDE_DIR . 'class.plugin.php');
require_once('config.php');

class TelegramPlugin extends Plugin
{
    var $config_class = "TelegramPluginConfig";
    static $pluginInstance = null;

    private function getPluginInstance(?int $id)
    {
        if ($id && ($i = $this->getInstance($id)))
            return $i;

        return $this->getInstances()->first();
    }

    function bootstrap()
    {
        self::$pluginInstance = self::getPluginInstance(null);
        Signal::connect('ticket.created', array($this, 'onTicketCreated'), 'Ticket');
    }

    function onTicketCreated($ticket)
    {
        global $ost;
        $ticketLink = $ost->getConfig()->getUrl() . 'scp/tickets.php?id=' . $ticket->getId();
        $ticketId = $ticket->getNumber();
        $title = $ticket->getSubject() ?: 'No subject';
        $createdBy = $ticket->getName() . " (" . $ticket->getEmail() . ")";
        $chatid = $this->getConfig(self::$pluginInstance)->get('telegram-chat-id');
        $chatid = is_numeric($chatid) ? "-" . $chatid : "@" . $chatid;
        if ($this->getConfig(self::$pluginInstance)->get('telegram-include-body')) {
            $body = $ticket->getLastMessage()->getMessage() ?: 'No content';
            $body = str_replace('<p>', '', $body);
            $body = str_replace('</p>', '<br />', $body);
            $breaks = array("<br />", "<br>", "<br/>");
            $body = str_ireplace($breaks, "\n", $body);
            $body = preg_replace('/\v(?:[\v\h]+)/', '', $body);
            $body = strip_tags($body);
        }

        $this->sendToTelegram(
            array(
                "method" => "sendMessage",
                "chat_id" => $chatid,
                "text" => "<b>New Ticket:</b> <a href=\"" . $ticketLink . "\">#" . $ticketId . "</a>\n<b>Created by:</b> " . $createdBy . "\n<b>Subject:</b> " . $title . ($body ? "\n<b>Message:</b>\n" . $body : ''),
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

            $botToken = $this->getConfig(self::$pluginInstance)->get('telegram-bot-token');

            $ch = curl_init('https://api.telegram.org/bot' . $botToken . '/');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string)
                )
            );

            $result = curl_exec($ch);

            if ($result === false) {
                throw new Exception(curl_error($ch));
            } else {
                $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if ($statusCode != '200') {
                    throw new Exception(' Http code: ' . $statusCode);
                }
            }

            curl_close($ch);
        } catch (Exception $e) {
            $ost->logError('Telegram error', $e->getMessage(), true);
            error_log('Error posting to Telegram. ' . $e->getMessage());
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
