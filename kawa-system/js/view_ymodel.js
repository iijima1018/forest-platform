function view_ymodel(viewId, sheet_id){

    var toi = new Array();
    var answer = new Array();
    var dot = new Array();
    var ynodes_array = new Array();
    var ynodes_vis_nodes = new Array();
    var ynodes_vis_edges = new Array();

    // var element = document.getElementById("jsmind_container_view");
    // element.textContent = "";


    //concept_idからノード情報を取得
    //umodel_c_idは順に 1.学習目標 2.学習者モデル 3.困難性 4.教授戦略 5.学習教材 6.学習支援システム 7.支援機能

    var ymodel_c_id = ["1509010690559_n188", "1643924411000_n841", "1608617663000_n631", "1643925952000_n847", "1607521395000_n588", "1509010690552_n82", "1643925952000_n851" ];

    $.ajax({
        url: "php/get_view_node.php",
        type: "POST",
        data: { val : "view_ymodel",
                concept_id: ymodel_c_id,
                sheet_id: sheet_id},
        success: function(y_nodes){
            
            if(y_nodes === "[]"){
                console.log('fin');
            } else {
                ynodes_array = JSON.parse(y_nodes);
            }
            get_parent_nodes(ynodes_array);

        }
    })

    //　子ノードを取得
    function get_parent_nodes(ynodes_array){

        var n = 0;
        var node_id = new Array();
        var c_number = new Array();

        for(var i = 0; i < ynodes_array.length; i++){
            for(var j = 0; j < ynodes_array[i].length; j++){
                node_id[n] =  ynodes_array[i][j]['node_id'];
                c_number[n] = ynodes_array[i][j]['c_number'];
                n++;
            }
        }

        $.ajax({
            url: "php/get_view_node.php",
            type: "POST",
            data: { val : "get_parent_node",
                    node_id: node_id,
                    c_number: c_number,
                    sheet_id: sheet_id
                },
            success: function(child_nodes){
                
                let child_array;

                if(child_nodes === "[]"){
                    console.log('DontHaveChild');
                    child_array = [];
                } else {
                    child_array = JSON.parse(child_nodes);
                }

                ynodes_array.push(child_array);

                ymodel_vis(ynodes_array);

            }
        })
    }



    //jsmind_container_viewに表示
    function ymodel_vis(ynodes_array){

        console.log(ynodes_array);

        var nodes_base = [
            {id: "1.0", label: '学習目標', x: 0, y: 0, group:'ymodelNodes', cid: 1},
            {id: "2.0", label: '学習者モデル', x: 0, y: 500, group:'ymodelNodes', cid: 2},
            {id: "3.0", label: '困難性', x: 0, y: 1000, group:'ymodelNodes', cid: 3},
            {id: "4.0", label: '教授戦略', x: 500, y: -500, group:'ymodelNodes', cid: 4},
            {id: "5.0", label: '学習教材', x: -500, y: -500, group:'ymodelNodes', cid: 5},
            {id: "6.0", label: '学習支援システム', x: -500, y: 1500, group:'ymodelNodes', cid: 6},
            {id: "7.0", label: '支援機能', x: 500, y: 1500, group:'ymodelNodes', cid: 7}
        ];

        var edges_base = [
            {from: "2.0", to: "1.0", arrows: 'to', group: 'ymodelEdges'},
            {from: "3.0", to: "2.0", arrows: 'to', group: 'ymodelEdges'},
            {from: "4.0", to: "1.0", arrows: 'to', group: 'ymodelEdges'},
            {from: "5.0", to: "1.0", arrows: 'to', group: 'ymodelEdges'},
            {from: "6.0", to: "3.0", arrows: 'to', group: 'ymodelEdges'},
            {from: "7.0", to: "6.0", arrows: 'to', group: 'ymodelEdges'}
        ];

        var nodes = new vis.DataSet(nodes_base);
        var edges = new vis.DataSet(edges_base);




        //取得したノードを格納　深度1
        for(var i = 0; i < ynodes_array.length - 1; i++){

            if(i < nodes_base.length && ynodes_array[i] == ""){

                nodes_base[i].group = 'noymodelNodes';
                console.log(nodes_base[i].group);

            }else if(ynodes_array[i] != ""){

                for(var j = 0; j < ynodes_array[i].length; j++){
                    
                    if(j == 0){
                        dot[i] = 1;
                    }else{
                        dot[i]++;
                    }

                    var to = ynodes_array[i][j]["c_number"] +".0";
                    var id =  ynodes_array[i][j]["c_number"] +"."+ ynodes_array[i][j]["child"] +"."+ ynodes_array[i][j]["node_id"];
                    var label = ynodes_array[i][j]["content"];
                    
                    if(ynodes_array[i][j]["c_number"] != 8){
                        
                        if(ynodes_array[i][j]["type"] == 'konkyo' || ynodes_array[i][j]["type"] == 'other_to_myanswer'){

                            var group = 'konNodes';

                        }
                        else if(ynodes_array[i][j]["type"] == 'predict'){

                            var group = 'preNodes';

                        }
                            
                        ynodes_vis_nodes = {id: id, label: label, group: group, cid: ynodes_array[i][j]["c_number"]}; 
                        ynodes_vis_edges = {from: id, to: to, arrows: 'to', group: group + 'Edges'};

                        nodes_base.push(ynodes_vis_nodes);
                        edges_base.push(ynodes_vis_edges);

                    }
                }
            }
        }

        //子の場合　深度2以上
        for(var i = 0; i < ynodes_array[7].length; i++){
            
            if(ynodes_array[7][i] != ""){

                for(var j = 0; j < ynodes_array[7][i].length; j++){

                    var c = ynodes_array[7][i][j]["c_number"] - 1;
            
                    for(var l = 0; l < ynodes_array[c].length; l++){

                        //子が親につながっている場合
                        if(ynodes_array[7][i][j]["parent_id"] == ynodes_array[c][l]["node_id"] && ynodes_array[7][i][j]["c_number"] == ynodes_array[c][l]["c_number"]){

                            //　toiはそのまま格納し、toi以外のノードはconcept_idがつながっているか判定
                            if(ynodes_array[7][i][j]["type"] == 'toi' || ynodes_array[7][i][j]["type"] == 'toi_deep' || ynodes_array[7][i][j]["type"] == 'other_to_myquestion'){
                                
                                var label = ynodes_array[7][i][j]["content"];
                                var id =  ynodes_array[7][i][j]["c_number"] + "." + ynodes_array[7][i][j]["child"] +"."+ ynodes_array[7][i][j]["node_id"];
                                var to = ynodes_array[c][l]["c_number"] +"."+ ynodes_array[c][l]["child"] +"."+ ynodes_array[c][l]["node_id"];
                                var group = 'toiNodes';

                        
                                ynodes_vis_nodes = {id: id, label: label, group: group, cid: ynodes_array[7][i][j]["c_number"]}; 
                                ynodes_vis_edges = {from: id, to: to, arrows: 'to', group: group + 'Edges'};
                                

                            }else {

                                var label = ynodes_array[7][i][j]["content"];
                                var id =  ynodes_array[7][i][j]["c_number"] + "." + ynodes_array[7][i][j]["child"] +"."+ ynodes_array[7][i][j]["node_id"];
                                var to = ynodes_array[c][l]["c_number"] +"."+ ynodes_array[c][l]["child"] +"."+ ynodes_array[c][l]["node_id"];

                                if(ynodes_array[7][i][j]["type"] == 'konkyo' || ynodes_array[7][i][j]["type"] == 'other_to_myanswer'){
            
                                    var group = 'konNodes';
                            
                                }
                                else if(ynodes_array[7][i][j]["type"] == 'predict'){
                            
                                    var group = 'preNodes';
                            
                                }else{
                                    var group = 'otherNodes';
                                }
                            
                                ynodes_vis_nodes = {id: id, label: label, group: group, cid: ynodes_array[7][i][j]["c_number"]}; 
                                ynodes_vis_edges = {from: id, to: to, arrows: 'to', group: group + 'Edges'};

                                
                            }
            
                        }
                        else if(l < ynodes_array[7].length){
                            
                            //子が子につながっている場合
                            for(var m = 0; m < ynodes_array[7].length; m++){
                                for(var n = 0; n < ynodes_array[7][m].length; n++){

                                    if(ynodes_array[7][i][j]["parent_id"] == ynodes_array[7][m][n]["node_id"] && ynodes_array[7][i][j]["c_number"] == ynodes_array[7][m][n]["c_number"]){

                                        //　toiはそのまま格納し、toi以外のノードはconcept_idがつながっているか判定
                                        if(ynodes_array[7][i][j]["type"] == 'toi' || ynodes_array[7][i][j]["type"] == 'toi_deep' || ynodes_array[7][i][j]["type"] == 'other_to_myquestion'){
                                

                                            var label = ynodes_array[7][i][j]["content"];
                                            var id =  ynodes_array[7][i][j]["c_number"] + "." + ynodes_array[7][i][j]["child"] +"."+ ynodes_array[7][i][j]["node_id"];
                                            var to = ynodes_array[7][m][n]["c_number"] + "." + ynodes_array[7][m][n]["child"] +"."+ ynodes_array[7][m][n]["node_id"];
                                            var group = 'toiNodes';

                                    
                                            ynodes_vis_nodes = {id: id, label: label, group: group, cid: ynodes_array[7][i][j]["c_number"]}; 
                                            ynodes_vis_edges = {from: id, to: to, arrows: 'to', group: group + 'Edges'};
                                            
            
                                        }
                                        else {
            
                                            var label = ynodes_array[7][i][j]["content"];
                                            var id =  ynodes_array[7][i][j]["c_number"] + "." + ynodes_array[7][i][j]["child"] +"."+ ynodes_array[7][i][j]["node_id"];
                                            var to = ynodes_array[7][m][n]["c_number"] + "." + ynodes_array[7][m][n]["child"] +"."+ ynodes_array[7][m][n]["node_id"];
            
                                            if(ynodes_array[7][i][j]["type"] == 'konkyo' || ynodes_array[7][i][j]["type"] == 'other_to_myanswer'){
                
                                                var group = 'konNodes';
                                    
                                            }
                                            else if(ynodes_array[7][i][j]["type"] == 'predict'){
                                    
                                                var group = 'preNodes';
                                    
                                            }else{
                                                var group = 'otherNodes';
                                            }
                                        
                                            ynodes_vis_nodes = {id: id, label: label, group: group, cid: ynodes_array[7][i][j]["c_number"]}; 
                                            ynodes_vis_edges = {from: id, to: to, arrows: 'to', group: group + 'Edges'};
            
                                            
                                        }
                                        
                                    }
                                }
                            }
                        }
                    }
            
                    nodes_base.push(ynodes_vis_nodes);
                    edges_base.push(ynodes_vis_edges);
                }
            }
        }

        console.log(nodes_base);
        console.log(edges_base);

        nodes.update(nodes_base);
        edges.update(edges_base);

        //　ここまでがノードの設定
        //　以下はデザインの設定

        var data = {
            nodes: nodes,
            edges: edges,
        };  

        var options = {
            physics: {
                barnesHut: {
                    gravitationalConstant: -1000, // 中心の重力 0が重力の最大
                    centralGravity: -0.1,          // 中心に集まる重力
                    springConstant: 0.01,         // エッジの弾性力
                },
                stabilization: {interations: 2000},
            },


            nodes: {
                shape: "box",
                value: 100,
                scaling: { label: { enabled: true} },
                borderWidth: 1,
            },

            edges : {
                smooth: {
                    type: 'dynamic',
                },
                length: 200,
                width: 5,
                selectionWidth: function (width) {return width*2;},
            },
            

            groups: {
                ymodelNodes: {
                    physics: {
                        barnesHut: {
                            damping: 100                 // ノードの減衰（間隔としては摩擦）
                        }
                    },
                    size: 50,
                    color: "#ffcf43",
                    shape: "ellipse",
                    value: 100,
                    scaling: { label: { enabled: true} },
                    //margin: { top: 10, right: 10, bottom: 10, left: 10 },
                },

                ymodelEdges: {
                    color: {
                        color: "#ffcf43",
                    },
                },

                noymodelNodes: {
                    physics: {
                        barnesHut: {
                            damping: 100                 // ノードの減衰（間隔としては摩擦）
                        }
                    },
                    borderWidth:4,
                    color: {
                        background: "#ffcf43",
                        highlight:{
                            background:"#ffcf43",
                            border: '#faa61f',
                        },
                        border: '#faa61f',
                    },
                    value: 100,
                    scaling: { label: { enabled: true} },
                    shape: "ellipse",
                    margin: { top: 20, right: 20, bottom: 20, left: 20 },
                },

                konNodes: {
                    color: "#FFB6C7"
                },
                
                preNodes: {
                    color: "#FF946D"
                },

                toiNodes: {
                    color: "#BFEAF5"
                },

                goriNodes:{
                    color: "#9bb8fa"
                },

                otherNodes: {
                    color: "#FF597B"
                }
            },

        };

        //出力
        var network = new vis.Network(viewId, data, options);
    }
}
