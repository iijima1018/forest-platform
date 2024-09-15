async function displayResearchContent() {
    let bubble = document.getElementById("speechBubble");

    // 吹き出し要素が存在しない場合、新しく作成
    if (!bubble) {
        bubble = document.createElement('div');
        bubble.id = "speechBubble";
        bubble.className = "speech-bubble";

        // ボタンの下に吹き出しを配置するために body に追加
        document.body.appendChild(bubble);

        // ドラッグ機能の初期化
        initializeDrag(bubble);
    }

    try {
        // JSONファイルからテキストを取得
        const response = await fetch('css/displayResearchContent.json');
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const data = await response.json();
        bubble.innerHTML = data.researchContent;
    } catch (error) {
        console.error('There has been a problem with your fetch operation:', error);
        bubble.innerHTML = 'データの読み込みに失敗しました。詳細: ' + error.message;
    }

    // 吹き出しを表示
    bubble.style.display = "block";

    // ボタンの位置を取得
    const button = document.querySelector(".button4");
    const buttonRect = button.getBoundingClientRect();

    // 吹き出しの位置をボタンの真下に設定
    bubble.style.position = "absolute";
    bubble.style.left = `${buttonRect.left}px`;  // ボタンの左端に合わせる
    bubble.style.top = `${buttonRect.bottom + 35}px`;  // ボタンの下に配置し、少し余裕を持たせる
}

function initializeDrag(element) {
    let offsetX, offsetY, startX, startY;

    element.onmousedown = function(event) {
        // マウスの初期位置を記録
        startX = event.clientX;
        startY = event.clientY;
        offsetX = element.offsetLeft;
        offsetY = element.offsetTop;

        // マウスムーブとマウスアップのイベントリスナーを追加
        document.onmousemove = function(event) {
            let newX = event.clientX - startX + offsetX;
            let newY = event.clientY - startY + offsetY;
            element.style.left = `${newX}px`;
            element.style.top = `${newY}px`;
        };

        document.onmouseup = function() {
            document.onmousemove = null;
            document.onmouseup = null;
        };

        // デフォルトのドラッグ動作を防止
        return false;
    };
}
