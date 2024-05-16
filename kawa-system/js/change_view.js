function change_view(){
    


    var index = document.view_select.view_select.selectedIndex;
    var value= document.view_select.view_select.options[index].value;
    console.log(value);

    //Allに切り替え
    if(value=="all"){
        console.log("view_all");

        $('#jsmind_container').css('height','100%');
        $('#jsmind_container_view').css('height','0%');
    }
    //Yモデルに切り替え
    else if(value=="ymodel"){
        console.log("view_yモデル");

        $('#jsmind_container').css('height','0%');
        $('#jsmind_container_view').css('height','100%');

        $.ajax({

            url: "php/get_view_node.php",
            type: "POST",
            data: { val : "get_conceptid",
                    id : nodeid
                },
            success: function(conceptid){
                
                if(conceptid =="[]"){
                    console.log('fin');
                    return;
                }
                conceptid = conceptid.substring(2, 20);
                
                
                $.ajax({

                    url: "php/get_other_annotation.php",
                    type: "POST",
                    data: { val : "get_question",
                            id : conceptid
                        },
                    success: function(question){
                        result = JSON.parse(question);
                        var Array = result;
                        create_mindmapbutton(Array, "node");
                    }
                });

            }
        })
    }



}
