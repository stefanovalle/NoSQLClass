<h1>NoSQL E-Commerce</h1>

<h2>Tutti gli articoli...</h2>
<table>
    <thead>
        <td>Macrocategoria</td>
        <td>Categoria</td>
        <td>Prodotto</td>
        <td>Prezzo</td>
        <td>Acquisti</td>
        <td>Data Arrivo</td>
        <td>Dettaglio</td>
    </thead>
<?php

ini_set('display_errors', 1);
$dsn = 'pgsql:host=localhost;dbname=ecommerce';

try {

  $db = new PDO($dsn , 'postgres', 'zf2');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  $sql = 'SELECT prodotto.*, macrocategoria.nome as macrocategoria, 
          categoria.nome as categoria FROM prodotto join categoria on categoria.id = prodotto.categoria_id 
          join macrocategoria on macrocategoria.id = categoria.macrocategoria_id 
          ORDER by prodotto.dataarrivo DESC, categoria.nome, prodotto.nome LIMIT 10';

  $start = microtime(true);

  foreach($db->query($sql) as $row){  
      ?>
    <tr>
    <td><?php echo $row['macrocategoria']; ?></td>
        <td><?php echo $row['categoria']; ?></td>
        <td><?php echo $row['nome']; ?></td>
        <td><?php echo $row['prezzo']; ?></td>
        <td><?php echo $row['venduti']; ?></td>
        <td><?php echo $row['dataarrivo']; ?></td>
        <td><a href="/detail.php?id=<?php echo $row['id']; ?>">Vedi</a></td>
    </tr>
      <?php
  }
  
  $time_taken = microtime(true) - $start;
  
}
  catch (PDOException $e) {
    print $e->getMessage();
}
?>
</table>

<?php echo "Time taken: " . $time_taken; ?>