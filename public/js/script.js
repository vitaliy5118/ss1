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
        //var coordinates;
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
                data = $.parseJSON(data);
                var address = utfConvertData(data);
                console.log('address=');
                console.log(address);

                //получаем координаты торговой точки точки
                var geocoder;
                geocoder = new google.maps.Geocoder();
                geocoder.geocode({'address': address}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        var coordinates = results[0].geometry.location;
                        console.log(coordinates);

                        if (confirm('Координаты найдены!\n\Долгота: ' + coordinates.lng() + '\n\Широта: ' + coordinates.lat() + '\n\Сохранить данные?')) {
                            console.log('Сохраняем');
                            console.log('.........');
                            var coord = {'id': id, 'lng': coordinates.lng(), 'lat': coordinates.lat()};
                            // сохраняема данные в базу данных
                            $.ajax({
                                url: '/outlets/save/',
                                type: 'post',
                                data: {
                                    coordinates: coord
                                },
                                success: function (data2) {
                                    
                                    console.log('Сохранено');
                                    console.log(data2);
                                    $('#cordsp_' + id).remove();
                                    $('<span id="cordsp_' + id + '"><b>Долгота: </b>' + coordinates.lng() +
                                            '<b> Широта: </b>' + coordinates.lat() + '</span>').prependTo('#cord_' + id);
                                    $('#show_' + id).prop("disabled", false);
                                    //редактируем кнопку "На карту"
                                    var show = $('#show_'+id).prop("checked");
                                    if (show === true) {
                                       show=''; 
                                    } else {
                                       show = 'disabled';
                                    }
                                    console.log('show = '+show);
                                    $('#to_map_' + id).remove();
                                    $('<button style="margin-left: 4px;" type="button" class="btn btn-default" id="to_map_'+ id +'"'+ show +'>На карту</button>').insertAfter('#but_man_' + id);
                                    $('#to_map_'+ id).bind('click', function(){
                                        location.href='/outlets/index/lng/'+ coord.lng +'/lat/'+ coord.lat;
                                    }); 
                                    console.log('Сохранено2');
                                }
                            });
                        } else {
                            console.log('Не сохранено');
                        }
                    } else {
                        alert("Координаты не найдены! Статус: " + status + "\n\Уточните адресс или введите координаты вручную!");
                    }
                });
                console.log('___Finish___');

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
                        success: function (answer) {
                            console.log('Сохранено');
                            console.log(answer);
                            $('#cordsp_' + id).remove();
                            $('<span id="cordsp_' + id + '"><b>Долгота: </b>' + lat +
                                    '<b> Широта: </b>' + lng + '</span>').prependTo('#cord_' + id);
                            $('#show_' + id).prop("disabled", false);
                            //редактируем кнопку "На карту"
                            var show = $('#show_' + id).prop("checked");
                            if (show === true) {
                                show = '';
                            } else {
                                show = 'disabled';
                            }
                            console.log('show = ' + show);
                            $('#to_map_' + id).remove();
                            $('<button style="margin-left: 4px;" type="button" class="btn btn-default" id="to_map_'+ id +'" '+ show +'>На карту</button>').insertAfter('#but_man_' + id);
                            $('#to_map_' + id).bind('click', function () {
                                location.href = '/outlets/index/lng/' + coordinates.lng + '/lat/' + coordinates.lat;
                            }); 
                            console.log('Сохранено2');
                        }
                    });
                }
            }
        }
        e.preventDefault();
    });
//******************************************************************************
// включение/выключение отображения торговой точки на карте
    $('.show_mark').click(function () {
        console.log('_show_mark_process_start_');
        console.log('_check_id_');
        var id = $(this).val();
        var show = $(this).prop("checked");
        console.log('id=' + id);
        console.log('show=' + show);
        if (show === true) {
            show = {'id': id, 'show': 'OK'};
            $('#to_map_' + id).removeAttr("disabled"); //включить кнопку перехода на метку на карте   
            $('.gradeA #' + id).css('color', '#8FBC8F'); //меняем цвет значка  
        } else {
            show = {'id': id, 'show': 'NO'};
            $('#to_map_' + id).attr('disabled', 'disabled'); //выключить кнопку перехода на метку на карте
            $('.gradeA #' + id).css('color', '#E9967A'); //меняем цвет значка
        }
        console.log('show=' + show);
        console.log('saving....');

        // сохраняема данные в базу данных
        $.ajax({
            url: '/outlets/saveshow/',
            type: 'post',
            data: {
                show: show
            },
            success: function (answer) {
                console.log(answer);
            }
        });
        console.log('finish');
    });
//******************************************************************************
// включение/выключение отображения торговой точки на карте
    $('.mark_color').click(function () {
        console.log('_mark_color_process_start_');
        console.log('_check_id_');
        var id = $(this).attr("id");
        console.log('id=' + id);
        console.log('_check_color_');
        var color = $(this).val();
        console.log('color=' + color);
        if (id && color) {
            color = {'id': id, 'color': color};
            console.log('color=' + color);
            console.log('saving....');
            // сохраняема данные в базу данных
            $.ajax({
                url: '/outlets/savecolor/',
                type: 'post',
                data: {
                    color: color
                },
                success: function (answer) {
                    console.log(answer);
                }
            });
        } else {
            alert("error");
        }
        console.log('finish');
    });

    //функция обратной перекодировки символов
    function utfConvertData(data) {

        var str = data;
        var combining = /[\u0300-\u036F]/g;

        return str.normalize('NFKD').replace(combining, '');
    }
//******************************************************************************
});


