osTicket-telegram_fork
==============
Это плагин для [osTicket](https://osticket.com) для постинга нотификаций в [Telegram](https://telegram.org) канал/чат/группу.
Мне понадобилась оповещалка для телеги, и я форкнул этот плагин. 
Дописал чуть более продвинутый дебаг, и возможность гнать трафик через SOCKS5 proxy.
Дописал возможность разделять по разным чатам сообщения для разных департаментов. Нужно, если хелпдеском пользуются не только IT, но и, например, HR. Можно разные уведомления закидывать в разные чаты. 


## Note
Если мне понадобится какой то функционал - я его дописываю. 

Install
--------
Скачайте этот репозиторий на сервер с остикетом, киньте папку telegram-bot в includes/plugins, тогда он появится в списке доступных плагинов. Его надо установить, из вебморды остикета, настроить (токен бота, ид чата, и айпи прокси (apt-get install tor && systemctl start tor && systemctl enable tor, и тогда этот параметр надо выставить в 127.0.0.1:9050)).
For more information about Telegram Bot, see: https://core.telegram.org/bots/api

Info
------
Этот плагин использует php-curl и протестирован с osTicket-1.14.1

Based on [thammanna/osticket-slack](https://github.com/thammanna/osticket-slack)
