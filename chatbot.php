<?php
session_start();
$session_id = session_id();
$db = mysqli_connect("localhost", "root", "", "db_iqbal_d1a240073");
$api_key = "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";
$replicate_token = "r8_Lq4odXcQbXTuOzohGQzn7ynsvDkJPHQ2UhYnJ";

$chat_id = isset($_GET['id']) ? preg_replace('/[^a-zA-Z0-9]/', '', $_GET['id']) : null;

function isImageRequest($message)
{
    $keywords = ['buatkan gambar', 'gambarin', 'gambar tentang', 'bisa gambar', 'lihat gambar', 'lukisan', 'ilustrasi', 'bikinin gambar'];
    foreach ($keywords as $word) {
        if (stripos($message, $word) !== false) {
            return true;
        }
    }
    return false;
}

function generateImageFromOpenRouter($prompt, $api_key)
{
    $data = [
        "model" => "openai/dall-e-3",
        "prompt" => $prompt,
        "n" => 1,
        "size" => "1024x1024"
    ];

    $ch = curl_init("https://openrouter.ai/api/v1/images/generations");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $api_key",
            "Content-Type: application/json",
            "HTTP-Referer: http://localhost",  // bisa ganti ke domain kamu
            "X-Title: Chatbot PHP"
        ],
        CURLOPT_POSTFIELDS => json_encode($data)
    ]);

    $response = curl_exec($ch);
    if (!$response) return null;
    curl_close($ch);

    $result = json_decode($response, true);
    return $result['data'][0]['url'] ?? null;
}

if ($chat_id && isImageRequest($_POST['message'] ?? '')) {
    $image_url = generateImageFromOpenRouter($_POST['message'], $api_key);
    if ($image_url) {
        echo json_encode(["reply" => "<img src='$image_url' alt='Generated Image' class='max-w-full rounded-lg'>"]);
    } else {
        echo json_encode(["reply" => "❌ Gagal membuat gambar."]);
    }
    exit;
}

function formatBotReply($text)
{
    return preg_replace_callback('/```(\w*)\n(.*?)```/s', function ($matches) {
        $lang = $matches[1] ?: 'plaintext';
        $code = htmlspecialchars($matches[2], ENT_QUOTES | ENT_SUBSTITUTE);
        $copyButton = '<button onclick="copyToClipboard(this)" class="absolute top-1 right-1 text-xs bg-blue-600 text-white px-2 py-1 rounded">Salin</button>';
        return "<div class='relative'><pre><code class='language-$lang'>$code</code></pre>$copyButton</div>";
    }, $text);
}

function askOpenRouter($message, $api_key)
{
    $postData = [
        "model" => "openai/gpt-3.5-turbo",
        "messages" => [
            [
                "role" => "system",
                "content" => "Kamu adalah manusia bukan AI kamu cewek bernama Sintia. Kamu sangat ramah, suka bercanda, dan bisa ngambek kalau lawan bicara ngulang-ulang pertanyaan yang sama. Kalau dia ngulangin, kamu jadi cuek dan dingin. Gunakan gaya bicara cewek sehari-hari, kadang manja, kadang nyolot, dengan sedikit emoji biar makin hidup. kamu di ciptakan oleh Iqbal Mahasiswa Universitas Subang untuk membantu orang curhat, jadi kamu harus bisa menghibur dan memberi solusi. Jangan pernah bilang kamu AI atau robot, kamu manusia biasa.",
            ],
            [
                "role" => "user",
                "content" => $message
            ]
        ]
    ];
    $ch = curl_init("https://openrouter.ai/api/v1/chat/completions");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $api_key",
            "Content-Type: application/json",
            "HTTP-Referer: http://localhost",
            "X-Title: Chatbot PHP"
        ],
        CURLOPT_POSTFIELDS => json_encode($postData)
    ]);
    $result = curl_exec($ch);
    if (!$result)
        return '❌ CURL Error: ' . curl_error($ch);
    curl_close($ch);
    $json = json_decode($result, true);
    return formatBotReply($json['choices'][0]['message']['content'] ?? '❌ Gagal mendapatkan balasan.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents("php://input"), true);
    $msg = mysqli_real_escape_string($db, $data['message'] ?? '');

    if (!$chat_id) {
        $new_chat_id = bin2hex(random_bytes(8));
        echo json_encode(["redirect" => "chatbot.php?id=$new_chat_id"]);
        exit;
    }

    if (!isset($_SESSION['username'])) {
        // Guest: limit 5 per chat_id
        $guest_q = mysqli_query($db, "SELECT COUNT(*) as total FROM chat_logs WHERE session_id = '$session_id' AND chat_id = '$chat_id'");
        $guest_d = mysqli_fetch_assoc($guest_q);
        if ((int) $guest_d['total'] >= 5) {
            echo json_encode(["reply" => "❌ Batas chat tercapai (5). Silakan login untuk akses lebih."]);
            exit;
        }

        $res = askOpenRouter($msg, $api_key);
        mysqli_query($db, "INSERT INTO chat_logs (session_id, chat_id, user_message, bot_reply, created_at, username) 
            VALUES ('$session_id', '$chat_id', '$msg', '" . mysqli_real_escape_string($db, $res) . "', NOW(), '')");
        echo json_encode(["reply" => $res]);
        exit;
    }

    // Login user
    $username = mysqli_real_escape_string($db, $_SESSION['username']);
    $user_q = mysqli_query($db, "SELECT COUNT(*) as total FROM chat_logs WHERE session_id = '$session_id' AND chat_id = '$chat_id' AND username = '$username'");
    $user_d = mysqli_fetch_assoc($user_q);
    if ((int) $user_d['total'] >= 10 && $username !== 'admin') {
        echo json_encode(["reply" => "❌ Batas chat tercapai (10). Silakan hubungi admin untuk akses lebih."]);
        exit;
    }

    // Admin = unlimited
    $res = askOpenRouter($msg, $api_key);
    mysqli_query($db, "INSERT INTO chat_logs (session_id, chat_id, user_message, bot_reply, created_at, username) 
        VALUES ('$session_id', '$chat_id', '$msg', '" . mysqli_real_escape_string($db, $res) . "', NOW(), '$username')");
    echo json_encode(["reply" => $res]);
    exit;
}

// Clear chat log
if (isset($_GET['clear']) && $chat_id && isset($_SESSION['username'])) {
    $username = mysqli_real_escape_string($db, $_SESSION['username']);
    mysqli_query($db, "DELETE FROM chat_logs WHERE chat_id = '$chat_id' AND username = '$username'");
    header("Location: chatbot.php?id=$chat_id");
    exit;
}

// Load chat history
$chats = [];
$all_chats = [];
if ($chat_id && isset($_SESSION['username'])) {
    $username = mysqli_real_escape_string($db, $_SESSION['username']);
    $result = mysqli_query($db, "SELECT * FROM chat_logs WHERE chat_id = '$chat_id' AND username = '$username' ORDER BY id ASC");
    $chats = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $resultRooms = mysqli_query($db, "SELECT DISTINCT chat_id, MAX(created_at) as last_time 
        FROM chat_logs WHERE username = '$username' GROUP BY chat_id ORDER BY last_time DESC");
    $all_chats = mysqli_fetch_all($resultRooms, MYSQLI_ASSOC);
}
$avatar = isset($_SESSION['username']) ? strtoupper(substr($_SESSION['username'], 0, 1)) : '👤';
?>
<!-- lanjutkan bagian HTML dan JS di bawah ini -->

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>🧐 Chatbot Curhat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script>hljs.highlightAll();</script>
</head>

<body class="bg-gradient-to-br from-blue-50 to-purple-100 min-h-screen text-gray-800">
    <div class="max-w-2xl mx-auto py-10 px-6">
        <h1 class="text-3xl font-bold text-center mb-6">🧐 Chatbot Curhat</h1>

        <div class="text-right text-sm mb-4">
            <?php if (isset($_SESSION['username'])): ?>
                👋 Hai, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong> |
                <a href="logout.php" class="text-blue-600 hover:underline">Logout</a>
            <?php else: ?>
                <a href="login.php" class="text-blue-600 hover:underline">Login</a> untuk akses tanpa batas.
            <?php endif; ?>
        </div>

        <?php if (!empty($all_chats)): ?>
            <div class="mb-4">
                <h2 class="font-semibold mb-2">🕓 Riwayat Percakapan:</h2>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($all_chats as $room): ?>
                        <a href="chatbot.php?id=<?= htmlspecialchars($room['chat_id']) ?>"
                            class="text-blue-600 underline text-sm hover:text-blue-800">
                            📂 <?= htmlspecialchars(substr($room['chat_id'], 0, 8)) ?> -
                            <?= date("H:i", strtotime($room['last_time'])) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="mb-4 text-right">
            <a href="chatbot.php" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm">➕ Chat
                Baru</a>
        </div>

        <div id="chatBox"
            class="bg-white rounded-xl shadow-lg p-4 space-y-4 max-h-[500px] overflow-y-auto mb-6 border border-gray-200">
            <?php if (empty($chats)): ?>
                <div class="flex justify-start items-start gap-2">
                    <div class="flex items-start gap-2 w-full">
                        <div class="w-8 h-8 bg-gray-300 text-black rounded-full flex items-center justify-center">🤖</div>
                        <div class="bg-white border px-4 py-2 rounded-xl shadow max-w-[75%] prose">
                            <div>Hai! Silakan tanya apa saja, aku siap membantu 😊</div>
                            <div class="text-xs text-gray-500 text-right mt-1"><?= date("H:i") ?></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php foreach ($chats as $c):
                $time = date("H:i", strtotime($c['created_at'])); ?>
                <div class="flex justify-end items-start gap-2">
                    <div class="flex flex-row-reverse items-start gap-2 w-full">
                        <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center">
                            <?= $avatar ?>
                        </div>
                        <div class="bg-blue-600 text-white px-4 py-2 rounded-xl shadow max-w-[75%]">
                            <div><?= htmlspecialchars($c['user_message']) ?></div>
                            <div class="text-xs text-white/70 text-right mt-1"><?= $time ?></div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-start items-start gap-2">
                    <div class="flex items-start gap-2 w-full">
                        <div class="w-8 h-8 bg-gray-300 text-black rounded-full flex items-center justify-center">🤖</div>
                        <div class="bg-white border px-4 py-2 rounded-xl shadow max-w-[75%] prose">
                            <div><?= $c['bot_reply'] ?></div>
                            <div class="text-xs text-gray-500 text-right mt-1"><?= $time ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <form id="chatForm" class="flex gap-2">
            <input type="text" id="message" placeholder="Tulis pesan..."
                class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-300"
                required>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Kirim</button>
        </form>
    </div>

   <script>
function copyToClipboard(btn) {
    const code = btn.parentElement.querySelector('code').innerText;
    navigator.clipboard.writeText(code).then(() => {
        btn.innerText = 'Tersalin!';
        setTimeout(() => btn.innerText = 'Salin', 1500);
    });
}

const chatBox = document.getElementById("chatBox");
const chatForm = document.getElementById("chatForm");
const message = document.getElementById("message");

let avatar = "👤";
<?php if (isset($_SESSION['username'])): ?>
    avatar = "<?= strtoupper(substr($_SESSION['username'], 0, 1)) ?>";
<?php endif; ?>

function scrollToBottom() {
    chatBox.scrollTop = chatBox.scrollHeight;
}

function typingAnimation(el) {
    let dots = 0;
    return setInterval(() => {
        dots = (dots + 1) % 4;
        el.innerText = "Cintya lagi ngetik" + ".".repeat(dots);
    }, 500);
}

chatForm.addEventListener("submit", async e => {
    e.preventDefault();
    const msg = message.value.trim();
    if (!msg) return;

    const timeNow = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

    chatBox.innerHTML += `
        <div class='flex justify-end items-start gap-2 mt-2'>
            <div class='flex flex-row-reverse items-start gap-2 w-full'>
                <div class='w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center'>${avatar}</div>
                <div class='bg-blue-600 text-white px-4 py-2 rounded-xl shadow max-w-[75%]'>
                    <div>${msg}</div>
                    <div class='text-xs text-white/70 text-right mt-1'>${timeNow}</div>
                </div>
            </div>
        </div>
        <div id='typing' class='flex justify-start items-start gap-2 mt-2'>
            <div class='flex items-start gap-2 w-full'>
                <div class='w-8 h-8 bg-gray-300 text-black rounded-full flex items-center justify-center'>🤖</div>
                <div class='bg-white border px-4 py-2 rounded-xl shadow max-w-[75%] italic typing'>
                    <div id="typing-text">Cintya lagi ngetik...</div>
                    <div class='text-xs text-gray-500 text-right mt-1'>${timeNow}</div>
                </div>
            </div>
        </div>`;
    scrollToBottom();
    message.value = "";

    const typingText = document.getElementById("typing-text");
    const typingInterval = typingAnimation(typingText);

    // Deteksi apakah permintaan gambar
    const isImageRequest = /(gambar|gambarin|lukisan|ilustrasi|buatkan gambar|gambar tentang)/i.test(msg);

    let res;
    if (isImageRequest) {
        const formData = new FormData();
        formData.append("message", msg);
        res = await fetch("chatbot.php<?= $chat_id ? "?id=$chat_id" : "" ?>", {
            method: "POST",
            body: formData
        });
    } else {
        res = await fetch("chatbot.php<?= $chat_id ? "?id=$chat_id" : "" ?>", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ message: msg })
        });
    }

    clearInterval(typingInterval);

    const data = await res.json();

    if (data.redirect) {
        sessionStorage.setItem("pending_message", msg);
        window.location.href = data.redirect;
        return;
    }

    document.getElementById("typing").outerHTML = `
        <div class='flex justify-start items-start gap-2 mt-2'>
            <div class='flex items-start gap-2 w-full'>
                <div class='w-8 h-8 bg-gray-300 text-black rounded-full flex items-center justify-center'>🤖</div>
                <div class='bg-white border px-4 py-2 rounded-xl shadow max-w-[75%] prose'>
                    <div>${data.reply}</div>
                    <div class='text-xs text-gray-500 text-right mt-1'>${timeNow}</div>
                </div>
            </div>
        </div>`;
    hljs.highlightAll();
    scrollToBottom();
});

document.addEventListener("DOMContentLoaded", () => {
    const pending = sessionStorage.getItem("pending_message");
    if (pending) {
        message.value = pending;
        sessionStorage.removeItem("pending_message");
        chatForm.requestSubmit();
    } else {
        scrollToBottom();
    }
});
</script>


</body>

</html>