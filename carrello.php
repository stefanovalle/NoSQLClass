<?php


ini_set('display_errors', 1);
$dsn = 'pgsql:host=localhost;dbname=ecommerce';


$id = $_GET['id'];

try {

  $db = new PDO($dsn , 'postgres', 'zf2');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  $sql = "SELECT prodotto.*, macrocategoria.nome as macrocategoria, variante.nome as variante,
          categoria.nome as categoria FROM prodotto join categoria on categoria.id = prodotto.categoria_id 
          join macrocategoria on macrocategoria.id = categoria.macrocategoria_id 
          join prodottovariante on prodotto.id = prodottovariante.id_prodotto 
          join variante on prodottovariante.id_variante = variante.id
          WHERE prodotto.id = ". $id ;

  $st = $db->query($sql);
  $row = $st->fetch();
  $item = $row;
  $varianti = $item['variante'];
  while ($row = $st->fetch()) {
    $varianti .= ', '.$row['variante'];
  }
  $item['variante'] = $varianti;
}
  catch (PDOException $e) {
    print $e->getMessage();
}
?>
<h1>Nome Prodotto: <?php echo $item['nome']; ?></h1>
    
<a href="/ordina.php?id=<?php echo $item['id']; ?>">Compra</a>

<br /><br />

<a href="/detail.php?id=<?php echo $item['id']; ?>">Back</a>