<?php

require_once INCLUDE_DIR . 'class.plugin.php';

class TelegramPluginConfig extends PluginConfig {
    function getOptions() {
        return array(
            'telegram' => new SectionBreakField(array(
                'label' => 'Telegram Bot',
            )),
            'telegram-token' => new TextboxField(array(
                'label' => 'Telegram Bot Token',
                'configuration' => array('size'=>100, 'length'=>200),
            )),
             'chats' => new TextareaField(array(
                'id' => 'chats',
                'label' => 'Chats and depts',
                'configuration' => array('html'=>false, 'rows'=>2, 'cols'=>40),
                'hint' => 'Use  "dept:chat_id". Place one '
                    .'entry per line',
            )),

            'telegram-tor-proxy' => new TextboxField(array(
                'label' => 'Tor proxy',
                'configuration' => array('size'=>100, 'length'=>200),
            )),
            'telegram-include-body' => new BooleanField(array(
                'label' => 'Include Body',
                'default' => 0,
            )),
                'debug' => new BooleanField(array(
                'label' => 'Debug message in error.log',
                'default' => 0,
            )),

        );
    }
}
?>
