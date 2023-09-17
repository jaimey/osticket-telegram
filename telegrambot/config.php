<?php

require_once INCLUDE_DIR . 'class.plugin.php';

class TelegramPluginConfig extends PluginConfig
{
    function getOptions()
    {
        return array(
            'telegram' => new SectionBreakField(array(
                'label' => 'Telegram Bot',
            )),
            'telegram-bot-token' => new TextboxField(array(
                'label' => 'Telegram Bot Token',
                'hint' => 'Get your token from <a href="https://t.me/botfather" target="_blank">BotFather</a><br>Example: 123456789:ABCdefGHIjklMNOpqrSTUvwxYZ123456789',
                'configuration' => array('size' => 100, 'length' => 200),
            )),
            'telegram-chat-id' => new TextboxField(array(
                'label' => 'Chat ID',
                'hint' => 'Get your chat ID from <a href="https://t.me/RawDataBot" target="_blank">RawDataBot</a><br>Example: 123456789 or channelname',
                'configuration' => array('size' => 100, 'length' => 200),
            )),
            'telegram-include-body' => new BooleanField(array(
                'label' => 'Include Body',
                'hint' => 'Include the body of the ticket in the notification',
                'default' => 0,
            )),
        );
    }
}
