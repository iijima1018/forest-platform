<?php
session_start();
// require("php/connect_db.php");
require("php/function.php");

require("php/sheet.php");

$pdo = connectDB_Test();

// ログイン状態のチェック
if (!isset($_SESSION["USERID"]) ) { //ログイン出来ていない
    header("Location: ./../logout.php");
    exit;
}

if( (isset($_POST["sheetbtn"])) ||   //シート選択ボタンが押された
    (isset($_SESSION["USERID"]) && !isset($_SESSION["SHEETID"]) )) { //ログインは出来ているがシート未選択の場合
    header("Location: select_sheet.php");
    $_SESSION["SHEETID"] = null; //シート選択画面に遷移させた時にSHEETIDをリセット
}

if(isset($_POST["logout"])){ //logoutボタンが押された
    // alert("本当にログアウトしますか？");
    
    // 時間があれば確認ダイアログを作る
    header("Location: ./../logout.php");
}

if(isset($_POST["myFileImage"])){ //imageFileImage
    // print("画像");

    if (!empty($_FILES['ImageFile']['name'])) {
        $uuid = uniqid();
        $name = $_FILES['ImageFile']['name'];
        $type = $_FILES['ImageFile']['type'];
        $content = file_get_contents($_FILES['ImageFile']['tmp_name']);
        $size = $_FILES['ImageFile']['size'];

        $sql = "INSERT INTO images(image_id, image_name, image_type, image_content, image_size, created_at)
      VALUES ('$uuid', :image_name, :image_type, :image_content, :image_size, now())";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':image_name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':image_type', $type, PDO::PARAM_STR);
        $stmt->bindValue(':image_content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':image_size', $size, PDO::PARAM_INT);
        $stmt->execute();
    }
    header("Location: index.php");
    // CreateSlide_Image();
    // header("Location: select_sheet.php");
    exit();
}


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<!-- ここから大槻修正 -->
<html lang="en">
    <!-- ここまで大槻修正 -->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>自己内対話活性化支援システム</title>
        <link type="text/css" rel="stylesheet" href="css/jsmind.css" />
        <link rel="stylesheet" type="text/css" href="css/item.css">
        <link rel="stylesheet" type="text/css" href="css/font.css">
        <link rel="stylesheet" type="text/css" href="css/jquery.cleditor.css">
        <link rel="stylesheet" type="text/css" href="css/ui.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">

        <script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/jsmind.js"></script>
        <script type="text/javascript" src="js/jsmind.draggable.js"></script>

        <script src="js/jquery.autosize.js"></script>
        <script src="js/jquery.autosize.min.js"></script>

        <script type="text/javascript" src="js/version_update.js"></script>
        <script type="text/javascript" src="js/get_thinking.js"></script>
        <script type="text/javascript" src="js/jsmind.screenshot.js"></script>
        <script type="text/javascript" src="js/change_tab.js"></script>
        <script type="text/javascript" src="./js/vis-network.min.js"></script>
        <script type="text/javascript" src="./js/meeting-reflection-network.js"></script>
        <link rel="stylesheet" type="text/css" href="css/meeting-reflection-network.css" />
        <script type="text/javascript">
        window.onbeforeunload = function(e) {e.returnValue = "ページを離れようとしています。よろしいですか？";}
        </script>
    </head>
    <body id="all">
        <!---        タイトルメニューStart                 -->
        <div id="main_title">
            <form name="return" method="POST">
                <span class="title_name">Forest</span>
                <span><input class="button2" type="submit" name="logout" value="ログアウト"></span>
                <span><input class="button1" type="submit" name="sheetbtn" value="シート選択画面へ"></span>
            </form>
        </div>
        <!---          タイトルメニューFinish              -->

        <!--      タブメニュー Start        -->
        <ul div class="tabnav">
            <li class="active"><a href="#tab01">思考整理支援システム</a></li>
            <!-- <li><a href="#tab02">過去のマインドマップ</a></li>
                 <li class="active"><a href="#tab03" >リフレクション</a></li>
                 <li class="active"><a href="#record_tab" >履歴</a></li> -->
            <li class="active"><a href="#tab04">過去のマインドマップ</a></li>  <!--hatakeyama-->
        </ul>
        <!-- タブメニュー　Finish -->
        
        <div class="Menu">--Menu--
            <!--サイドメニュー　start-->
            <div id="side_menu">
                <!-- プレゼンモードのサイドメニュー -->
                <div id="document">
                    <div id="advice_frame" class="searchFrame">
                        <p id=now_logic_relation> </p>
                    </div>
                </div><!--document fin -->

                <!-- マインドマップ編集のサイドメニュー -->
                <div id="mind">
                    <!--ここから大槻修正-->
                    <div id = "feedback_area" style="display: none">
                        <div id = "ontology_feedback"></div>
                        <div id = "accordion_discussion"></div>
                        <input id = "feedbackrecord" type="button" value="記録">
                    </div>
                    <div id="xml_upload_area" style="display: none">
                        <form id="uploadForm" enctype="multipart/form-data">
                            <div style="font-size: 15px;">XMLファイルを選んでください</div>
                            <input type="file" name="xmlFile" id="meetingUtteranceXmlFileUploader" accept=".xml">
                        </form>
                        <button id="discussion_log_xml_file_upload_button">アップロード</button>
                        <div id="uploaded_meeting_utterance_xml_concent_display_area" style="display: none"></div>
                    </div>
                    <!--ここまで大槻修正-->
                    <!-- <div class="correct_reason">修正理由</div>
                         <div id="reason" align="center"></div>
                         <div class="toi_menu">問い一覧</div> -->

                    <!--  hatakeyama  -->
                    <div class="version_reason">
                        <div class="correct_reason">バージョン更新理由</div>
                        <div id="comment_balloon"class="comment balloon-under" hidden>
                            <p>バージョンを更新した理由が<br/>あれば記述しましょう！</p>
                        </div>
                        <div id="reason" style="text-align:'center'"></div>
                    </div>
                    <div class="correct_reason">ノードバージョン履歴</div>
                    <div id="node_version_log" class="node_version_log"></div>
                    <!--  hatakeyama  -->

                    <div class="toi_list">
                        <div id="mind_all">
                            <input class="button5" type="button" onclick="showGeneration();" value="問い一覧">
                            <b>マインドマップモード</b>
                        </div>
                        <div id="presen_all" hidden>
                            <input class="button5" type="button" onclick="P_showGeneration();" value="問い一覧">
                            <b>資料作成モード</b>
                        </div>
                    </div>

                    <div class="inquiry_area">
                        <div>【情報の表出化】</div>
                        <div id="testxml"></div>
                        <div id="ont"></div>
                        <div>【理由・目的】</div>
                        <div id="intention"></div>
                        <div>【合理性】</div>
                        <div id="rationality"></div>
                    </div>
                    <div id="ImageAddContent">
                        <!-- <form id="ImageForm" method="POST" enctype="multipart/form-data"> -->
                        <div class="deco-file">
                            <label>
                                画像追加
                                <input id="myFile" type="file" name="ImageFile" onchange="handleFileSelect()" accept="image/*" required>
                            </label>
                            <p id="FilenameDisplay" class="file-names"></p>
                        </div>
                        <!-- <button id="ImageSaveButton" type="submit" class="btn btn-primary" name="myFileImage">画像保存</button> -->
                        <button id="ImageSaveButton" name="myFileImage" hidden>画像保存</button>
                        <!-- </form> -->
                    </div>
                    <div id='node_slide'>
                        <!-- <input id="finish_btn" class="presen-btn" type="button" value="資料作成終了" onclick="macrolevel_xmlLoad();"> -->
                        <input id="output_file" class="presen-btn" type="button" value="資料構成出力" onclick="OutputFile();">
                        <button id="input_btn" class="presen-btn">資料構成復元</button>
                        <input id="input_file" type="file" onclick="InputFile()" >
                    </div>
                    <div id='document_slide'>
                        <input id="finish_btn" class="presen-btn" type="button" value="資料作成終了" onclick="OutputFileS();">
                    </div>
                </div> <!-- mind fin -->

                <!--サイドメニュー　finish-->
            </div>

        </div>

        <div class="checkbox">
            <form name="target_mode" action="">
                <select class="cp_ipselect2 cp_sl02"name="Select1">
                    <option>目標管理モード</option>
                    <option>自己内対話モード</option>
                    <option>資料構成作成モード</option>
                    <option>資料作成モード</option>
                    <option>議論内省マップモード</option>
                </select>
                <input type="button" class="button3" value="実行" onclick="ModeChangeButtonClick();" />
            </form>
            
        </div>

        <!--メインメニュー　Start  -->
        <div class="tabcontent">
            <!-- 思考整理支援システム -->
            <div id="tab01">
                <div id="layout">
                    <div id="jsmind_nav">
                        <div style="text-align: left">
                            <!-- 【Edit】 -->
                            <button class="button4" onclick="add_Qnode();">
                                問いノード追加
                            </button>
                            <button class="button4" onclick="add_Anode();">
                                答えノード追加
                            </button>
                            <!-- <li><button onclick="horisage();">掘り下げる</button></li>
                                 horisage()関数は現在存在しない-->
                            <button class="button4" onclick="remove_node();">
                                ノードの削除
                            </button>
                            <!--1つ前に消したノードを復元-->
                            <!-- <button class="button4" onclick="return_node();">
                                 1つ前に戻る
                                 </button> -->
                            <!-- 【Zoom】 -->
                            <button class="button3" id="zoom-in-button" onclick="zoomIn();">
                                拡大
                            </button>
                            <button class="button3" id="zoom-out-button" onclick="zoomOut();">
                                縮小
                            </button>
                            <button class="button4" id="map-snapshot-button" onclick="MapSnapShot();RecordRelation();">
                                マップver更新 <!--hatakeyama-->
                            </button>
                            <!-- 【Reason】
                                 <button class="button4" onclick="add_edit_reason();">
                                 修正理由の追加
                                 </button> -->
                            <!-- 【Screenshot】
                                 <button class="button4" style="width:80px" onclick="screen_shot();">
                                 screenshot
                                 </button> -->
                            <!-- <label><input type="checkbox" name="Difference" id="Difference" onClick="Difference();">以前のマップとの差分</label> -->
                            <div id="comment_balloon2"class="comment two" hidden><!--hatakeyama -->
                                <p>緑にハイライトされたノードは合理性を考えるべきノードです．<br/>このノードの考えを変えた際には，関連したノードも考え直す必要はないか考えてみましょう！</p>
                            </div>
                            <div id="comment_balloon3"class="comment three" hidden><!--hatakeyama-->
                                <p>何度もバージョン更新を行っている重要なノードです．<br/>定期的に考えを確認しましょう！</p>
                            </div>

                            <!-- ここから清水さん１ -->
                            <div id ="presen_menu">
                                <button class="button4" onclick="NewContent_Append('問い')">問いノード追加</button>
                                <button class="button4" onclick="NewContent_Append('答え')">答えノード追加</button>
                                <button class="button4" onclick="add_Confirm();">マップ側へ反映</button>
                                <button class="button4" onclick="Unreflected_node();">未反映ノード</button>
                                <!-- <button class="button4" onclick="CheckNodeAllLogicRelation();">関係性の一覧</button> -->
                                <button class="button4" onclick="DeleteLogicRelation();">関係性の解消</button>
                            </div> <!-- presen_menu fin -->
                        </div>
                    </div><!--jsmind_nav fin-->

                    <div id="jsmind_container" oncontextmenu="return false;">
                        <div id="mindmap_conmenu">
                            <ul>
                                <li><a href="javascript:void(0);" onClick="ItemAddDocument()">項目として追加する</a></li>
                                <li><a href="javascript:void(0);" onClick="NodeAppendLogic()">内容として追加する</a></li>
                                <li><a href="javascript:void(0);" onClick="VersionSpread();RecordRelation()">ノードの更新をマップ全体に波及させる</a></li><!--hatakeyama-->
                            </ul>
                        </div>
                    </div>
                    <div id="document_area" oncontextmenu="return false;">
                        <div id="document_title">
                            <div class="document_purpose">
                                <textarea id="scenario_title" class="document_title_area" class="statement" onfocus='TextboxClick()' onblur='Edit_title(this);Record_rank();' placeholder="資料タイトル" style='width:90%;'></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- 20221208 shimizu　資料構成作成エリアで右クリックしたときに項目出現 -->
                    <div id="document_area_conmenu">
                        <ul>
                            <li><a href="javascript:void(0);" onClick="LogicRelationChecker()">設定した関係を確認する</a></li>
                        </ul>
                    </div>
                    <div id="document_area_conmenu2" >
                        <select  id="Slides" class='cp_ipselect cp_sl05'>
                        </select><a id="SlideName">大枠</a><br>
                        <select id="Sentences" class='cp_ipselect cp_sl05'>
                        </select><a id="SelectNode">文章</a><br>
                        <input id="ImageOntologyDecide"type="button" value="決定" onclick="AddOntologyInfo();">
                        <input type="button" value="キャンセル" onclick="CancelButton_Click('document_area_conmenu2')">
                    </div>
                    
                    <div id="document_area_conmenu3" >
                        <div id="first_choice_node">
                            <select id="first_logic_node" class='cp_ipselect cp_sl05'>
                                <option value="主張">主張</option>
                                <option value="論拠">論拠</option>
                                <option value="根拠">根拠</option>
                            </select>
                            <select id="logic_intention1_node" class="cp_ipselect cp_sl04" >
                            </select><a id="SelectContent1_node">スライドA</a><br>
                        </div>
                        <div id="second_choice_node">
                            <select id="second_logic_node" class='cp_ipselect cp_sl05'>
                            </select>
                            <select id="logic_intention2_node" class="cp_ipselect cp_sl04">
                            </select><a id="SelectContent2_node">スライドB</a><br>
                        </div>
                        <input id="DecideLogicRelationButton" type="button" value="決定" onclick="DecideNodeLogicRelation_Click();">
                        <input id="DecideLogicRelationButton" type="button" value="キャンセル" onclick="CancelButton_Click('document_area_conmenu3')">
                    </div>
                    
                    <div id="document_area_conmenu4" >
                        <div id="first_choice">
                            <select id="first_logic" class='cp_ipselect cp_sl05'>
                                <option value="主張">主張</option>
                                <option value="論拠">論拠</option>
                                <option value="根拠">根拠</option>
                            </select>
                            <select id="logic_intention1" class="cp_ipselect cp_sl04" >
                            </select><a id="SelectContent1">スライドA</a><br>
                        </div>
                        <div id="second_choice">
                            <select id="second_logic" class='cp_ipselect cp_sl05'>
                            </select>
                            <select id="logic_intention2" class="cp_ipselect cp_sl04">
                            </select><a id="SelectContent2">スライドB</a><br>
                        </div>
                        <input id="DecideLogicRelationButton" type="button" value="決定" onclick="DecideSlideLogicRelation_Click();">
                        <input id="DecideLogicRelationButton" type="button" value="キャンセル" onclick="CancelButton_Click('document_area_conmenu4')">
                    </div>
                    <!--  kagitani　-->
                    <div id="object_container" oncontextmenu="return false;" >
                        <div id="utterance_area">
                            <div id="rclick2">
                                <div id="timedisplay"></div>
                                <div id="rclick"></div>
                            </div>
                            <div id="utterance_area2">
                            </div>
                        </div>
                        <div id="myobject">
                            <div id="buttoncluster">
                                <input type="button" class="meeting_reflectin_network_button"
                                       id="mrnb_addNode" value="目標追加" />
                                <input type="button" class="meeting_reflectin_network_button"
                                       id="mrnb_addNode" value="手順追加" /> 
                                <input type="button" class="meeting_reflectin_network_button"
                                       id="mrnb_removeNode" value="ノード削除" />
                                <input type="button" class="meeting_reflectin_network_button"
                                       id="mrnb_startEditEdge" value="エッジ追加" />
                                <input type="button" class="meeting_reflectin_network_button"
                                       id="mrnb_removeEdge" value="エッジ削除" />
                                <input type="button" class="meeting_reflectin_network_button"
                                       id="mrnb_ZoomIn" value="拡大" />
                                <input type="button" class="meeting_reflectin_network_button"
                                       id="mrnb_ZoomOut" value="縮小" />
                            </div>
                            <div id="network_conmenu">
                                <ul>
                                    <li><a href="javascript:void(0);" id="net_conmenu1">概念をつける</a></li>
                                    <li><a href="javascript:void(0);" id="net_conmenu2">マインドマップと対応付ける</a></li>
                                    <li><a href="javascript:void(0);" id="net_conmenu3" style="display:none">採用/棄却をつける</a></li>
                                    <li><a href="javascript:void(0);" id="net_conmenu4">キャンセル</a></li>
                                </ul>
                            </div>
                            <div id="labelselect">
                                <select id="selectionlist" size="3">
                                </select>
                                <input type="button" value="選択完了" id="ontology_select">
                            </div>
                            <div id="recruitselect">
                                <select id="recruitselectionlist">
                                    <option value="採用">採用</option>
                                    <option value="棄却">棄却</option>
                                </select>
                                <input type="button" value="選択完了" id="recruit_select">
                            </div>
                            <div id="mynetwork"></div>
                        </div>
                    </div>
                    <!--  kagitani　-->
                    <!--  ここから大槻修正　-->
                    <!-- <div id="network_container" oncontextmenu="return false;" >
                        <div id="utterance_area">
                            <div id="rclick2">
                                <div id="timedisplay"></div>
                                <div id="rclick"></div>
                            </div>
                            <div id="utterance_area2">
                            </div>
                        </div>
                        <div id="mynetwork2">
                            <div id="buttoncluster">
                                <input type="button" class="meeting_reflectin_network_button"
                                       id="mrnb_addNode" value="要約ノード追加" />
                                <input type="button" class="meeting_reflectin_network_button"
                                       id="mrnb_removeNode" value="ノード削除" />
                                <input type="button" class="meeting_reflectin_network_button"
                                       id="mrnb_startEditEdge" value="エッジ追加" />
                                <input type="button" class="meeting_reflectin_network_button"
                                       id="mrnb_removeEdge" value="エッジ削除" />
                                <input type="button" class="meeting_reflectin_network_button"
                                       id="mrnb_ZoomIn" value="拡大" />
                                <input type="button" class="meeting_reflectin_network_button"
                                       id="mrnb_ZoomOut" value="縮小" />
                            </div>
                            <div id="network_conmenu">
                                <ul>
                                    <li><a href="javascript:void(0);" id="net_conmenu1">概念をつける</a></li>
                                    <li><a href="javascript:void(0);" id="net_conmenu2">マインドマップと対応付ける</a></li>
                                    <li><a href="javascript:void(0);" id="net_conmenu3" style="display:none">採用/棄却をつける</a></li>
                                    <li><a href="javascript:void(0);" id="net_conmenu4">キャンセル</a></li>
                                </ul>
                            </div>
                            <div id="labelselect">
                                <select id="selectionlist" size="3">
                                     いるやつあれば追加やけど未実装（研究活動オントロジー読み込みかな？） 
                                </select>
                                <input type="button" value="選択完了" id="ontology_select">
                            </div>
                            <div id="recruitselect">
                                <select id="recruitselectionlist">
                                    <option value="採用">採用</option>
                                    <option value="棄却">棄却</option>
                                </select>
                                <input type="button" value="選択完了" id="recruit_select">
                            </div>
                            <div id="mynetwork"></div>
                        </div>
                    </div> -->
                    <!--  ここまで大槻修正　-->
                </div>
            </div><!--layout fin-->

            <!--  tab04メニュー　　hatakeyama　　-->
            <div id="tab04">
                <div id="layout">
                    <div id="jsmind_nav2">
                        <div class ="mt_timing">
                            <!-- 時刻入力で過去のマップ表示 -->
                            <!-- ここから大槻修正 -->
                            <div id="timeselect">
                                <select id="selectiontime">
                                </select>
                                <input id="past_time_select_button" type="button" value="選択完了">
                            </div>
                            <!-- ここまで大槻修正 -->
                        </div>
                    </div>
                    <!-- ここから大槻修正 -->
                    <div id="jsmind_container4">
                        <!-- ここまで大槻修正 -->
                        <!-- 過去のマインドマップを表示する部分 -->
                        <div id="jsmind_container2"></div>
                        <!--<div>過去のオントロジー</div>-->
                        <!-- </div> -->
                        <!-- 現在のマインドマップのコピー -->
                        <div id="jsmind_container3"></div>
                        <!-- ここから大槻修正 -->
                    </div>
                    <div id="mynetwork_show"></div>
                    <!-- ここまで大槻修正 -->
                </div>
            </div>

            <!-- リフレクション　yoshioka -->
            <div id="tab03">
                <div id="layout">
                    <div id="reflection_container">
                        <form id ="ref_peri" class="ref_peri" method = "post" acion="">
                            <p>リフクション期間を設定してください</p>
                            <label><input id="ref_c2" type="radio" name="ref_per" value="today" onclick="riflection_period2();" checked/>本日分のリフレクション</label>
                            <br>
                            <br>
                            <label><input id="ref_c" type="radio" name="ref_per" value="select" onclick="riflection_period();"/>リフレクション期間を指定する</label>
                            <br>
                            <input id="reflection_period" name="start_date" type="date" disabled="disabled"/>から<input id="reflection_period2" name="finish_date" type="date" disabled="disabled"/>
                            <br>
                            <br>
                            <span><input id="reflection_btn" type="button" onclick="activity_reflection();" value="リフレクション開始" /></span>
                        </form>
                        <form id ="reflection_form" class="ref_form" method = "post" action = "php/record_reflection.php" ></form>
                    </div>
                </div>
            </div>
            <!--リフレクション終了 yoshioka -->

            <!-- 履歴　yoshioka -->
            <div id="record_tab">
                <div id="layout">
                    <div id="record_container">
                        <form id ="reco_peri" class="ref_peri" method = "post" acion="">
                            <p>確認したいリフレクション履歴期間を設定してください</p>
                            <label><input id="reco_c2" type="radio" name="reco_per" value="today" onclick="record_period2();" checked/>本日分のリフレクション</label>
                            <br>
                            <br>
                            <label><input id="reco_c" type="radio" name="reco_per" value="select" onclick="record_period();"/>リフレクション期間を指定する</label>
                            <br>
                            <input id="reco_period" name="start_date" type="date" disabled="disabled"/>から<input id="reco_period2" name="finish_date" type="date" disabled="disabled"/>
                            <br>
                            <br>
                            <!-- ↓idがバッティングしていたため，とりあえずコメントアウトしている． -->
                            <!-- <span><input id="reflection_btn" type="button" onclick="get_recordAAAA();" value="リフレクション履歴表示" /></span> -->
                        </form>
                        <div id ="record_table"></div>
                    </div>
                </div>
            </div>
            <!--履歴 yoshioka -->
            
        </div>
        <!-- メインメニュー　Finish -->

        <div id="macro_feedback_area">
        </div>


        <script type="text/javascript" src="js/second_advice.js"></script>
        <script type="text/javascript" src="js/mindmap.js"></script>
        <script type="text/javascript" src="js/add_node.js"></script>
        <script type="text/javascript" src="js/past_sheet.js"></script>
        <script type="text/javascript" src="js/record_presentation.js"></script>
        <script type="text/javascript" src="js/presentation.js"></script>
        <script type="text/javascript" src="js/micro.js"></script>
        <script type="text/javascript" src="js/macro.js"></script>
        <script type="text/javascript" src="js/macrolevel_advice.js"></script>
        <script type="text/javascript" src="js/rationality.js"></script>
        <script type="text/javascript" src="plugins/Sortable-master/Sortable.js"></script>
        <script type="text/javascript" src="plugins/Sortable-master/Sortable.min.js"></script>
        <script type="text/javascript" src="plugins/Modaal-master/dist/js/modaal.js"></script>
        <script type="text/javascript" src="plugins/Modaal-master/dist/js/modaal.min.js"></script>
        <!-- <script src="plugins/Modaal-master/dist/css/modaal.css"></script> -->
        <script type="text/javascript" src="js/ont_inquiry.js"></script>
        <script type="text/javascript" src="js/ont_inquiry_verp.js"></script>
        <script type="text/javascript" src="js/ont_choose_inquiry.js"></script>
        <script type="text/javascript" src="js/ont_choose_input_output.js"></script>
        <script type="text/javascript" src="js/ont_rationality.js"></script>
        <script type="text/javascript" src="js/ont_scenario_inquiry.js"></script>
        <script type="text/javascript" src="js/ont_audience_model.js"></script>
        <script type="text/javascript" src="js/upload.js"></script>     
        <!-- 2022.shimizu -->
        <script type="text/javascript" src="js/html2canvas.min.js"></script>
        <script type="text/javascript" src="js/add_OntologyArea.js"></script>
        <!--  ここから大槻修正　--> 
        <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis-network.min.css" rel="stylesheet" type="text/css" /> -->
        <script type="text/javascript" src="./js/network.js"></script>  
        <script type="text/javascript" src="./js/readxmldata.js"></script>  
        
        <!--  ここまで大槻修正　-->
    </body>
</html>
