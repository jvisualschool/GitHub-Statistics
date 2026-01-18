<?php
// GitHub GraphQL Proxy (Token stays on server)
// Endpoint: /api/github-graphql.php
// Upstream: https://api.github.com/graphql
// Docs: https://docs.github.com/en/graphql

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *'); // 운영에서는 https://jvibeschool.org 등으로 제한 권장
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(204);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['error' => 'Method Not Allowed']);
  exit;
}

$tokenFile = '/home/bitnami/secure/github_token.txt';
if (!file_exists($tokenFile)) {
  http_response_code(500);
  echo json_encode(['error' => 'Token file not found']);
  exit;
}
$token = trim(file_get_contents($tokenFile));
if ($token === '') {
  http_response_code(500);
  echo json_encode(['error' => 'Token is empty']);
  exit;
}

$raw = file_get_contents('php://input');
$payload = json_decode($raw, true);
if (!$payload || !isset($payload['query'])) {
  http_response_code(400);
  echo json_encode(['error' => 'Invalid JSON or missing query']);
  exit;
}

$query = $payload['query'];
$variables = $payload['variables'] ?? new stdClass();

$body = json_encode([
  'query' => $query,
  'variables' => $variables
], JSON_UNESCAPED_SLASHES);

$ch = curl_init('https://api.github.com/graphql');
curl_setopt_array($ch, [
  CURLOPT_POST => true,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => [
    'Content-Type: application/json',
    'User-Agent: jvibeschool-proxy',
    'Authorization: bearer ' . $token
  ],
  CURLOPT_POSTFIELDS => $body,
]);

$res = curl_exec($ch);
$err = curl_error($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($res === false) {
  http_response_code(502);
  echo json_encode(['error' => 'cURL error', 'detail' => $err]);
  exit;
}

http_response_code($code);
echo $res;
