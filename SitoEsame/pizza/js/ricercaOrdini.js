
$(document).ready(function () {
    
    $(".error").hide();
    $("#tabella_ordini").hide();
    
    $('#filtra').click(function(e){
        // impedisco il submit
        e.preventDefault(); 
        var _mydata = $( "#mydata option:selected" ).attr('value');
        var _myora = $( "#myora option:selected" ).attr('value');

        
        var par = {
            myData:_mydata,
            myOra: _myora
        };
        
        $.ajax({
            url: 'addettoOrdini/filtra_ordini',
            data : par,
            dataType: 'json',
            success: function (data, state) {
                if(data['errori'].length === 0){
                    // nessun errore
                    $(".error").hide();
                    if(data['ordini'].length === 0){
                        // mostro il messaggio per nessun elemento
                        $("#nessuno").show();
                       
                        // nascondo la tabella
                        $("#tabella_ordini").hide();
                    }else{
                        // nascondo il messaggio per nessun elemento
                        $("#nessuno").hide();
                        $("#tabella_ordini").show();
                        //cancello tutti gli elementi dalla tabella
                        $("#tabella_ordini tbody").empty();
                       
                        // aggingo le righe
                        var i = 0;
                        for(var key in data['ordini']){
                            var esami = data['ordini'][key];
                            $("#tabella_ordini tbody").append(
                                "<tr id=\"row_" + i + "\" >\n\
                                       <td>a</td>\n\
                                       <td>a</td>\n\
                                       <td>a</td>\n\
                                       <td>a</td>\n\
                                       <td>a</td>\n\\n\
                                        </tr>");
                            if(i%2 == 0){
                                $("#row_" + i).addClass("alt-row");
                            }
                            
                            var colonne = $("#row_"+ i +" td");
                            $(colonne[0]).text(esami['id']);
                            $(colonne[1]).text(esami['data']);
                            $(colonne[2]).text(esami['ora']);
                            $(colonne[3]).text(esami['cliente']);
                            $(colonne[4]).text(esami['prezzo']);

                            i++;
                            
                           
                        }
                    }
                }else{
                    $(".error").show();
                    $(".error ul").empty();
                    for(var k in data['errori']){
                        $(".error ul").append("<li>"+ data['errori'][k] + "<\li>");
                    }
                }
               
            },
            error: function (data, state, error) {
                
                alert(error);
            }
        
        });
        
    })
});
