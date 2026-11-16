<?php
require __DIR__ . '/../config.php';

$idVille = filter_input(INPUT_GET, 'id_ville', FILTER_VALIDATE_INT);
if ($idVille === false || $idVille === null) {
    reponse(['success' => false, 'message' => 'id_ville invalide.'], 400);
}

// 1) Récupérer les coordonnées de la ville
$pdo  = getPDO();
$stmt = $pdo->prepare(
    'SELECT v.nom AS ville, p.nom AS pays, v.latitude, v.longitude
     FROM ville v JOIN pays p ON p.id_pays = v.id_pays
     WHERE v.id_ville = :id'
);
$stmt->execute([':id' => $idVille]);
$ville = $stmt->fetch();
if (!$ville) {
    reponse(['success' => false, 'message' => 'Ville non trouvée.'], 404);
}

// 2) Interroger Open-Meteo avec les coordonnées
$url = 'https://api.open-meteo.com/v1/forecast?' . http_build_query([
    'latitude'      => $ville['latitude'],
    'longitude'     => $ville['longitude'],
    'current'       => 'temperature_2m,apparent_temperature,wind_speed_10m',
    'daily'         => 'temperature_2m_max,temperature_2m_min',
    'timezone'      => 'auto',
    'forecast_days' => 1,
]);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 10,
]);
$json = curl_exec($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($json === false || $http !== 200) {
    reponse(['success' => false, 'message' => "Erreur lors de l'appel à Open-Meteo."], 502);
}
$m = json_decode($json, true);

// 3) Renvoyer au frontend uniquement ce qui est utile
reponse([
    'success'     => true,
    'ville'       => $ville['ville'],
    'pays'        => $ville['pays'],
    'latitude'    => (float) $ville['latitude'],
    'longitude'   => (float) $ville['longitude'],
    'temperature' => $m['current']['temperature_2m'],
    'ressenti'    => $m['current']['apparent_temperature'],
    'vent'        => $m['current']['wind_speed_10m'],
    'min'         => $m['daily']['temperature_2m_min'][0],
    'max'         => $m['daily']['temperature_2m_max'][0],
]);
