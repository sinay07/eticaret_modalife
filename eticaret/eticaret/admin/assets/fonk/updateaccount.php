<?php
include 'mysql.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];

    try {
        // Hole den Datensatz des Administrators und überprüfe das alte Passwort
        $stmt = $conn->prepare("SELECT * FROM yonetici WHERE KAYIT_ID = 1");
        $stmt->execute();
        
        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($oldPassword, $user['SIFRE'])) {
                // Das neue Passwort hashen
                $newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT);
                
                // Benutzernamen aktualisieren
                $stmt = $conn->prepare("UPDATE yonetici SET KULLANICI_ADI = :username WHERE KAYIT_ID = 1");
                $stmt->bindParam(':username', $username);
                $stmt->execute();

                // Passwort aktualisieren
                $stmt = $conn->prepare("UPDATE yonetici SET SIFRE = :newPassword WHERE KAYIT_ID = 1");
                $stmt->bindParam(':newPassword', $newPasswordHashed);
                $stmt->execute();
                
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Das alte Passwort ist falsch.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Der Administrator-Datensatz wurde nicht gefunden.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Datenbankfehler: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Ungültige oder unvollständige Daten.']);
}
?>
