
var notify = {
    info : function(text, title) {
        var title = title || '';

        $.pnotify({
            title: title,
            text: text
        });
    },

    error : function(text, title) {
        var title = title || 'Ошибка';

        $.pnotify({
            title: title,
            text: text,
            type: 'error'
            //icon: false
        });
    }
}