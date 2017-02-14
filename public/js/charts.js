console.log('charts module start');

// сохраняема данные в базу данных
$.ajax({
    url: '/statistic/getchartrepair/',
    type: 'post',
    success: function (data) {
        console.log(data);
        data = $.parseJSON(data);
        
        var data_array = [];
        
         for (var key in data) {
        // этот код будет вызван для каждого свойства объекта
        // ..и выведет имя свойства и его значение
            data_array.push({y: key, a: data[key]});
        }
        console.log(data_array);
        
        new Morris.Line({
        element: 'all_repairs_chart',
        data: data_array,
        xkey: 'y',
        ykeys: ['a'],
        labels: ['всего ']
    });
    }
});
// сохраняема данные в базу данных
$.ajax({
    url: '/statistic/getchart/',
    type: 'post',
    data: {
        parameter: 'name'
    },
    success: function (data) {
        //console.log(data);
        data = $.parseJSON(data);
        
        var data_array = [];
        
         for (var key in data) {
        // этот код будет вызван для каждого свойства объекта
        // ..и выведет имя свойства и его значение
            data_array.push({y: key, a: data[key]});
        }
        console.log(data_array);
        
        new Morris.Bar({
        element: 'all_devices_chart',
        data: data_array,
        xkey: 'y',
        ykeys: ['a'],
        labels: ['всего '],
        resize: true
    });
    }
});

// сохраняема данные в базу данных
$.ajax({
    url: '/statistic/getchart/',
    type: 'post',
    data: {
        parameter: 'type'
    },
    success: function (data) {
        //console.log(data);
        data = $.parseJSON(data);
        
        var data_array = [];
        
         for (var key in data) {
        // этот код будет вызван для каждого свойства объекта
        // ..и выведет имя свойства и его значение
            data_array.push({y: key, a: data[key]});
        }
        console.log(data_array);
        
        new Morris.Bar({
        element: 'all_type_chart',
        data: data_array,
        xkey: 'y',
        ykeys: ['a'],
        labels: ['всего '],
        resize: true
    });
    }
});
// сохраняема данные в базу данных
$.ajax({
    url: '/statistic/getchart/',
    type: 'post',
    data: {
        parameter: 'status'
    },
    success: function (data) {

        data = $.parseJSON(data);
     
        console.log(data);
        
        var data_array = [];
        
         for (var key in data) {
        // этот код будет вызван для каждого свойства объекта
        // ..и выведет имя свойства и его значение
            data_array.push({y: key, a: data[key]});
        }
        console.log(data_array);
        
        new Morris.Bar({
        element: 'all_status_chart',
        data: data_array,
        xkey: 'y',
        ykeys: ['a'],
        labels: ['всего '],
        resize: true
    });
    }
});
// сохраняема данные в базу данных
$.ajax({
    url: '/statistic/getchart/',
    type: 'post',
    data: {
        parameter: 'owner'
    },
    success: function (data) {

        data = $.parseJSON(data);
     
        console.log(data);
        
        var data_array = [];
        
         for (var key in data) {
        // этот код будет вызван для каждого свойства объекта
        // ..и выведет имя свойства и его значение
            data_array.push({y: key, a: data[key]});
        }
        console.log(data_array);
        
        new Morris.Bar({
        element: 'all_owner_chart',
        data: data_array,
        xkey: 'y',
        ykeys: ['a'],
        labels: ['всего '],
        resize: true
    });
    }
});
// сохраняема данные в базу данных
$.ajax({
    url: '/statistic/getchart/',
    type: 'post',
    data: {
        parameter: 'user'
    },
    success: function (data) {

        data = $.parseJSON(data);
     
        console.log(data);
        
        var data_array = [];
        
         for (var key in data) {
        // этот код будет вызван для каждого свойства объекта
        // ..и выведет имя свойства и его значение
            data_array.push({y: key, a: data[key]});
        }
        console.log(data_array);
        
        new Morris.Bar({
        element: 'all_user_chart',
        data: data_array,
        xkey: 'y',
        ykeys: ['a'],
        labels: ['всего '],
        resize: true
    });
    }
});


console.log('finish');



