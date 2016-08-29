/*global $, jQuery, document */
//jslint checked
jQuery(document).ready(function ($) {
    "use strict";
    var new_feature, current_features, new_status, current_status, parent_div, field_type;
    new_feature = null;
    current_features = null;
    new_status = null;
    current_status = null;

    $('.deletefieldlink').click(function (event) {
        event.preventDefault();
        parent_div = $(this).parent();
        parent_div.remove();
    });

    field_type = 'short text';
    $('#field_type ').change(function () {
        field_type = this.value;
    });



    $('#add_curency').click(function () {
        var newfield, field_name, field_value, field_order,field_label;
        newfield = '';
        field_name  =   $('#currency_name').val();
        field_label =   $('#currency_label').val();
        field_value =   $('#currency_value').val();
        field_order =   $('#where_cur').val();

        newfield =  '<div    class=field_row>';
        newfield += '<div    class=field_item><strong>Currency Name</strong></br> <input  type="text" name="add_curr_name[]"   value="' + field_name + '"  ></div>';
        newfield += '<div    class=field_item><strong>Currency Label</strong></br><input  type="text" name="add_curr_label[]"  value="' + field_label + '"  ></div>';     
        newfield += '<div    class=field_item><strong>Currency Value</strong></br><input  type="text" name="add_curr_value[]"  value="' + field_value + '"  ></div>';
        newfield += '<div    class=field_item><strong>Currency Order</strong></br><input  type="text" name="add_curr_order[]"  value="' + field_order + '"  ></div>';
        newfield += '<a class="deletefieldlink" href="#">delete</a>';
        newfield += '</div>';

        $('#custom_fields').append(newfield);
        $('#currency_name').val('');
        $('#currency_label').val('');
        $('#where_cur').val('');
        $('#where_cur').val('');
    });




    $('#add_field').click(function () {
        var newfield, field_name, field_label, field_order;
        newfield = '';
        field_name  =   $('#field_name').val();
        field_label =   $('#field_label').val();
        field_order =   parseInt($('#field_order').val(), 10);

        newfield =  '<div    class=field_row>';
        newfield += '<div    class=field_item><strong>Field Name</strong></br><input  type="text" name="add_field_name[]"  value="' + field_name + '"  ></div>';
        newfield += '<div    class=field_item><strong>Field Label</strong></br><input  type="text" name="add_field_label[]"  value="' + field_label + '"  ></div>';
        newfield += '<div    class=field_item><strong>Field Type</strong></br><input  type="text" name="add_field_type[]"  value="' + field_type + '"  ></div>';
        newfield += '<div    class=field_item><strong>Field Order</strong></br><input  type="text" name="add_field_order[]" value="' + field_order + '"></div>';
        newfield += '<a class="deletefieldlink" href="#">delete</a>';
        newfield += '</div>';

        $('#custom_fields').append(newfield);
        $('#field_name').val('');
        $('#field_label').val('');
        $('#field_order').val('');
    });

    $('#new_feature, #new_status').focus(function () {
        $(this).val('');
    });

    $('#add_feature').click(function () {
        new_feature = $('#new_feature').val();
        if (new_feature !== '') {
            //feature_list
            current_features    =   $('#feature_list').val();
            if (current_features === '') {
                current_features    =   new_feature;
            } else {
                current_features    =   current_features + ',\n' + new_feature;
            }
            $('#feature_list').val(current_features);
            $('#new_feature').val('');
        }
    });


    $('#add_status').click(function () {
        new_status = $('#new_status').val();
        if (new_status !== '') {
            //status_list
            current_features    =   $('#status_list').val();

            if (current_status === '') {
                current_status    =   new_status;
            } else {
                current_status    =   current_features + ',\n' + new_status;
            }
            $('#status_list').val(current_status);
            $('#new_status').val('');
        }
    });

});