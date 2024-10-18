


function show_other_mindmap_anno(){

    judge_charid(function (result) {
        if (result.length !== 0) {
            // 成功時の処理
            console.log("result=", result);
            result.sort((a, b) =>
                a.start_char_id > b.start_char_id ? 1 : -1
            );

        
            create_mindmapbutton(result, "annotation");
            
        } else {
            // エラー時の処理
            console.error("処理に失敗しました。");
            $('#mindmap_tab').empty();
            var arrayDisplay = document.getElementById("mindmap_tab");
            jm2_menu = document.createElement("div");
            jm2_menu.innerHTML = "選択したエリアに解釈ノードは存在しません";
            arrayDisplay.appendChild(jm2_menu);

        }
    });
}

//論文の選択部のidを取ってくる関数
function get_charid(){
    
    var selection = window.getSelection();
    if (selection.rangeCount > 0) {
        var range = selection.getRangeAt(0);

        
        // 選択範囲内のテキストを取得
        var selectedText = range.toString();

        // 選択範囲内の最初の文字の `char_id` を取得
        var startCharId = range.startContainer.parentNode.getAttribute("char_id");

        // 選択範囲内の最後の文字の `char_id` を取得
        var endCharId = range.endContainer.parentNode.getAttribute("char_id");

        charid = [startCharId, endCharId];
        var i = 0;

        for (var i = 0; i < charid.length; i++) {
            charid[i] = charid[i].match(/\d+/)[0];
        }      
    }
    return charid;
}

//取得した文章と被るところの検索
function judge_charid(callback) {
    var charid = get_charid(); // 文字IDを取得
    console

    if (charid !== null) { // 選択が行われているかを確認
        $.ajax({
            url: "php/get_other_annotation.php",
            type: "POST",
            data: {
                val: "judge_annotation",
                start_char_id: charid[0],
                end_char_id: charid[1]
            },
            success: function (question) {
                console.log(question);
                var result = JSON.parse(question);
                callback(result); // 結果をコールバック関数に渡す
            },
            error: function (error) {
                console.error("AJAXリクエストでエラーが発生しました:", error);
                callback(null); // エラー時にもコールバック関数を呼び出す
            }
        });
    } else {
        console.error("テキストが選択されていません。");
        callback(null); // エラー時にもコールバック関数を呼び出す
    }
}

// judge は annotation か node
function create_mindmapbutton(sheet_id_Array, judge){
    $('#mindmap_tab').empty();
    var arrayDisplay = document.getElementById("mindmap_tab");
    jm2_menu = document.createElement("span");
    jm3_menu = document.createElement("div");


    if (judge == "node"){      
        console.log(CheckSelectedNode());
        jm2_menu.innerHTML = "選択中 : " + CheckSelectedNode()["topic"];
        jm2_menu.setAttribute("id", "concept_content");
        jm2_menu.setAttribute("concept_id", CheckSelectedNode()["concept_id"]);
        arrayDisplay.appendChild(jm2_menu);
    }
    else if (judge == "annotation"){
        jm2_menu.innerHTML = "選択中 : 論文内文章    ";
        jm3_menu.innerHTML = '<label for="pet-select">表示形式:</label><select name="pets" id="pet-select"><option value="">--Please choose an option--</option><option value="dog">Dog</option><option value="cat">Cat</option><option value="hamster">Hamster</option><option value="parrot">Parrot</option><option value="spider">Spider</option><option value="goldfish">Goldfish</option></select>';


        arrayDisplay.appendChild(jm2_menu);
        arrayDisplay.appendChild(jm3_menu);

    }
<<<<<<< Updated upstream
    console.log(sheet_id_Array);
    var n = 0;


    for (var i = 0; i < sheet_id_Array.length; i++) {
        
        var button = document.createElement("button"); // 新しいボタン要素を作成
        sheet_id = sheet_id_Array[i]["sheet_id"];
        var result = hasSheetId(sheet_id);
        if (!result){
            button.innerHTML = n + 1
            button.setAttribute("data-sheet_id", sheet_id);
            button.setAttribute("data-content", sheet_id_Array[i]["content"]);
            button.setAttribute("data-parent_id", sheet_id_Array[i]["parent_id"]);
            button.setAttribute("class", "button10");
            button.setAttribute("onClick", "show_other_mindmap(this, "+sheet_id+", '"+sheet_id_Array[i]["parent_id"]+"');");
            arrayDisplay.appendChild(button); // ボタンを表示用の要素に追加
            n++;
        }       
=======
    for (var i = 0; i < sheet_id_Array.length; i++) {
    
        var button = document.createElement("button"); // 新しいボタン要素を作成
        button.innerHTML = i + 1; // ボタンのテキストを配列の要素に設定
        sheet_id = sheet_id_Array[i]["sheet_id"];

        button.setAttribute("data-sheet_id", sheet_id);
        button.setAttribute("data-content", sheet_id_Array[i]["content"]);
        button.setAttribute("class", "button10");
        button.setAttribute("onClick", "show_other_mindmap(this, "+sheet_id+");");
        console.log("届いてるよー");

        show_selected_sheet();
        arrayDisplay.appendChild(button); // ボタンを表示用の要素に追加
>>>>>>> Stashed changes
    }
   
}

<<<<<<< Updated upstream
function hasSheetId(sheet_id) {
    var elements = document.querySelectorAll('[data-sheet_id]');
  
    for (var i = 0; i < elements.length; i++) {
      var element = elements[i];
      var dataSheetId = element.getAttribute('data-sheet_id');
  
      if (dataSheetId === sheet_id) {
        return true;
      }
    }
    return false;
  }

=======
>>>>>>> Stashed changes





