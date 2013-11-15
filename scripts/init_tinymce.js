/**
 * Created by abatieva on 08.10.13.
 */

function init_tinymce(selector) {


    tinymce.PluginManager.load('moxiemanager', '/scripts/jquery/tinymce/plugins/moxiemanager/plugin.js');

    tinymce.init({
        selector: selector,
        plugins: [
            "autolink autosave link image lists preview spellchecker",
            "wordcount insertdatetime media nonbreaking code",
            "moxiemanager"
        ],
        toolbar1: "styleselect | bold italic underline | alignleft alignright aligncenter | bullist numlist | link unlink insertfile image media | code removeformat | preview",

        menubar: false,
        image_advtab: true,
        toolbar_items_size: 'small',
        style_formats: [
            {title: 'Paragraph', block: 'p'},
            {title: 'Header 1', block: 'span', styles: {color: '#444', font: "22px OpenSans, sans-serif", margin: '0 0 1em 0',display:'block'}},
            {title: 'Header 2', block: 'span', styles: {color: '#444', font: "bold 17px OpenSans, sans-serif", margin: '0 0 1em 0',display:'block'}}

        ],
        content_css : "/themes/bring_it_local/tinymce.css",
        setup: function (ed) {
            ed.on("init", function (ed) {
                var submit = $(selector + '_submit_btn');
                if (submit.length) {
                    this.on('blur', function (e) {

                        if (this.getContent() == "") {
                            submit.addClass('disabled');
                        } else submit.removeClass('disabled');


                    });
                }
            });
        }
    });
}