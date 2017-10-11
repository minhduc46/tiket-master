var fancyBoxAjaxForm = function (action) {
    $(document).on('click', '.alert', function () {
        $(this).hide();
    });

    $(document).on('click', '.btn-submit', function () {
        setTimeout(function () {
            $(".alert").fadeOut('slow');
        }, 5000);
    });
    $(document).on('click', '.btn-close', function () {
        $(".fancybox-overlay").fadeOut('slow', function () {
            $(this).remove();
        });
        $('html').removeClass();
        $("#" + action + "-grid").yiiGridView('update');
    });
    $('.btn-' + action).fancybox({
        closeBtn: false,
        autoHeight: true,
        autoWidth: true,
        helpers: {
            overlay: {
                closeClick: false
            }
        }
    });
    $(document).on('click', '.btn-submit', function () {
        $.ajax({
            type: "POST",
            cache: false,
            url: $("#" + action + "-form").attr('action'),
            data: $("#" + action + "-form").serializeArray(),
            success: function (data) {
                data = $.parseJSON(data);
                if (data.code == 1) {
                    $(".ajax-success").show();
                } else if (data.code == 2) {
                    $(".ajax-warning").show();
                } else {
                    $(".ajax-error").show();
                }
            }
        });
    });
};
var initSlug = function (id) {
    var first = $(".form-horizontal#" + id + " input[type=text]:first").attr('id');
    var last = $(".form-horizontal#" + id + " input[type=text]:last").attr('id');
    $(document).on('keyup', "#" + first, function () {
        $("#" + last).val(convertToSlug($(this).val()));
    });
    function convertToSlug(str) {
        str = str.replace(/^\s+|\s+$/g, ''); // trim
        str = str.toLowerCase();
        var from = "äàáạảãâầấậẩẫăằắặẳẵëèéẹẻẽêềếệểễïîìíịỉĩöòóọỏõôồốộổỗơờớợởỡüûùúụủũưừứựửữỳýỵỷỹđñç·/_,:;";
        var to = "aaaaaaaaaaaaaaaaaaeeeeeeeeeeeeiiiiiiioooooooooooooooooouuuuuuuuuuuuuyyyyydnc------";
        for (var i = 0, l = from.length; i < l; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }
        str = str.replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');

        return str;
    }
};
var initPreview = function (selector) {
    $("#" + selector).change(function () {
        var reader = new FileReader();
        reader.onload = imageIsLoaded;
        reader.readAsDataURL(this.files[0]);
    });
    function imageIsLoaded(e) {
        $('#preview_image').attr('src', e.target.result).attr('width', '100px');
    }
};
var initUpload = function () {
    $('.file-picker').ace_file_input({
        no_file: 'No File ...',
        btn_choose: 'Choose',
        btn_change: 'Change',
        droppable: true,
        onchange: null,
        thumbnail: 'small',
        before_change: function (files, dropped) {
            var allowed_files = [];
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                if (typeof file === "string") {
                    if (!(/\.(jpe?g|png|gif|bmp|pem)$/i).test(file)) return false;
                }
                else {
                    var type = $.trim(file.type);
                    if (( type.length > 0 && !(/^image\/(jpe?g|png|gif|bmp)$/i).test(type) )
                        || ( type.length == 0 && !(/\.(jpe?g|png|gif|bmp|pem)$/i).test(file.name) )
                    ) {
                        alert('Invalid file type.');
                        continue;
                    }
                }
                allowed_files.push(file);
            }
            if (allowed_files.length == 0) return false;
            return allowed_files;
        }
    });
};
var numberFormat = function (nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
};
var pad = function (str, max) {
    str = str.toString();
    return str.length < max ? pad("0" + str, max) : str;
};