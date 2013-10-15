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
        toolbar1: "formatselect | bold italic underline | alignleft | bullist numlist | link unlink image media | removeformat | preview",

        menubar: false,
        image_advtab: true,
        toolbar_items_size: 'small'

    });
}