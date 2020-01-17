var host = location.host;

function formatNumber(num) {
    //Pasear numeros a punto de mil
    if (!num || num == 'NaN') return '-';
    if (num == 'Infinity') return '&#x221e;';
    num = num.toString().replace(/\$|\,/g, '');
    if (isNaN(num))
        num = "0";
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num * 100 + 0.50000000001);
    num = Math.floor(num / 100).toString();
    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3) ; i++)
        num = num.substring(0, num.length - (4 * i + 3)) + '.' + num.substring(num.length - (4 * i + 3));
    return (((sign) ? '' : '-') + num);
}

function crearFactura(id,nombre,valor)
{
    //Generar una nueva factura de compra
    var newData = {
        "idProducto": id,
        "valor": valor
    }
    $.ajax({
        url:"http://"+host+"/administrador/generar/factura",
        data: newData,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
        },
        success: function(response){
            if (response.code == 200) {
                //Cambiar de contenedor
                $("#sinFactura").css("display", "none");
                $("#factura").css("display", "");
                //Imprimir los valores en el texto
                $("#nombre_producto").html(nombre);
                $("#valor_producto").html(formatNumber(valor));
                $("#valor_subTotal").html(formatNumber(valor));
            }
        }
    });

    
}