$(buscar_datos());

window.onload= ()=>{
    buscar_datos("");
};
function buscar_datos(consulta){
    $.ajax({
        url: 'app/buscar.php',
        type: 'POST',
        dataType: 'html',
        data: {consulta: consulta},
        success:(respuesta)=>{
            $("#datos").html(respuesta);
        },
        error:()=>{
            console.log("error");
        }
    })
    // .done(function(respuesta){
    //     $("#datos").html(respuesta);
    // })
    // .fail(function(){
    //     console.log("error");
    // })
    
}