function loadEditor(){

    ClassicEditor.create( document.querySelector('#editor'), {

        toolbar: ['heading', 'bold', 'italic', 'link', 'bulletedList','insertTable'],
        language:'ko'

    }).then(function (editor) {

    }).catch(function (error) {

        console.log(error);

    });
}

window.onload = function(){
    loadEditor();
}
