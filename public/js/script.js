$(document).ready(function () {
//******************************************************************************
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

    $('.row_set').click(function () {
        console.log('__check_row___');
        $(this).toggleClass('check_row'); 
   });
//******************************************************************************
//Загрузка запчастей
//Запрет ввода данных до нажатия соответствующей галочки
    $(function () {
        $('.spare_check').click(function () {
            var id = $(this).val();
            console.log(id);
            console.log('__check_row_toggle___');

            $('.check_spare_'+id).prop('disabled', !$('.spare_check_'+id ).prop( "checked" ));

            console.log($('.spare_check_'+id ).prop( "checked" ));
        });
    });


});