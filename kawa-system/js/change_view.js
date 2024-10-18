function change_view(){
    
    var index = document.view_select.view_select.selectedIndex;
    var value= document.view_select.view_select.options[index].value;
    var viewId = document.getElementById("jsmind_container_view");

    //Allに切り替え
    if(value=="all"){
        console.log("view_all");

        

        $('#jsmind_container').css('height', '100%');
        $('#jsmind_container_view').css('height','0%');
    }
    //Yモデルに切り替え
    else if(value=="ymodel"){
        console.log("view_ymodel");

        $('#jsmind_container').css('height','0%');
        $('#jsmind_container_view').css('height','100%');

        //スクリプトの追加 view_ymodel.jsを実行

        $.ajax({
            url: "php/get_view_node.php",
            type: "POST",
            data: { val : "get_sheet_id"},
            success: function(sheet_ids){
                
                if(sheet_ids == "[]"){
                    console.log('fin');
                } else {
                    sheet_id = JSON.parse(sheet_ids);
                    view_ymodel(viewId, sheet_id[0]["sheet_id"]);
                }
            }
        })
        
    }
}

function other_view(){

    check = document.getElementById("checkbox_view");
    console.log($('#jsmind_container_view2').css('width'));

    // checkboxがチェックされている時の処理
    if(check.checked == true){

        $('#jsmind_container_view').css('width','50%');
        $('#jsmind_container_view2').css('width','50%');

        $.ajax({
            url: "php/get_view_node.php",
            type: "POST",
            data: { val : "get_sheet_id"},
            success: function(sheet_ids){
                
                if(sheet_ids == "[]"){
                    console.log('fin');
                } else {
                    sheet_id = JSON.parse(sheet_ids);
                }
            }
        })

        var viewId = document.getElementById("view_num")

        // for (let i = 1; i < Object.keys(sheet_id).length; i++) {
        //　実践用に↓のlengthいじっています
        //　実践が終わったら上記のlengthに戻す
        for (let i = 1; i < 5; i++) {
            var btn = document.createElement("button");

            btn.type = "button";
            btn.value = i;
            btn.className = "view_num_button";
            btn.name = "btn"+i;
            btn.textContent = i;
            
            btn.addEventListener('click', function () {
                other_view_click(Object.keys(sheet_id).length, sheet_id[i]["sheet_id"], this);
            });

            viewId.appendChild(btn);

            if(i == 1){
                other_view_click(Object.keys(sheet_id).length, sheet_id[i]["sheet_id"], btn);
            }
        }

    }
    else{
        $('#jsmind_container_view').css('width','100%');
        $('#jsmind_container_view2').css('width','0%');

        var viewId = document.getElementById("view_num");
        viewId.textContent = '';
        console.log(viewId);

    }

}

function other_view_click(n, sheet_id, btn){

    for(var a = 1; a < n; a++){
        var id = "btn"+a;
        var buttons = document.getElementsByName(id);

        for (var b = 0; b < buttons.length; b++) {
            var currentButton = buttons[b];
            
            if (currentButton.id === "selected") {
                currentButton.id = "";
                currentButton.style.backgroundColor = '';
            }
        }
    }

    btn.id = "selected";
    btn.style.backgroundColor = '#ffc57e';
 
    var viewId = document.getElementById("view_other");
    view_ymodel(viewId, sheet_id);

}