<?php

require 'predis/lib/Predis/Autoloader.php';
Predis\Autoloader::register();

ini_set('display_errors', 1);
$dsn = 'pgsql:host=localhost;dbname=ecommerce';


$id = $_GET['id'];

try {

  $db = new PDO($dsn , 'postgres', 'zf2');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
try {
    $redis = new Predis\Client();
/*
    $redis = new PredisClient(array(
        "scheme" => "tcp",
        "host" => "127.0.0.1",
        "port" => 6379));
*/
    echo "Successfully connected to Redis";
}
catch (Exception $e) {
    echo "Couldn't connected to Redis";
    echo $e->getMessage();
}
  
   $start = microtime(true);
 
  if (!$redis->exists($id)) {
      
    echo "Not cached<br />";
  
    $sql = "SELECT prodotto.*, macrocategoria.nome as macrocategoria, variante.nome as variante,
            categoria.nome as categoria FROM prodotto join categoria on categoria.id = prodotto.categoria_id 
            join macrocategoria on macrocategoria.id = categoria.macrocategoria_id 
            join prodottovariante on prodotto.id = prodottovariante.id_prodotto 
            join variante on prodottovariante.id_variante = variante.id
            WHERE prodotto.id = ". $id ;

    $st = $db->query($sql);
    $row = $st->fetch();
    $item = $row;

    $varianti = $row['variante'];
    while ($row = $st->fetch()) {
            $varianti .= ', ' . $row['variante'];
    }
    
    $item['variante'] = $varianti;
    
  } else {
      $item = json_decode($redis->get($id), true);
  }
  
  $data = json_encode($item, true);
  $redis->set($id, $data) or die ("Failed to save data at the server");
  // $redis->expireat($id, 1000);

  $time_taken = microtime(true) - $start;  
          
}
  catch (PDOException $e) {
    print $e->getMessage();
}
?>
<h1>Scheda Prodotto: <?php echo $item['nome']; ?></h1>
<p>Prezzo: <?php echo $item['prezzo']; ?></p>
<p>Venduti: <?php echo $item['venduti']; ?></p>
<p>Disp. Dal: <?php echo $item['dataarrivo']; ?></p>
<p>Varianti: <?php echo $item['variante']; ?>
</p>

<?php echo "Time taken: " . $time_taken; ?>

<br />
<a href="/index.php">Back</a>