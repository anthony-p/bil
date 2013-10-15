/**
 * Created by abatieva on 08.10.13.
 */

function init_tinymce(selector) {


    tinymce.PluginManager.load('moxiemanager', '/scripts/jquery/tinymce/plugins/moxiemanager/plugin.js');

    tinymce.init({
        selector: selector,
        plugins: [
            "autolink autosave link image lists preview spellchecker",
            "wordcount insertdatetime media nonbreaking ",
            "moxiemanager"
        ],
        toolbar1: "styleselect | bold italic underline | alignleft | bullist numlist | link unlink insertfile image media | removeformat | preview",

        menubar: false,
        image_advtab: true,
        toolbar_items_size: 'small',
        style_formats: [
            {title: 'Paragraph', block: 'p'},
            {title: 'Header 1', block: 'span', styles: {color: '#444', font: "1.5em OpenSans, sans-serif", margin: '0 0 1em 0'}},
            {title: 'Header 2', block: 'span', styles: {color: '#444', font: "bold 1.3em OpenSans, sans-serif", margin: '0 0 1em 0'}}

        ]
    });
}