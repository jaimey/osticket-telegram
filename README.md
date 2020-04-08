osTicket-telegram_fork
==============
Это плагин для [osTicket](https://osticket.com) для постинга нотификаций в [Telegram](https://telegram.org) канал/чат/группу.
Мне понадобилась оповещалка для телеги, и я форкнул этот плагин. 
Дописал чуть более продвинутый дебаг, и возможность гнать трафик через SOCKS5 proxy.


## Note
Вряд-ли я буду что-то еще менять, если только какая нить бага не вылезет.

Install
--------
Скачайте этот репозиторий на сервер с остикетом, киньте папку telegram-bot в includes/plugins, тогда он появится в списке доступных плагинов. Его надо установить, из вебморды остикета, настроить (токен дота, ид чата, и айпи прокси (apt-get install tor && systemctl start tor && systemctl enable tor, и тогда этот параметр надо выставить в 127.0.0.1:9050)).
For more information about Telegram Bot, see: https://core.telegram.org/bots/api

Info
------
Этот плагин использует php-curl и протестирован с osTicket-1.14.1

Based on [thammanna/osticket-slack](https://github.com/thammanna/osticket-slack)
