
$(document).ready(function () {
    
    $(".error").hide();
    $("#tabella_ordini").hide();
    
    $('#filtra').click(function(e){
        // impedisco il submit
        e.preventDefault(); 
        //acquisisco i dati relativi a data e ora dal form e li rinomino
        var _myData = $( "#myData option:selected" ).attr('value');
        var _myOra = $( "#myOra option:selected" ).attr('value');

        
        var par = {
            myData:_myData,
            myOra: _myOra
        };
        //funzione ajax 
        $.ajax({
            url: 'addettoOrdini/filtra_ordini',
            data : par,
            dataType: 'json',
            success: function (data, state) {
                if(data['errori'].length === 0){
                    // nessun errore
                    $(".error").hide();
                    if(data['ordini'].length === 0){
                        // mostro il messaggio per nessun ordine trovato
                        $("#nessuno").show();
                       
                        // nascondo la tabella dei risultati
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
                            var ordini = data['ordini'][key];
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
                            $(colonne[0]).text(ordini['id']);
                            $(colonne[1]).text(ordini['cliente']);
                            $(colonne[2]).text(ordini['idCliente']);
                            $(colonne[3]).text(ordini['stato']);
                            $(colonne[4]).text(ordini['prezzo']);

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
