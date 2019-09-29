Магазин с кассами
(тестовое задание)

Представим некий абстрактный продуктовый магазин (типа Семья или Ашан), в котором имеется несколько касс для оплаты. 

Количество рабочих касс варьируется в зависимости от загруженности. 
Если ни одна касса не работает и приходит покупатель — тогда открывается любая из касс
Если на кассе становится 5 (и более) ожидающих покупателей - открывается ещё одна касса (при условии что есть доступные) 
Если на кассу некоторое время не приходят покупатели, она закрывается

В магазин приходят покупатели, которые выбирают несколько продуктов (от 1 до 10). Потом они встают в очередь на кассу, где меньше всего людей. 

Чтобы кассиру отпустить покупателя, нужно:
пробить все продукты (будем считать что пробивка каждого продукта занимает одинаковое время X) 
и принять оплату (время Y)

Задача: 
Смоделировать рабочий день магазина. 
В самом начале дня кассы не работают. Появляется первый покупатель, потом следующий. Постепенно частота прихода покупателей нарастает (в случайном порядке) и доходит до пика. Далее идет на спад до нуля. 
Нужно сделать вывод информации в каждый рабочий час: сколько касс работает и какой размер очередей на каждой из них. 