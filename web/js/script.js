jQuery(document).ready(function ($) {

    var contentStick = false;

    $('#invoicesgoods-count, #invoicesgoods-price_in').on('keyup', function (e) {

        contentStick = '';

        var unit = $('#invoicesgoods-goods_id option:selected').data('unit');
        var count = $('#invoicesgoods-count').val() * 1;
        var price_in = $('#invoicesgoods-price_in').val() * 1;

        if (typeof unit == 'number' && typeof arrayUnit[unit] == 'object' && typeof arrayUnit[unit].convert == 'object') {
            contentStick += (count / arrayUnit[unit].convert.value) + ' ' + arrayUnit[unit].convert.name;
            contentStick += '<br>';
        }

        if (count > 0 && price_in > 0 && typeof arrayUnit[unit] == 'object') {
            contentStick += (price_in / count) + ' ' + Currency + ' за 1 ' + arrayUnit[unit].name;
        }

        $('#data-info').html(contentStick);

    });

});