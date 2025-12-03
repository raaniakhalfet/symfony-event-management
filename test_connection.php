<?php
// test_connection.php
require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

$databaseUrl = $_ENV['DATABASE_URL'];
echo "📊 Database URL: " . $databaseUrl . "\n\n";

// Parse l'URL
$parsed = parse_url($databaseUrl);
$host = $parsed['host'] ?? '127.0.0.1';
$port = $parsed['port'] ?? 3306;
$user = $parsed['user'] ?? 'root';
$pass = $parsed['pass'] ?? '';
$dbname = trim($parsed['path'] ?? '', '/');

echo "🔧 Détails:\n";
echo "- Host: $host\n";
echo "- Port: $port\n";
echo "- User: $user\n";
echo "- Pass: " . (empty($pass) ? '(vide)' : '***') . "\n";
echo "- Database: " . ($dbname ?: '(non spécifiée)') . "\n\n";

// Test de connexion
try {
    $dsn = "mysql:host=$host;port=$port" . ($dbname ? ";dbname=$dbname" : "");
    echo "🔌 DSN: $dsn\n\n";
    
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion MySQL réussie!\n\n";
    
    // Liste des bases
    echo "🗄️ Bases de données disponibles:\n";
    $stmt = $pdo->query("SHOW DATABASES");
    $databases = [];
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        $databases[] = $row[0];
        echo "- " . $row[0] . "\n";
    }
    
    echo "\n";
    
    // Vérifiez si notre base existe
    if ($dbname && !in_array($dbname, $databases)) {
        echo "⚠️ ATTENTION: La base '$dbname' n'existe pas!\n";
    }
    
    // Si une base est spécifiée, voir ses tables
    if ($dbname && in_array($dbname, $databases)) {
        echo "📋 Tables dans '$dbname':\n";
        try {
            $pdo->exec("USE $dbname");
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (empty($tables)) {
                echo "(aucune table)\n";
            } else {
                foreach ($tables as $table) {
                    echo "- $table\n";
                }
            }
        } catch (Exception $e) {
            echo "❌ Erreur avec la base '$dbname': " . $e->getMessage() . "\n";
        }
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion PDO: " . $e->getMessage() . "\n";
    echo "Code d'erreur: " . $e->getCode() . "\n";
}
