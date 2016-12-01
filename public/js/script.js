$(document).ready(function () {
    console.log('js_ready');
//******************************************************************************
//�������������� ���������� �������� ��� ��������� �����
    $("tbody tr td p img#war_img").hover(function () {
        console.log('oh');
        // ��� ��������� ������� ����
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
//�������� ���������
//������ ����� ������ �� ������� ��������������� �������
        $('.spare_check').click(function () {
            console.log('__check_row_toggle___');
            var id = $(this).val();
            console.log(id);

            $('.check_spare_' + id).prop('disabled', !$('.spare_check_' + id).prop("checked"));

            console.log($('.spare_check_' + id).prop("checked"));
        });
//******************************************************************************
// �������������� ����������� ��������� �� ������
    $('.autofind').click(function (e) {
        console.log('_autofind_process_start_');
        console.log('_check_id_');
        var id = $(this).val();
        var coordinates;
        console.log('id=' + id);
        console.log('Ok');

        $.ajax({//�������� ������
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
                    if (confirm('���������� �������!\n\�������: ' + data['lng'] + '\n\������: ' + data['lat'] + '\n\��������� ������?')) {
                        console.log('���������');
                        console.log('.........');
                        coordinates = {'id': id, 'lng': data['lng'], 'lat': data['lat']};
                        // ���������� ������ � ���� ������
                        $.ajax({
                            url: '/outlets/save/',
                            type: 'post',
                            data: {
                                coordinates: coordinates
                            },
                            success: function (data2) {

                                console.log('���������');
                                console.log(data2);
                                $('#cordsp_' + id).remove();
                                $('<span id="cordsp_'+ id+'"><b>�������: </b>' + data['lat'] +
                                        '<b> ������: </b>' + data['lng'] + '</span>').prependTo('#cord_' + id);
                            }
                        });
                    } else {
                        console.log('�� ���������');
                    }
                } else {
                    alert('   ���������� �� �������\n\������� ���������� �������\n\      ��� �������� ������');
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
        var lng = prompt("������� �������:\n\������: 50.474881 ");
        if (lng) {
            console.log('lng=' + lng);
            var lat = prompt("������� ������:\n\������: 30.474881 ");
            if (lat) {
                console.log('lat=' + lat);
                result = confirm('��������� ������? \n\�������=' + lat + ' ������=' + lng);
                if (result) {
                    coordinates = {'id': id, 'lng': lng, 'lat': lat};
                    // ���������� ������ � ���� ������
                    $.ajax({
                        url: '/outlets/save/',
                        type: 'post',
                        data: {
                        coordinates: coordinates
                        },
                        success: function (data2) {
                            console.log('���������');
                            console.log(data2);
                            $('#cordsp_' + id).remove();
                            $('<span id="cordsp_'+ id+'"><b>�������: </b>' + lat +
                                    '<b> ������: </b>' +lng + '</span>').prependTo('#cord_' + id);
                        }
                    });
                }
            }
        }
        e.preventDefault();
    });
//******************************************************************************
});


