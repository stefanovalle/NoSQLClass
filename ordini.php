<?php


ini_set('display_errors', 1);

/*
 *  lettura dati da couch
 */

// configurazione libreria Sag
require_once('./sag/src/Sag.php');
$sag = new Sag('127.0.0.1', '5984');
 
// selezione database
$sag->setDatabase('ordini');
 
try {
    
    // get dati da vista
    $risultato = $sag->get('_design/listaordini/_view/listaordini');
    
    $listaordini = $risultato->body->rows;
    
} catch(SagCouchException $e) {
    
    //The requested post doesn't exist - oh no!
    if($e->getCode() == "404") {
        $e = new Exception("That post doesn't exist.");
    }
 
    throw $e;
}
catch(SagException $e) {
    
    //We sent something to Sag that it didn't expect.
    error_log('Programmer error: '.$e->getMessage());
    
}

?>
<h1>NoSQL E-Commerce</h1>

<h2>Lista ordini</h2>
<table>
    <thead>
        <td>ID</td>
        <td>Cliente</td>
        <td>Totale</td>
        <td>Dettaglio</td>
    </thead>
<?php
foreach($listaordini as $row) {
      ?>
    <tr>
        <td><?php echo $row->id; ?></td>
        <td><?php echo $row->value->cliente->nome . ' ' . $row->value->cliente->cognome; ?></td>
        <td><?php echo $row->value->totale; ?></td>
        <td><a href="/ordinedettaglio.php?id=<?php echo $row->id; ?>">Vedi</a></td>
    </tr>
      <?php  } ?>
</table>