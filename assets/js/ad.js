$('#add-image').click(function(){
    // We count the number of current existing fields
    const index = $('#widgets-counter').val();
    console.log(index);

    //Getting the prototype code given by Symfony
    const tmpl = $('#ad_images').data('prototype').replace(/__name__/g, index);

    //Injecting the new code in the form div
    $('#ad_images').append(tmpl);

    $('#widgets-counter').val(parseInt(index)+1);

    //setting delete Button
    handleDeleteButtons();

});


function handleDeleteButtons(){
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target
        
        $(target).remove();
    })
}

function updateCounter(){
    const count = +$('#ad_images div.form-group').length;

    $('#widgets-counter').val(count);
}

updateCounter();

handleDeleteButtons();