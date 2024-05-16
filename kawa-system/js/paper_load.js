const input_and_convert_html_paper = (file_input_btn_id, paper_area_id) => {
    // 論文のHTMLファイルを入力（選択）して，それをSPANを挟むよう変換して，対象のテキストエリアに挿入するボタンクリックイベントを追加する関数
    const btn = document.getElementById(file_input_btn_id);
    btn.addEventListener("click", () => {
        btn.addEventListener("change", (evt) => {
            const file = evt.target.files;
            //FileReaderの作成
            const reader = new FileReader();
            //テキスト形式で読み込む
            reader.readAsText(file[0]);

            //読込終了後の処理
            reader.onload = () => {
                const new_span = document.createElement('span'); // 改行はいやなのでspan
                new_span.setAttribute('id', 'rebuild');
                new_span.innerHTML = reader.result; //html要素に変換

                //テキストエリアに表示する
                const area = $(paper_area_id);
                area.empty();
                area.append(new_span); //bodyに追加

                //* こっからSPANを突っ込む処理 *//
                const add_span = () => {
                    const recursive_leaf_apply = (target_dom) => {
                        if(target_dom.contents().length === 0) {
                            // もしこれ以上子要素がない＝SPANを追加したい対象の論文文字列である可能性がある場合
                            if(target_dom.context.nodeName === '#text') {
                                // 空のオブジェクトではなく，実際に文字列である場合，SPANでくくって，ID付与
                                const tmp = [...target_dom.context.nodeValue].map(char => {
                                    // 元のオブジェクトが１つのSPANに複数の文字が入ってる場合があるので，各文字に対してSPAN付与
                                    return "<span class='paper_txt_obj' >" + char + "</span>";
                                }).join('');
                                target_dom.replaceWith(tmp); // DOMオブジェクトの置き換え
                                return; // この条件にマッチしてるときは条件分岐移行の処理を殺す
                            }
                        }
                        target_dom.contents().each((ind, elm) => {
                            // もし，子要素にさらにDOMがある場合（＝さらに掘り下げたところに文字があるかも知れない場合），再帰して，子要素に対してSPAN付与の必要性チェック
                            return recursive_leaf_apply($(elm));
                        });
                    }

                    const add_id = () => {
                        //spanで区切られた文字にidを付与する
                        const ptolength=$(".paper_txt_obj").length;
                        $(".paper_txt_obj").each((index, elm) => {
                            $(elm).attr({
                                'char_id': 'p_txt_'+index
                            });
                        });
                    }

                    const before_spanned_text = $('#page-container');
                    recursive_leaf_apply(before_spanned_text); // PDF2HTMLEXで変換・スクリプトで一部だけ取得したHTMLファイルの中身を取得して，SPANを付与
                    add_id();
                    return;
                }
                add_span();
            }
        }, false);
    });
    $(file_input_btn_id).off();
}

//input_and_convert_html_paper("input_file", "#paper_area");
