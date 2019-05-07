$('#add-image').click(function(){
            
    const index = +$('#widgets_counter').val();
    const tmpl  = $('#content_images').data('prototype').replace(/__name__/g, index);

    $('#content_images').append(tmpl);
    
    $('#widgets_counter').val(index +1);
    
    handleDeleteButtons();
})

function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        console.log(target);
        $(target).remove();
    })
}

function UpdateCounter(){
    const count = +$('#content_images div.form-group').length;

    console.log(count);
    $('#widgets_counter').val(count);

}

UpdateCounter();

handleDeleteButtons();