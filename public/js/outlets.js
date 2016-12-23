// ������ ���������� ����� �� �����
//******************************************************************************
// ��������� ������ �� ���� ������
console.log('module start');

function initMap() {

    console.log('get mark data');
    $.ajax({
        url: '/outlets/getmarkdata/',
        type: 'post',
        success: function (markdata) {
            markdata = $.parseJSON(markdata);
            if (markdata) {
                console.log('loading succesfull');
                console.log('markdata=' + markdata);
            } else {
                console.log('error! no data!');
            }
            // ��������� ������ �����
            var map;
            // ������ ��������
            //�������
            var lat = parseFloat($('#lat_point').val());
            var lng = parseFloat($('#lng_point').val());
            var zoom = parseInt($('#zoom_point').val());
            var latlng = new google.maps.LatLng(lat, lng);
            var myOptions = {
                zoom: zoom,
                center: latlng,
                mapTypeControl: true,
                mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                },
                navigationControl: true,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            console.log('��������� �����');
            map = new google.maps.Map(document.getElementById("map"), myOptions);
            console.log('����� ���������');

            //var pinColor = "FE7569";
            //var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor);

            var marker;
            var i;

            // ��������� ���� �������� ������ �� �����
            for (i = 1; i <= markdata['i']; i++) {

                var address = utfConvertData(markdata[i]['adress']);
                lat = parseFloat(markdata[i]['lat']);
                lng = parseFloat(markdata[i]['lng']);
                console.log('�������� ����� �' + i);
                console.log('Lat=' + lat + ' Lng=' + lng);
                var myLatLng = {lat: lat, lng: lng};

                var mscontent = '<div><b> �����: ' + markdata[i]['city'] + ', �����: ' 
                        + markdata[i]['adress'] + '</b><p>S/N: ' + markdata[i]['number'] 
                        + ' ��������: ' + utfConvertData(markdata[i]['name']) 
                        + '</p>'+'<p>������� �: <b>' + markdata[i]['tt_phone'] + '</b></p></div>'
                        + lat +', ' +lng+'<br>'
                        + '<a href="/catalog/index/search_catalog/'+markdata[i]['number']+'">� �������</a> | '
                        + '<a href="/catalog/edit/id/'+markdata[i]['id']+'">�������������</a> | '
                        + '<a href="/repairs/add/number/'+markdata[i]['number']+'">������</a>',
                        marker = new google.maps.Marker({
                            position: myLatLng,
                            map: map,
                            draggable: true,
                            title: address,
                            //icon: pinImage
                        });

                attachSecretMessage(marker, mscontent);
            }

        }

    });
}

// Attaches an info window to a marker with the provided message. When the
// marker is clicked, the info window will open with the secret message.
function attachSecretMessage(marker, mscontent) {
    var infowindow = new google.maps.InfoWindow({
        content: mscontent
    });

    marker.addListener('click', function () {
        console.log('click');
        infowindow.open(marker.get('map'), marker);
    });
}
//������� �������� ������������� ��������
function utfConvertData(data) {
    
    var str = data;
    var combining = /[\u0300-\u036F]/g;

    return str.normalize('NFKD').replace(combining, '');
}

