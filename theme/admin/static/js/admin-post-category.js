$(function(){
    $('#field-canal').change(function(){
        var $this = $(this);
        var val = parseInt($this.val());
        
        if(!val)
            return $('.field-parent').prop('disabled', false);
        $('.field-parent:not([data-canal='+val+'])').prop('disabled', true);
        $('.field-parent[data-canal='+val+']').prop('disabled', false);
    });
    $('#field-canal').change();
});