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

  $start = microtime(true);
  
  $st = $db->query($sql);
  $row = $st->fetch();
  
}
  catch (PDOException $e) {
    print $e->getMessage();
}
?>
<h1>Scheda Prodotto: <?php echo $row['nome']; ?></h1>
<p>Prezzo: <?php echo $row['prezzo']; ?></p>
<p>Venduti: <?php echo $row['venduti']; ?></p>
<p>Disp. Dal: <?php echo $row['dataarrivo']; ?></p>
<p>Varianti: <?php echo $row['variante']; ?>
    
<?php  
    while ($row = $st->fetch()) {
          echo ', '.$row['variante'];
        }
    $time_taken = microtime(true) - $start;
?>
</p>

<?php echo "Time taken: " . $time_taken; ?>

<br />
<a href="/index.php">Back</a>