<?php require 'db.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการคำสำคัญและคำตอบ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">📋 จัดการคำสำคัญและคำตอบ</h1>
        <a href="add.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">➕ เพิ่มข้อมูล</a>

        <?php
        // แสดงข้อความแจ้งเตือนจาก URL (SweetAlert)
        if (isset($_GET['msg'])) {
            $msg = $_GET['msg'];
            $title = '';
            $icon = '';
            if ($msg == 'added') {
                $title = 'เพิ่มข้อมูลสำเร็จ';
                $icon = 'success';
            } elseif ($msg == 'updated') {
                $title = 'แก้ไขข้อมูลสำเร็จ';
                $icon = 'success';
            } elseif ($msg == 'deleted') {
                $title = 'ลบข้อมูลสำเร็จ';
                $icon = 'success';
            } elseif ($msg == 'error') {
                $title = 'เกิดข้อผิดพลาด';
                $icon = 'error';
            }
            if ($title) {
                echo "<script>
                    Swal.fire({
                        title: '$title',
                        icon: '$icon',
                        timer: 1500,
                        showConfirmButton: false
                    });
                </script>";
            }
        }
        ?>

        <table class="w-full mt-6 border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">ID</th>
                    <th class="border p-2">คำสำคัญ</th>
                    <th class="border p-2">คำตอบ</th>
                    <th class="border p-2">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT * FROM responses ORDER BY id DESC");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td class='border p-2'>{$row['id']}</td>";
                    echo "<td class='border p-2'>{$row['keyword']}</td>";
                    echo "<td class='border p-2'>{$row['answer']}</td>";
                    echo "<td class='border p-2'>
                            <a href='edit.php?id={$row['id']}' class='text-blue-600 hover:underline mr-2'>แก้ไข</a>
                            <a href='#' onclick='confirmDelete({$row['id']})' class='text-red-600 hover:underline'>ลบ</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: 'ข้อมูลนี้จะถูกลบอย่างถาวร!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'delete.php?id=' + id;
                }
            });
        }
    </script>
</body>
</html>
