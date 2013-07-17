
(function($) {
    // @todo Document this.
    $.extend($,{ placeholder: {
        browser_supported: function() {
            return this._supported !== undefined ?
                this._supported :
                ( this._supported = !!('placeholder' in $('<input type="text">')[0]) );
        },
shim: function(opts) {
    var config = {
    color: '#888',
    cls: 'placeholder',
    selector: 'input[placeholder], textarea[placeholder]'
    };
$.extend(config,opts);
return !this.browser_supported() && $(config.selector)._placeholder_shim(config);
}
}});

$.extend($.fn,{
    _placeholder_shim: function(config) {
    function calcPositionCss(target)
    {
    var op = $(target).offsetParent().offset();
    var ot = $(target).offset();

    return {
    top: ot.top - op.top,
    left: ot.left - op.left,
    width: $(target).width()
    };
}
return this.each(function() {
    var $this = $(this);

    if( $this.is(':visible') ) {

    if( $this.data('placeholder') ) {
    var $ol = $this.data('placeholder');
    $ol.css(calcPositionCss($this));
    return true;
    }

var possible_line_height = {};
if( !$this.is('textarea') && $this.css('height') != 'auto') {
    possible_line_height = { lineHeight: $this.css('height'), whiteSpace: 'nowrap' };
}

var ol = $('<label />')
.text($this.attr('placeholder'))
.addClass(config.cls)
.css($.extend({
    position:'absolute',
    display: 'inline',
    float:'left',
    overflow:'hidden',
    textAlign: 'left',
    color: '#333',
    cursor: 'text',
    paddingTop: $this.css('padding-top'),
    paddingRight: $this.css('padding-right'),
    paddingBottom: $this.css('padding-bottom'),
    paddingLeft:'15px',
    fontSize:'16.65px',
    fontFamily:'DroidSans-Bold',
    fontStyle: $this.css('font-style'),
    fontWeight: 'normal',
    textTransform: $this.css('text-transform'),
    backgroundColor: 'transparent',
    zIndex: 99
    }, possible_line_height))
.css(calcPositionCss(this))
.attr('for', this.id)
.data('target',$this)
.click(function(){
    $(this).data('target').focus();
    })
.insertBefore(this);
$this
.data('placeholder',ol)
.focus(function(){
    ol.hide();
    }).blur(function() {
    ol[$this.val().length ? 'hide' : 'show']();
    }).triggerHandler('blur');
$(window)
.resize(function() {
    var $target = ol.data('target');
    ol.css(calcPositionCss($target));
    });
}
});
}
});
})(jQuery);

jQuery(document).add(window).bind('ready load', function() {
    if (jQuery.placeholder) {
    jQuery.placeholder.shim();
    }
});