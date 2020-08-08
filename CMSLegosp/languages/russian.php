<?php

/*********************************************************************************
*                                                                                *
*   shop-script Legosp - legosp.net                                              *
*   Skype: legoedition                                                           *
*   Email: legoedition@gmail.com                                                 *
*   Лицензионное соглашение: https://legosp.net/info/litsenzionnoe_soglashenie/  *
*   Copyright (c) 2010-2019  All rights reserved.                                *
*                                                                                *
*********************************************************************************/
 
 
 
//language file

//		RUSSIAN		//

//default character set, that will be used
if (DB_CHARSET=='cp1251')
define('DEFAULT_CHARSET', 'windows-1251');
else define('DEFAULT_CHARSET', 'utf-8');
define('LINK_TO_HOMEPAGE', 'Главная');
define('PRODUCTS_BEST_CHOISE', 'Хит продаж');
define('MORE_INFO_ON_PRODUCT', 'подробнее...');
define('MORE_ABOUT', 'Подробнее...');
define('MORE_ABOUT_PRODUCT', 'Подробно');
define('NO_ABOUT', 'Описание отсутствует');
define('ENLARGE_PICTURE', 'увеличить...');
define('ADD_TO_CART_STRING', 'Заказать');
define('LIST_PRICE', 'Старая цена');
define('CURRENT_PRICE', 'Наша цена');
define('YOU_SAVE', 'Вы экономите');
define('IN_STOCK', 'На складе');
define('VOTING_FOR_ITEM_TITLE', 'Ваша оценка');
define('MARK_EXCELLENT', 'Отлично!');
define('MARK_GOOD', 'Хорошо');
define('MARK_AVERAGE', 'Средне');
define('MARK_POOR', 'Плохо');
define('MARK_PUNY', 'Очень плохо');
define('VOTE_BUTTON', 'Оценить!');
define('VOTES_FOR_ITEM_STRING', 'голосов');
define('NAVIGATOR_LEFT', 'назад');
define('NAVIGATOR_RIGHT', 'вперед');
define('HOT_NEW', 'Горячие новинки');

define('Your_order_number', 'Вашему заказу присвоен номер');
define('QUESTION_UNSUBSCRIBE', 'Удалить?');
define('ADMIN_SETTINGS_CPU', 'Включить ЧПУ');
define('ADMIN_EDIT', 'Редактировать');

define('ADMIN_ORDER_TOV', 'Товарный чек');
define('ADMIN_ORDER_EXCEL', 'Export в Excel');

define('LOGOUT_LINK', 'Выйти из сеанса...');
define('ADMINISTRATE_LINK', '>> АДМИНИСТРИРОВАНИЕ <<');
define('ADMIN_CONF_EMAILSMTP','Использовать SMTP');

define('ANSWER_YES', 'да');
define('ANSWER_NO', 'нет');
define('SAVE_BUTTON', 'Сохранить');
define('DELETE_BUTTON', 'Удалить');
define('CLOSE_BUTTON', 'Закрыть');
define('CANCEL_BUTTON', 'Отмена');
define('UPDATE_BUTTON', 'Пересчитать');
define('ADD_BUTTON', 'Добавить');
define('ADMIN_ENABLED', 'Вкл.');

define('STRING_BACK_TO_SHOPPING', 'Вернуться к покупкам');
define('STRING_SHOW', 'показывать');
define('STRING_PRESENT', 'В подарок');
define('STRING_PRESENT_SELECT', 'Выберите подарок');
define('STRING_PRINT_ORDER', 'Распечатать заказ');
define('STRING_NUMBER', 'числа');
define('STRING_RELATED_ITEMS', 'Рекомендуем');
define('STRING_NUMBER_ONLY', 'только число');
define('STRING_EMPTY_CATEGORY', 'нет товаров');
define('STRING_NO_ORDERS', 'нет заказов');
define('STRING_NO_USERS', 'нет пользователей');
define('STRING_SEARCH', 'Поиск...');
define('STRING_phrase_SEARCH', 'Фраза для поиска');
define('STRING_GO_SEARCH', 'Найти');
define('STRING_HOWSEARCH', 'Как искать?');
define('STRING_LANGUAGE', 'Язык');
define('STRING_MANUFACTURER', 'Производитель');
define('STRING_BRANDS', 'Производители');
define('NOT_SELECTED', 'Не выбрано');
define('STRING_MODEL', 'Модель');
define('STRING_PRICELIST', 'Прайс-лист');
define('DOWNLOAD_PRICE','Скачать прайс в формате MS Excel');

define('STRING_GREETINGS', 'Добро пожаловать');
define('STRING_FOUND', 'Найдено ');
define('STRING_NO_MATCHES_FOUND', 'Ничего не найдено');
define('STRING_PRODUCTS', 'товар(ов)');
define('STRING_ORDER_ID', 'Номер заказа');
define('STRING_ORDER_ID_MAIL', 'Ваш заказ №');
define('STRING_ORDER_PLACED', 'Спасибо за Ваш заказ!');
define('STRING_PLACE_ORDER', 'Оформить заказ!');
define('STRING_NEXT', 'след');
define('STRING_PREVIOUS', 'пред');
define('STRING_SHOWALL', 'показать все');
define('STRING_REQUIRED', '<font style="color: #F00">*</font> Поля обязательны для заполнения');
define('STRING_CONTACT_INFORMATION', 'Контакты');
define('STRING_CONTACT_SEND_MESSAGE', 'Вы также можете отправить нам сообщение воспользовавшись формой обратной связи:');
define('STRING_ADRESS_INFORMATION', 'Как нас найти');
define('STRING_MORE_ABOUT', 'Подробно...');
define('STRING_SEND_MAIL', 'Написать письмо');
define('STRING_SEND', 'Отправить');
define('STRING_SEND_TEXT', 'Текст сообщения');
define('STRING_MAIL_FROM_SITE', 'Письмо с сайта');
define('STRING_SEND_CAPCHA', 'Защитный код');
define('STRING_SEND_ERROR', '<font style="color: #C00">Ошибка отправки сообщения</font>');

define('STRING_CATEGORY_SHOW', 'Показать список категорий');
define('STRING_CATEGORY_HIDE', 'Скрыть список категорий');
define('STRING_DOP_INFO', 'Дополнительная информация');
define('STRING_BLOG', 'БЛОГ');
define('STRING_FORUM_SUPPORT', 'Форум поддержки');
define('STRING_FEEDBACK', 'Обратная связь');
define('STRING_HELP', 'Помощь');
define('STRING_USER_NAME', 'Имя пользователя');
define('STRING_default','по умолчанию');




define('CART_CONTENT_EMPTY', '(нет товаров)');
define('CART_CONTENT_NOT_EMPTY', 'товар(ов): ');
define('CART_TITLE', 'Корзина');
define('CART_CLEAR', 'очистить');
define('CART_PROCEED_TO_CHECKOUT', 'Оформить заказ');
define('CART_EMPTY', 'Ваша корзина пуста');

define('PRODUCT_IN_STOCK', 'В наличии');
define('PRODUCT_NO_IN_STOCK', 'На заказ');

//table titles

define('TABLE_PRODUCT_NAME', 'Название');
define('TABLE_PRODUCT_QUANTITY', 'Количество');
define('TABLE_PRODUCT_COST', 'Цена');
define('TABLE_PRODUCT_SUMM', 'Сумма');
define('TABLE_TOTAL', 'Итого');
define('TABLE_ORDER_TIME', 'Время заказа');
define('TABLE_ORDERED_PRODUCTS', 'Заказанные товары');
define('TABLE_ORDER_TOTAL', 'Стоимость заказа');
define('TABLE_CUSTOMER', 'Покупатель');
define('ORDER_EDIT', 'Редактирование заказа');


//different admin strings

define('ADMIN_TITLE', 'Администрирование');
define('ADMIN_PAGES_FIRST','Начало');
define('ADMIN_PAGES_PREV','Назад');
define('ADMIN_PAGES_NEXT','Следующая');
define('ADMIN_PAGES_LAST','Конец');

define('ADMIN_WELCOME', '<p><font class=big>Добро пожаловать в режим администрирования!</font><p>Используйте меню для доступа к разделам администраторской части.');
define('ADMIN_NEW_ORDERS', 'Новые заказы');
define('ADMIN_ORDERS_SEARCHP','Добавить продукты в заказ:');
define('ADMIN_CATEGORIES', 'Категории');
define('ADMIN_CATEGORIES_NAME', 'Наименование');
define('ADMIN_ON', 'Вкл.');
define('ADMIN_PRODUCTS', 'Товары');
define('ADMIN_CATALOG', 'Каталог');
define('ADMIN_PRESENTS', 'Подарки');
define('ADMIN_PRESENT_SELECT', 'Позволить покупателю выбирать подарок');
define('ADMIN_DISCOUNT_VALUE', 'Скидка от суммы');
define('ADMIN_DISCOUNT', 'Ваша скидка');
define('ADMIN_DISCOUNT_STRING', 'Скидка');
define('ADMIN_REVIEW', 'Отзывы');
define('ADMIN_ORDER_T12', 'Форма Торг-12');
define('ADMIN_ORDER_COMPLITE', 'Выполнен');
define('ADMIN_ORDER_INCOMPLITE', 'Не выполнен');
define('ADMIN_COMPLITE_ORDERS', 'Выполненные заказы');
define('ADMIN_PRESENT_VAL', 'От суммы');
define('ADMIN_INDEX_PAGE', 'Главная страница');
define('ADMIN_MAIN_PAGES', 'Главные страницы');
define('ADMIN_CONTACT_PAGE', 'Контакты');
define('ADMIN_MORE_ABOUT', 'Полное описание');
define('ADMIN_MORE', 'Дополнительно');
define('ADMIN_INFORMATION', 'Информация');
define('ADMIN_SETTINGS', 'Настройки');
define('ADMIN_SELECT', 'Выбрать');
define('ADMIN_SETTINGS_GENERAL', 'Общие');
define('ADMIN_SETTINGS_APPEARENCE', 'Оформление');
define('ADMIN_SETTINGS_IMAGES', 'Изображения');
define('ADMIN_CUSTOMERS_AND_ORDERS', 'Заказы');

define('ADMIN_ABOUT_PAGE', 'О магазине');
define('ADMIN_SHIPPING_PAGE', 'Сервис');
define('ADMIN_SITE_MAP', 'Карта сайта');
define('ADMIN_NEWS', 'Новости');
define('ADMIN_NEWS_ALL', 'Все новости');
define('ADMIN_NEWS_ADD', 'Добавить');
define('ADMIN_NEWS_TITLE', 'Заголовок');
define('ADMIN_NEWS_TEXT', 'Текст');
define('ADMIN_NEWS_DATE', 'Дата');
define('ADMIN_NEWS_BRIEF', 'Краткое содержание');
define('ADMIN_NEWS_EDIT', 'Добавить/редактировать');
define('ADMIN_NEWS_READ', 'далее...');
define('ADMIN_NEWS_PICTURE', 'Фото');
define('ADMIN_NEWS_DELETE', 'Удалить');
define('ADMIN_NEWS_ON', 'Вкл.');
define('ADMIN_NEWS_ONHOME', 'Выводить новости на главной');
define('ADMIN_NEWS_ONHOME_COUNT', 'Количество новостей на главной');
define('ADMIN_PAGES_ONHOME', 'Выводить статьи на главной');
define('ADMIN_PAGES_ONHOME_COUNT', 'Количество статей на главной');
define('ADMIN_BRANDS_ALL', 'Все производители');
define('ADMIN_BRAND_COMMENT', 'При отсутствии на складе');
define('ADMIN_PRICE_SHOW_COUNT', 'Количество строк прайса на странице');
define('ADMIN_USERS', 'Покупатели');

define('ADMIN_USERS_EDIT', 'Редактирования данных покупателя');



define('ADMIN_PAGES', 'Статьи');
define('ADMIN_PAGES_TITLE', 'Оглавление');
define('ADMIN_PAGE_BACK', 'К оглавлению');
define('ADMIN_PAGE_ALL', 'Все статьи');
define('ADMIN_PAGE_ADD', 'Добавить');
define('ADMIN_PAGE_TITLE', 'Заголовок');
define('ADMIN_PAGE_TEXT', 'Текст');
define('ADMIN_PAGE_DATE', 'Дата');
define('ADMIN_PAGE_BRIEF', 'Краткое содержание');
define('ADMIN_PAGE_EDIT', 'Добавить/редактировать');
define('ADMIN_PAGE_READ', 'далее...');
define('ADMIN_PAGE_PICTURE', 'Фото');
define('ADMIN_PAGE_DELETE', 'Удалить');
define('ADMIN_PAGE_ON', 'Вкл.');

define('ADMIN_MINIMAL', 'Минимальный заказ');
define('ADMIN_MINIMAL_COUNT', 'Количество');
define('ADMIN_MINIMAL_SUMM', 'Сумма заказа');
define('ADMIN_MINIMAL_PRODUCT', 'Добавлять товар');
define('ADMIN_MINIMAL_COST', 'На сумму');

define('ADMIN_IMAGE', 'Изображение');
define('ADMIN_IMAGE_SIZE', 'Размер изображений');
define('ADMIN_IMAGE_SMALL', 'Маленькая фотография');
define('ADMIN_IMAGE_NORMAL', 'Обычная фотография');
define('ADMIN_IMAGE_BIG', 'Большая фотография');

define('ADMIN_TIMES', 'График работы');
define('ADMIN_SHOW_MENU', 'Развернуть меню');
define('ADMIN_FLOAT_QUANTITY', 'Разрешить дробное количество товара в заказе');
define('ADMIN_AUX_INFO', 'Главные страницы');
define('ADMIN_AUX_PAGES', 'Дополнительные страницы');
define('ADMIN_BACK_TO_SHOP', 'в общедоступную часть ...');
define('ADMIN_SORT_ORDER', 'Порядок сортировки');
define('ACCESS', 'Вход в панель управления');
define('ACCESS_LOGIN', 'Логин');
define('ACCESS_LOGOUT', 'Выход');
define('ACCESS_LOGIN_NAME', 'Вы вошли как ');
define('ACCESS_PASS', 'Пароль');
define('ACCESS_ENTER', 'Вход');
define('ACCESS_ADMIN', 'Администратор');
define('ACCESS_ERROR', 'Неверный логин или пароль.');

define('ADMIN_LOGIN_PASSWORD', 'Доступ к администрированию');
define('ADMIN_CHANGE_LOGINPASSWORD', 'Изменить логин и пароль администратора');
define('ADMIN_CURRENT_LOGIN', 'Логин');
define('ADMIN_OLD_PASS', 'Старый пароль');
define('ADMIN_NEW_PASS', 'Новый пароль');
define('ADMIN_NEW_PASS_CONFIRM', 'Подтвердите новый пароль');
define('ADMIN_UPDATE_SUCCESSFUL', '<font style="color: red"><b>Обновление прошло успешно!</b></font>');
define('ADMIN_UPDATE_ERROR', '<font style="color: red;"><b>Нет прав для переименования файла '.CONF_ADMIN_FILE.' и/или '.CONF_ADMIN_FILE_ACCESS.' переименуйте их в ручную.</b></font>');
define('ADMIN_NO_SPECIAL_OFFERS', 'Спец-предложения не выбраны');
define('ADMIN_NO_PRESENT', 'Подарки не выбраны');
define('ADMIN_ADD_SPECIAL_OFFERS', 'Добавить в список спец-предложений');
define('ADMIN_SPECIAL_OFFERS_DESC', 'Спец-предложения показываются на витрине Вашего магазина.<br>
Выбрать товарные позиции, которые будут показаны как спец-предложения<br>
Вы можете в подразделе <a href="admin.php?dpt=catalog&sub=products">"Товары"</a>, кликнув по значку <img src="./images/backend/special.png" border=0> в таблице товаров.<br>
В спец-предложения можно выбрать только товары с фотографией.');
define('ADMIN_PRESENT_DESC', 'Подарки автоматически добавляются в корзину при достижении заданной общей суммы заказа<br>Добавить товар в подарки
Вы можете в подразделе <a href="admin.php?dpt=catalog&sub=products">"Товары"</a>, кликнув по значку <img src="./images/backend/present.png" border=0> в таблице товаров.<br>');
define('ADMIN_ROOT_WARNING', '<font style="color: #F00">Все товары, находящиеся в корне, не видны пользователям!</font>');
define('ADMIN_ABOUT_PRICES', 'цены актуальны на момент заказа и указаны без налога');
define('ADMIN_SHOP_NAME', 'Название магазина');
define('ADMIN_SHOP_DESCRIPTION', 'Описание ');
define('ADMIN_SHOP_KEYWORDS', 'Ключевые слова ');
define('ADMIN_SHOP_URL', 'URL магазина');
define('ADMIN_SHOP_EMAIL', 'Контактный email адрес Вашего магазина');
define('ADMIN_ORDERS_EMAIL', 'Email, на который будут отправляться уведомления о заказах');
define('ADMIN_SHOW_ADD2CART', 'Включить возможность добавления товаров в корзину и оформления заказов');
define('ADMIN_SHOW_ADD2CART_INSTOCK', 'Разрешить добавлять в корзину при отсутствии на складе');
define('ADMIN_SHOW_PRODUCT_INSTOCK', 'Отображать товар при отсутствии на складе');
define('ADMIN_SHOW_PRODUCT_VARIANTS_INSTOCK', 'Отображать доп. параметры при отсутствии на складе');
define('ADMIN_SHOW_BESTCHOICE', 'Показывать наиболее популярные товары в пустых категориях');
define('ADMIN_MAX_PRODUCTS_COUNT_PER_PAGE', 'Максимальное количество товаров на странице');
define('ADMIN_MAX_NEWS_COUNT_PER_PAGE', 'Максимальное количество новостей на странице');
define('ADMIN_MAX_PAGES_COUNT_PER_PAGE', 'Максимальное количество статей на странице');
define('ADMIN_MAX_COLUMNS_PER_PAGE', 'Количество столбцов при показе товаров');
define('ADMIN_MAX_HITS', 'Максимальное количество выводимых "хитов продаж"');
define('ADMIN_SCROLL_HITS', 'Количество одновременно выводимых "хитов продаж"');
define('ADMIN_HITS_SETTINGS', 'Настройки хитов продаж');
define('ADMIN_HITS_FRIQ', 'Частота смены слайдов (мс.)');
define('ADMIN_HITS_SPEED', 'Скорость смены слайдов (мс.)');
define('ADMIN_HITS_TYPE', 'Тип вывода');

define('ADMIN_MAIN_COLORS', 'Цвета');
define('ADMIN_COLOR', 'Цвет');
define('ADMIN_COLOR_BODY', 'Цвет Листа(Body)');
define('ADMIN_COLOR_VOTE', 'Результат голосования');
define('ADMIN_COLOR_IMAGE', 'Фон изоражения');
define('ADMIN_COLOR_SCHEME', 'Шаблон');
define('ADMIN_COLOR_IMPORT', 'Ипорт шаблона из архива');
define('ADMIN_SPECIAL_OFFERS', 'Специальные предложения');


define('ADMIN_CATEGORY_IMPORT_DIR', 'Из каталога импорта');
define('ADMIN_CATEGORY_TITLE', 'Каталог');
define('ADMIN_CATEGORY_TITLE_PR', 'Категория');
define('ADMIN_CATEGORY_NEW', 'Создать новую категорию');
define('ADMIN_CATEGORY_PARENT', 'Родительская категория:');
define('ADMIN_CATEGORY_MANUAL_INPUT', 'Ручной ввод');
define('ADMIN_CATEGORY_MOVE_TO', 'Переместить в:');
define('ADMIN_CATEGORY_NAME', 'Название категории:');
define('ADMIN_CATEGORY_COUNT_P', 'Кол. товаров');
define('ADMIN_CATEGORY_TITLE_H1', 'Заголовок (H1):');
define('ADMIN_CATEGORY_LOGO', 'Логотип');
define('ADMIN_CATEGORY_ROOT', 'Корень');
define('ADMIN_CATEGORY_DESC', 'Короткое описание');
define('ADMIN_CATEGORY_ALL', 'Все товары');
define('ADMIN_CATALOG_CONF', 'Товары');
define('ADMIN_FAST_ORDER', 'Срочная доставка');
define('ADMIN_FAST_ORDER_COST', 'Доплата за срочный заказ');
define('ADMIN_PRODUCT_TITLE', 'Товары');
define('ADMIN_PRODUCT_TITLE_H1', 'Заголовок (H1)');
define('ADMIN_PRODUCT_CARD', 'Карточка товара');
define('ADMIN_PRODUCT_NEW', 'Добавить новый товар');
define('ADMIN_PRODUCT_CODE', 'Артикул');
define('ADMIN_PRODUCT_NAME', 'Наименование');
define('ADMIN_PRODUCT_RATING', 'Рейтинг');
define('ADMIN_PRODUCT_PRICE', 'Цена');
define('ADMIN_PRODUCT_LISTPRICE', 'Старая цена');
define('ADMIN_PRODUCT_INSTOCK', 'На складе');
define('ADMIN_PRODUCT_PICTURE', 'Фотография');
define('ADMIN_PRODUCT_THUMBNAIL', 'Маленькая фотография');
define('ADMIN_PRODUCT_BIGPICTURE', 'Большая фотография');
define('ADMIN_PRODUCT_DESC', 'Описание');
define('ADMIN_PRODUCT_BRIEF_DESC', 'Краткое описание');
define('ADMIN_PRODUCT_SOLD', 'Продано');
define('ADMIN_PRODUCT_COPY', 'Создать копию');
define('ADMIN_PRODUCT_THUMB', 'Дополнительные фото');
define('ADMIN_PRODUCT_ACCOMPANY', 'Сопутствующие товары');
define('ADMIN_PRODUCT_AUTORES', 'Подбор изображений из исходного');
define('ADMIN_COMPANY_ABOUT', 'Реквизиты компании');
define('ADMIN_COMPANY_NAME', 'Полное наименование');
define('ADMIN_COMPANY_ADDRESS', 'Адрес');
define('ADMIN_COMPANY_PHONE', 'Телефон');
define('ADMIN_COMPANY_DIRECTOR', 'Директор');
define('ADMIN_COMPANY_BUH', 'Главный бухгалтер');
define('ADMIN_COMPANY_RS', 'Расчетный счет');
define('ADMIN_COMPANY_INN', 'ИНН');
define('ADMIN_COMPANY_KPP', 'КПП');
define('ADMIN_COMPANY_BANK', 'Банк');
define('ADMIN_COMPANY_BANK_KOR', 'к/с');
define('ADMIN_COMPANY_BANK_BIK', 'БИК');

// Переименование админ файлов
define('ADMIN_FILE', 'Имя админ файла:');
define('ADMIN_FILE_ACCESS', 'Имя админ файла 2 (форма авторизации):');
define('ADMIN_FILE_INFO', '<img src="./images/zn.png" style="float: left;padding-right: 10px;">Если вы видите ошибки при переименовании админ файла (красные надписи под полями ввода), введите новые имена, сохраните, а потом в ручную переименуйте указанные в ошибках файлы, иначе после обновления странички вы не сможете попасть в админку.');
define('ADMIN_MSG0', "<font color='red'>Для переименования файла, нужно в ручную переименовать файл ".CONF_ADMIN_FILE.".</font>");
define('ADMIN_MSG1', "<font color='red'>Для переименования файла, нужно в ручную переименовать файл ".CONF_ADMIN_FILE_ACCESS.".</font>");
define('ADMIN_MSG2', "<font color='red'>Для переименования админ файлов, нужно в ручную править файл .htaccess а именно строчки<br /><b>#administration url's<br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RewriteRule admin$ admin.php [L]<br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RewriteRule admin/$ admin.php [L]</b><br /> или выставьте ему права на запись 0666.</font>");

define('ADMIN_PRODUCT_CHARACTER', 'Дополнительные цены по характеристике');
define('ADMIN_PRODUCT_CHARACTER_ON', '(вкл.)');
define('ADMIN_CHARACTER', 'Дополнительные характеристики');
define('ADMIN_CHARACTER_DESC', 'Тип характеристики');
define('ADMIN_CHARACTER_VAL', 'Значение');

define('ADMIN_TAGS_NAME', 'Метки');
define('ADMIN_TAGS_SHOW_COUNT', 'Максимальное количество тегов');
define('ADMIN_TAGS_SHOW_VIEW', 'Использовать Flash при показе тегов');
define('PRODUCTS_TAGGED', 'Продукты с тегом');
define('CUSTOMER_EMAIL', 'Email:');
define('CUSTOMER_FIRST_NAME', 'Имя:');
define('CUSTOMER_LAST_NAME', 'Фамилия:');
define('CUSTOMER_ZIP', 'Почтовый индекс:');
define('CUSTOMER_STATE', 'Область:');
define('CUSTOMER_COUNTRY', 'Страна:');
define('CUSTOMER_CITY', 'Город:');
define('CUSTOMER_ADDRESS', 'Адрес:');
define('CUSTOMER_COMMENT', 'Комментарий:');
define('CUSTOMER_PHONE_NUMBER', 'Телефон:');

define('EMAIL_CUSTOMER_COMMENT', 'Комментарий заказчика:');


define('ADMIN_PICTURE_NOT_UPLOADED', '(фотография не загружена)');

//sort order

define('ADMIN_SORT', 'Параметры сортировки');
define('ADMIN_SORT_CATEGORY', 'Сортировать категории по');
define('ADMIN_SORT_PRODUCT', 'Сортировать товары по');
define('ADMIN_SORT_BY_CATEGORY', 'номеру категории');
define('ADMIN_SORT_BY_PRODUCTS_COUNT', 'количеству товаров');
define('ADMIN_SORT_BY_PRODUCT', 'ID продукта');
define('ADMIN_SORT_BY_NAME', 'названию');
define('ADMIN_SORT_BY_SOLD', 'продажам');
define('ADMIN_SORT_BY_INSTOCK', 'наличию');
define('ADMIN_SORT_BY_PRICE', 'цене');
define('ADMIN_SORT_BY_VOTES', 'популярности');
define('ADMIN_SORT_BY_CODE', 'Артикулу');
define('ADMIN_SORT_ASC', 'возрастанию');
define('ADMIN_SORT_DESC', 'убыванию');

define('ADMIN_SORT_BY_NAME_ASC', 'Имени: А - Я');
define('ADMIN_SORT_BY_NAME_DESC', 'Имени: Я - А');
define('ADMIN_SORT_BY_PRICE_DESC', 'Цене: Выше - Ниже');
define('ADMIN_SORT_BY_PRICE_ASC', 'Цене: Ниже - Выше');
define('ADMIN_SORT_BY_IN_STOCK_ASC','Наличие Меньше - Больше');
define('ADMIN_SORT_BY_RATING', 'Популярности');
define('ADMIN_SORT_BY_IN_STOCK', 'Наличие Больше - Меньше');

define('SORT', 'cортировать по');

define('ADMIN_REVIEW_WRITE', 'Оставлять отзывы');
define('ADMIN_REVIEW_LINK', 'Разрешать ссылки в отзывах');
define('ADMIN_REVIEW_MODER', 'Модерация отзывов'); 
define('ADMIN_REVIEW_GOOD','Одобрить комментарий');  



//errors

define('ERROR_FAILED_TO_UPLOAD_FILE', '<b><font style="color: #F00">Не удалось закачать файл на сервер. Убедитесь,<br>что включены права на создание файлов на сервере в папке products_pictures/</font></b>');
define('ERROR_CANT_FIND_REQUIRED_PAGE', '<h4>Извините, запрашиваемый документ не был найден на сервере</h4>');
define('ERROR_INPUT_EMAIL', 'Пожалуйста, введите email');
define('ERROR_INPUT_PHONE', 'Пожалуйста, введите номер телефона');
define('ERROR_INPUT_NAME', 'Пожалуйста, введите Ваши ФИО');
define('ERROR_INPUT_COUNTRY', 'Пожалуйста, введите страну');
define('ERROR_INPUT_CITY', 'Пожалуйста, введите название города');
define('ERROR_INPUT_ZIP', 'Пожалуйста, введите почтовый индекс');
define('ERROR_INPUT_STATE', 'Пожалуйста, введите область');
define('ERROR_FILL_FORM', 'Пожалуйста, заполните все поля');
define('ERROR_WRONG_PASSWORD', 'Неверный старый пароль');
define('ERROR_PASS_CONFIRMATION', 'Неверное повторение пароля');
define('ERROR_INPUT_LETTERSONLY','Пожалуйста, введите только русские и латинские буквы!');
define('ERROR_FLASH_REQUARED', '<p>Облако тегов WP Cumulus, требует для просмотра <![CDATA[<noindex>]]><a href="http://www.adobe.com/go/getflashplayer" target="_blank" rel="nofollow">Flash Player 9</a><![CDATA[</noindex>]]> или выше.</p>');

//questions

define('QUESTION_DELETE_PICTURE', 'Удалить фотографию?');
define('QUESTION_DELETE_CONFIRMATION', 'Удалить?');

//emails
define('EMAIL_ADMIN_ORDER_NOTIFICATION_SUBJECT', 'Новый заказ!');
define('EMAIL_CUSTOMER_ORDER_NOTIFICATION_SUBJECT', 'Ваш заказ');
define('EMAIL_MESSAGE_PARAMETERS', 'Content-Type: text/plain; charset="'.DEFAULT_CHARSET.'"');
define('EMAIL_HELLO', 'Здравствуйте');
define('EMAIL_SINCERELY', 'С уважением');
define('EMAIL_THANK_YOU_FOR_SHOPPING_AT', 'Спасибо за Ваш выбор');
define('EMAIL_ORDER_WILL_BE_SHIPPED_TO', 'Ваш заказ будет доставлен по адресу');
define('EMAIL_OUR_MANAGER_WILL_CONTACT_YOU', 'Мы свяжемся с Вами для подтверждения заказа в ближайшее время.');

//warnings

define('WARNING_DELETE_INSTALL_PHP', '<div class="top_err">Файл <b>install.php</b> не удален из директории со скриптами LegoSP. Вам необходимо удалить его вручную.</div>');
define('WARNING_DELETE_FORGOTPW_PHP', '<div class="top_err">Файл <b>forgot_password.php</b> не удален из директории со скриптами Legosp. Вам необходимо удалить его вручную.</div>');
define('WARNING_WRONG_CHMOD', '<div class="top_err">Неверные атрибуты для папки cfg, ее содержимого, а также папок products_pictures и templates_c (либо эти не из этих папок существуют).<br>Необходимо установить правильные атрибуты на них для разрешения (пере)записи файлов в этих папках (как правило, это атрибуты 775).</div>');

//currencies

define('ADMIN_CURRENCY', 'Валюта');
define('ADMIN_CURRENCY_VAL', 'Курс');
define('ADMIN_CURRENCY_AUTO', 'Автоматически пересчитывать Курс валют с сайта ЦБ');
define('ADMIN_CURRENCY_ID_LEFT', 'Обозначение валюты слева от суммы (цены)<br>(например, "$")');
define('ADMIN_CURRENCY_ID_RIGHT', 'Обозначение валюты справа от суммы (цены)<br>(например, "руб.")');
define('ADMIN_CURRENCY_ISO3', 'Трехбуквенный код валюты ISO3<br>(например, USD, EUR, RUR)');
define('ADMIN_CURRENCY_COURSE', 7.7);
define('ADMIN_CURRENCY_DEFAUL', 'Валюта по умолчанию');
define('STRING_CURRENCY', 'Валюта');

//other 

define('PRODUCT_REVIEW_TITLE', 'Отзывы');
define('PRODUCT_REVIEW_NOTIFICATION_SUBJECT', 'Новый отзыв!');
define('PRODUCT_NO_REVIEWS', 'К данному продукту нет отзывов. <br />Может вы оставите первый?');
define('PRODUCT_REVIEW', 'Оставьте отзыв');
define('PRODUCT_REVIEW_HELLO', 'Нам очень важно знать ваше мнение!');
define('PRODUCT_REVIEW_NAME', 'Ваше имя');
define('PRODUCT_REVIEW_EMAIL', 'E-mail');
define('PRODUCT_REVIEW_MESSAGE', 'Отзыв');
define('PRODUCT_REVIEW_WRITE', 'Оставить отзыв');
define('PRODUCT_REVIEW_ALL', 'Все отзывы');
define('PRODUCT_REVIEW_ADD_OK', 'Благодарим Вас \nВаш отзыв успешно отправлен.');
define('PRODUCT_REVIEW_ADD_OK_MODER', 'Благодарим Вас \nВаш отзыв успешно отправлен.\nПосле проверки модератором он будет виден другим пользователям.');
define('PRODUCT_REVIEW_ADD_NOT_LINK', 'Размещение ссылок в отзывах запрещено.');


define('PRODUCT_ADMIN_REVIEW_NAME', 'Посетитель');
define('PRODUCT_ADMIN_REVIEW_EDIT', 'Редактировать');

//mysql

define('ADMIN_MYSQL_DB', 'База данных MySQL');
define('ADMIN_MYSQL_DB_SETTINGS', 'Настройки');
define('ADMIN_MYSQL_DB_OP', 'Операции');
define('ADMIN_MYSQL_DB_HOST', 'Хост');
define('ADMIN_MYSQL_DB_USER', 'Пользователь');
define('ADMIN_MYSQL_DB_PASS', 'Пароль');
define('ADMIN_MYSQL_DB_NAME', 'Имя базы MySQL');
define('ADMIN_MYSQL_CHARSET', 'Кодировка базы данных');
define('ADMIN_MYSQL_DB_OPTIMIZE', 'Оптимизировать базу');
define('ADMIN_MYSQL_DB_EXPORT', 'Экспорт базы');
define('ADMIN_MYSQL_DB_IMPORT', 'Импортировать базу');
define('ADMIN_MYSQL_DB_TEST', 'Проверить базу');
define('ADMIN_MYSQL_DB_WARNING', '<font style="color: #F00"><b>Внимание!</b></font> Не рекомендуется
редактировать данный раздел самостоятельно. Для редактирование данного
раздела обратитесь к администратору.');
define('ADMIN_MYSQL_DB_IMPORT_COMPLITE', 'Импорт успешно завершен, запросов выполнено:');
define('ADMIN_MYSQL_DB_IMPORT_ERROR', 'Ошибка! Неверная структура файла. (запрос # ');


//votes

define('STRING_VOTE', 'Ваше мнение');
define('ADMIN_VOTES', 'Голосование');
define('ADMIN_VOTES_NAME', 'Заголовок');
define('ADMIN_VOTES_ALL', 'Все темы');
define('ADMIN_VOTES_ARCHIVE', 'Архив тем');
define('ADMIN_VOTES_QUEST', 'Вопрос');
define('ADMIN_VOTES_NOW', 'Текущая тема');
define('ADMIN_VOTES_COMPLITE', 'В архив');
define('ADMIN_VOTES_INCOMPLITE', 'Сделать текущим');
define('ADMIN_VOTES_ANSER', 'Голосов');
define('ADMIN_VOTES_RESULT', 'Результаты');
define('ADMIN_VOTES_IP', 'Запретить голосовать более раза с одного IP');
define('ADMIN_VOTES_TOMAIL', 'Отсылать сообщения администратору');
define('ADMIN_VOTES_SEND', 'Голосовать!');

//admin

define('ADMIN_SYSTEM', 'Система');


//reports
define('ADMIN_REPORTS', 'Отчеты');
define('ADMIN_REPORTS_SALES', 'Отчёт по продуктам');
define('ADMIN_REPORTS_CUSTOMER', 'Покупатель');
define('ADMIN_REPORTS_NAME_PRODUCTS', 'Наименование товара');
define('ADMIN_REPORTS_ALL', 'Все');



//order

define('ORDER_ORDERER', 'Заказчик');
define('ORDER_ADRESS', 'Адрес');
define('ORDER_PHONE', 'Телефон');
define('ORDER_EMAIL', 'e-mail');
define('ORDER_STATUSES', 'Статусы заказов');
define('ORDER_STATUS', 'Статус');



//aux pages

define('ADMIN_AUX_ADD', 'Добавить');
define('ADMIN_AUX_ALL', 'Все страницы');
define('ADMIN_AUX_NAME', 'Название');
define('ADMIN_AUX_TEXT', 'Содержание');
define('ADMIN_AUX_DELETE', 'Удалить');
define('ADMIN_AUX_WARNING', 'Эта страница будет доступна по адресу ');

// on-line

define('ADMIN_ONLINE', 'On-line №');
define('STRING_ONLINE', 'Консультант');
define('ADMIN_ONLINE_SHOW_NAME', 'Показывать имя');
define('ADMIN_ONLINE_TYPE', 'Связь');
define('ADMIN_ONLINE_ON', 'Выводить online контакты');

define('ADMIN_ONLINE_WARNING', '<font style="color: #F00"><b>Внимание!</b></font> Для корректного отображения
online статуса необходимо разрешить отображать статус в интернете во всех приложениях (настройки -> безопасность).');

//share
define('ADMIN_SHARE', 'Cкидки');
define('ADMIN_SHARE_DISCOUNT', 'Скидки');
define('ADMIN_SHARE_CUPONS', 'Купоны');
define('ADMIN_SHARE_CUPON_FOR', 'Купон на скидку');

//shiping
define('ADMIN_SHIPPING', 'Доставка');
define('ADMIN_SHIPPING_WHERE', 'Доставка в');
define('STRING_SHIPPING_NONE', '--- Самовывоз ---');
define('STRING_SHIPPING_SELF', 'Самовывоз');
define('STRING_SHIPPING', 'Доставка');
define('STRING_SHIPPING_FREE', 'Бесплатно');
define('ADMIN_SHIPPING_CONF', 'Настройки');
define('ADMIN_SHIPPING_FREE', 'Бесплатная доставка от суммы');

//managers
define('ADMIN_MANAGER', 'Операторы');

define('ADMIN_MANAGER_NAME', 'Имя');
define('ADMIN_MANAGER_LOGIN', 'Логин');
define('ADMIN_MANAGER_PASS', 'Пароль');
define('ADMIN_MANAGER_ACCESS', 'Права');
define('ADMIN_MANAGER_EMAIL', 'e-mail');
define('ADMIN_MANAGER_ACCESS_1', 'Менеджер');
define('ADMIN_MANAGER_ACCESS_2', 'Оператор');
define('ADMIN_MANAGER_ACCESS_3', 'Администратор');
define('ADMIN_MANAGER_NAME_MAIL', 'Ваш личный менеджер');
define('ADMIN_MANAGER_MAIL', 'Ответственный');

//import
define('ADMIN_IMPORT', 'Импорт/Export');
define('ADMIN_IMPORT_BUTTON', 'Импорт');
define('ADMIN_IMPORT_FILE', 'XLS файл');
define('ADMIN_IMPORT_RESULT', 'Успешно! Запросов выполнено: ');
define('ADMIN_IMPORT_TITLE', 'Добавление и обновление товаров');
define('ADMIN_IMPORT_METHOD', 'Метод импорта');


//compare
define('COMPARE', 'Сравнение выбранных товаров');
define('COMPARE_NOT', 'Нечего сравнивать, для сравнения нужно выбрать хотя бы 2 товара!');
define('Back_to_compare','Перейти к сравнению');
define('COMPARE_CLEAR', 'Очистить весь список');
define('ADD_COMPARE', 'Добавить товар к сравнению.');


//live_couns

define('ADMIN_LIVE_COUNTS', 'Счетчики');
define('ADMIN_LIVE_COUNTS_WARNING', '<font style="color: #F00"><b>Внимание!</b></font> Для корректного отображения
счетчиков весь код должен быть заключен в {literal}{/literal}');

//payments
define('STRING_GO_PAYMENTS', 'Перейти к оплате');
define('STRING_PAYMENT_SELECT', 'Выберите способ оплаты');
define('STRING_PAY', 'Оплатить');
define('STRING_PAY_ADDPRICE', 'Внимание! При выбранном способе оплаты взимается комиссия системы в размере ');
define('ADMIN_PAYMENTS', 'Системы оплаты');
define('ADMIN_PAYMENTS_ON', 'Вкл.');
define('ADMIN_PAYMENTS_LOGIN', 'Логин (ID)');
define('ADMIN_PAYMENTS_PASS', 'Пароль');
define('ADMIN_PAYMENTS_ADDPRICE', 'Наценка при оплате (%)');
define('ADMIN_PAYMENTS_NAME', 'Выводить название');
define('ADMIN_QIWI_TIME', 'Время для оплаты');
define('ADMIN_QIWI_CHECKMOBILE', 'Проверка наличия мобильного кошелька');
define('ADMIN_ROBOX_CURR', 'Валюта по умолчанию');
define('ADMIN_INTER_SHOPID', 'Персональный код магазина');
define('ADMIN_SBRF_PRINT', 'Распечатать квитанцию');
define('ADMIN_PAYMENTS_COMMENT', 'Комментарий к платежу по умолчанию');

//redirect
define('ADMIN_REDIRECT', 'Перенаправление');
define('ADMIN_REDIRECT_SETTINGS', 'Настройки перенаправления');
define('ADMIN_REDIRECT_CATEGORY', 'Категории');
define('ADMIN_REDIRECT_PRODUCTS', 'Продукты');
define('ADMIN_REDIRECT_NEWS', 'Новости');
define('ADMIN_REDIRECT_PAGES', 'Статьи');
define('ADMIN_REDIRECT_BRANDS', 'Производители');
define('ADMIN_REDIRECT_TAGS', 'Теги');
define('ADMIN_REDIRECT_INFO', 'Информация');


define('ADMIN_ANY_VALUE','Произвольное значение');


define('ADMIN_TEGS','Теги');

//Admin index
define('ADMIN_ORDERS', 'Заказы');
define('ADMIN_ORDER_ov', 'заказ(ов)');
define('ADMIN_today', 'Сегодня');
define('ADMIN_yesterday', 'Вчера');
define('ADMIN_This_month', 'В этом месяце');
define('ADMIN_only', 'Всего');
define('ADMIN_products','Продукты');
define('ADMIN_total_number_products','Общее количество продуктов');
define('ADMIN_products_for_client',"Из них продаются<br>(доступны в пользовательской части)");
define('ADMIN_category_products','Категорий продуктов');
define('ADMIN_CUST_WELCOME','Привет');
define('ADMIN_CUST_WELCOME_TITLE','Моя учётная запись');
define('ADMIN_STAT_SHOP','Статистика магазина');
define('ADMIN_Thank_you_for_using','Спасибо что используете');
define('Version','Версия 6.3.1');

//Admin menu
define('ADMIN_MENU_categories','Категорию');
define('ADMIN_MENU_aux_pages','Доп. страницу');
define('ADMIN_MENU_console','Консоль');
define('ADMIN_MENU_Index','Главная админки');
define('ADMIN_MENU_go_site','Перейти на сайт');
define('ADMIN_MENU_minimize','Свернуть меню');


/*define('ADMIN_MENU_products_variants','Категорию');
define('ADMIN_MENU_categories','Категорию');
define('ADMIN_MENU_categories','Категорию');*/


//
define('ADMIN_PRICING', 'Ценообразование');
define('ADMIN_PRICING_TITLE', 'Изменение цены');
define('ADMIN_PRICING_PROCECCING', 'Операция');
define('ADMIN_PRICING_VALUE', 'Значение');
define('ADMIN_PRICING_EDIT', 'Изменить все цены на');
define('ADMIN_PRICING_INC', 'Увеличить на');
define('ADMIN_PRICING_DEC', 'Уменьшить на');
define('ADMIN_PRICING_OPTION', 'Дополнительно');
define('ADMIN_PRICING_OPTION_1', 'Изменить только текущие цены');
define('ADMIN_PRICING_OPTION_2', 'Изменить все цены');
define('ADMIN_PRICING_OPTION_3', 'Заменить текущие цены и записать текущие в старую цену');

define('ADMIN_PRODUCTS_VARIANTS_GROUPS', 'Доп. параметры');
define('ADMIN_PRODUCTS_VARIANTS', 'Значения доп. характеристики');
define('ADMIN_ADD_NEW_OPTION', 'Добавить характеристику');
define('ADMIN_ADD_VALUE', 'Добавить возможное значение');
define('ADMIN_TAB_GENERAL', 'Общие');
define('ADMIN_TAB_customparams', 'Доп. характеристики'); 

define('STRING_NEWPRICE', 'Цена с учётом выбранных опций'); 

define('ADVANCED_SEARCH_LINK', 'Расширенный поиск');
define('STRING_ADVANCED_SEACH_TITLE', 'Расширенный поиск');
define('STRING_UNIMPORTANT', 'неважно'); 
define('STRING_PRICE_FROM', 'Цена от'); 
define('STRING_PRICE_TO', 'Цена до'); 
define('STRING_SEARCH_IN_SUBCATEGORIES', 'искать в подкатегориях');  

define('STRING_ACTIONS', 'Действия');
define('STRING_couple','элемент(ов)');         
//useful

//CURRENCY
define('ADMIN_CURRENCY_TYPES', 'Валюты');
define('ADMIN_CURRENCY_NAME', 'Название валюты');
define('ADMIN_CURRENCY_ID', 'Обозначение в магазине');
define('ADMIN_CURRENCY__ISO3', 'Код валюты ISO 3');
define('ADMIN_CURRENCY_EXCHANGERATE', 'Курс');
define('ADMIN_CURRENCY_ADD', 'Добавить');

//Регистрация
define('CUSTOMER_PASSWORD', 'Ваш пароль');
define('CUSTOMER_PASSWORD2', 'Повторите пароль');
define('STRING_LOGIN_INFORMATION', 'Авторизация');
define('STRING_LOGIN_LONG', 'Войти в систему');
define('STRING_LOGIN', 'Войти');
define('STRING_PROFILE', 'Профиль');
define('STRING_REGISTER', 'Регистрация');
define('CUST_WELCOME', 'Приветствуем, ');
define('CUST_GOOT_REG', 'Спасибо за регистрацию! Вы успешно зарегиcтированы.');
define('CUST_LOGOUT', 'Выйти');
define('LOGIN_TITLE', 'Вход для покупателей');
define('CUST_LOGIN', 'Email');
define('CUST_PASS', 'Пароль');
define('ERROR_CUST_PASSWORD', 'Вы ввели неверный пароль');
define('ERROR_NO_SUCH_EMAIL', 'Пользователь с таким email не зарегистрирован');
define('STRING_REGISTER_INFORMATION', 'Регистрация покупателя');
define('EMAIL_CUSTOMER_REGISTER_NOTIFICATION_SUBJECT', 'Ваши регистрационные данные');
define('STRING_SHIPPING_ADDRESS', 'Адрес доставки');
define('NOTIFICATION_COMMENT_SUBJECT', 'Новый комментарий');
define('ERROR_EMAIL_ALREADY_EXISTS', 'Покупатель с таким email адресом уже зарегистрирован.');
define('REMEMBER_PASSWORD', 'Напомнить пароль?');
define('STRING_SEND_REGISTER_FORM', 'Зарегистрироваться');
define('STRING_OTHE_DATE', 'Заполните не достаюшие данные');
define('admin_menedger_deny','Запретить использование для пользователя: ');
define('ADMIN_ORDERS_SEARCHP_DESC','Искать можно как по имени товара так и по артикулу');

define('prdset_min_qunatity_to_order','Минимальный заказ продукта (штук)');
define('prdset_minimal_order_quantity','Минимальный заказ');
define('str_items','шт.');

//Дополнительные родительские категории 
define('ADMIN_CATEGORY_APPENDED_PARENTS', 'Дополнительные родительские категории');

//История заказов
define('ORDERS_HISTORY', 'История заказов');

define('ORDERS_MIN_PRODUCT','Количество товара должно быть не меньше '); 

define('hidden','Отоброжение'); 
define('not_hidden','отображать всем'); 
define('not_hidden_avtor','отображать только для зарегетрированных'); 
define('not_hidden_user','отображать не для зарегестрированных'); 


define('INDEX_cart', 'Ваши заказы');
define('LegoSP_title', 'LegoSP - движок интернет магазин');
define('menu_uslugi', 'Наши услуги');
define('menu_forum', 'Форум');
define('menu_doc', 'Документация');
define('menu_katalog', 'Дополнения');
define('author', 'Автор');
define('Recent_Additions', 'Свежие дополнения');
define('Our_News', 'Наши новости');
define('Publications','Публикации');
?>