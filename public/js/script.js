$(document).ready(function () {
    console.log('js_ready');
//******************************************************************************
//автоматическое увеличение картинки при наведении мышки
    $("tbody tr td p img#war_img").hover(function () {
        console.log('oh');
        // при наведении курсора мыши
        $(this).parent().parent().css("z-index", 1);
        $(this).animate({
            height: "100",
            width: "100",
            left: "-=50",
            top: "-=50"
        }, "fast");
    }, function () {
        // hover out
        $(this).parent().parent().css("z-index", 0);
        $(this).animate({
            height: "50",
            width: "50",
            left: "+=50",
            top: "+=50"
        }, "fast");
    });
//******************************************************************************

    $('.row_set').click(function () {
        console.log('__check_row___');

        $(this).toggleClass('check_row');
    });
//******************************************************************************

//******************************************************************************

    $('.shop').click(function () {
        console.log('__check_shop___');
        $('.row_set_' + $(this).attr('id')).toggleClass('shop_table');
    });
//******************************************************************************


//******************************************************************************
//Загрузка запчастей
//Запрет ввода данных до нажатия соответствующей галочки
        $('.spare_check').click(function () {
            console.log('__check_row_toggle___');
            var id = $(this).val();
            console.log(id);

            $('.check_spare_' + id).prop('disabled', !$('.spare_check_' + id).prop("checked"));

            console.log($('.spare_check_' + id).prop("checked"));
        });
//******************************************************************************
// автоматическое обнаружение координат по адресу
    $('.autofind').click(function (e) {
        console.log('_autofind_process_start_');
        console.log('_check_id_');
        var id = $(this).val();
        var coordinates;
        console.log('id=' + id);
        console.log('Ok');

        $.ajax({//отправка данных
            url: '/outlets/autofind/',
            type: 'post',
            data: {
                id: id
            },
            success: function (data) {
                console.log('___success___');
                console.log('____JSON____');
                console.log(data);
                data = $.parseJSON(data);
                console.log('___Finish___');
                if (data['lng'] != 0 && data['lat'] != 0) {
                    if (confirm('Координаты найдены!\n\Долгота: ' + data['lng'] + '\n\Широта: ' + data['lat'] + '\n\Сохранить данные?')) {
                        console.log('Сохраняем');
                        console.log('.........');
                        coordinates = {'id': id, 'lng': data['lng'], 'lat': data['lat']};
                        // сохраняема данные в базу данных
                        $.ajax({
                            url: '/outlets/save/',
                            type: 'post',
                            data: {
                                coordinates: coordinates
                            },
                            success: function (data2) {

                                console.log('Сохранено');
                                console.log(data2);
                                $('#cordsp_' + id).remove();
                                $('<span id="cordsp_'+ id+'"><b>Долгота: </b>' + data['lat'] +
                                        '<b> Широта: </b>' + data['lng'] + '</span>').prependTo('#cord_' + id);
                            }
                        });
                    } else {
                        console.log('Не сохранено');
                    }
                } else {
                    alert('   Координаты не найдены\n\Введите координаты вручную\n\      или уточните адресс');
                }
            }
        });




        e.preventDefault();
    });
//******************************************************************************
    $('.manual').click(function (e) {
        console.log('_manual_process_start_');
        console.log('_check_id_');
        var id = $(this).val();
        var coordinates;
        console.log('id=' + id);
        console.log('Ok');
        var result;
        var lng = prompt("Введите Долготу:\n\пример: 50.474881 ");
        if (lng) {
            console.log('lng=' + lng);
            var lat = prompt("Введите Широту:\n\пример: 30.474881 ");
            if (lat) {
                console.log('lat=' + lat);
                result = confirm('Сохранить данные? \n\Долгота=' + lat + ' Широта=' + lng);
                if (result) {
                    coordinates = {'id': id, 'lng': lng, 'lat': lat};
                    // сохраняема данные в базу данных
                    $.ajax({
                        url: '/outlets/save/',
                        type: 'post',
                        data: {
                        coordinates: coordinates
                        },
                        success: function (data2) {
                            console.log('Сохранено');
                            console.log(data2);
                            $('#cordsp_' + id).remove();
                            $('<span id="cordsp_'+ id+'"><b>Долгота: </b>' + lat +
                                    '<b> Широта: </b>' +lng + '</span>').prependTo('#cord_' + id);
                        }
                    });
                }
            }
        }
        e.preventDefault();
    });
//******************************************************************************
});


