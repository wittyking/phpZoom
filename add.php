<?php require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $keyword = $_POST['keyword'];
    $answer = $_POST['answer'];

    if (!empty($keyword) && !empty($answer)) {
        $stmt = $pdo->prepare("INSERT INTO responses (keyword, answer) VALUES (?, ?)");
        if ($stmt->execute([$keyword, $answer])) {
            header('Location: index.php?msg=added');
            exit;
        } else {
            $error = 'เกิดข้อผิดพลาดในการบันทึก';
        }
    } else {
        $error = 'กรุณากรอกข้อมูลให้ครบ';
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มข้อมูล</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">➕ เพิ่มคำสำคัญและคำตอบ</h2>
        <?php if (isset($error)): ?>
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?= $error ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-4">
                <label class="block text-gray-700">คำสำคัญ</label>
                <input type="text" name="keyword" class="w-full border p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">คำตอบ</label>
                <textarea name="answer" rows="4" class="w-full border p-2 rounded" required></textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <a href="index.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">ยกเลิก</a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">บันทึก</button>
            </div>
        </form>
    </div>
</body>
</html>
