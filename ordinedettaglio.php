<?php


ini_set('display_errors', 1);

$id = $_GET['id'];

/*
 *  lettura dati da couch
 */

// configurazione libreria Sag
require_once('./sag/src/Sag.php');
$sag = new Sag('127.0.0.1', '5984');
 
// selezione database
$sag->setDatabase('ordini');
 
try {
    
    // get dati ordine
    $risultato = $sag->get($id);
    
    $ordine = $risultato->body;
    
} catch(SagCouchException $e) {
    
    //The requested post doesn't exist - oh no!
    if($e->getCode() == "404") {
        $e = new Exception("Documento inesistente.");
    }
 
    throw $e;
}
catch(SagException $e) {
    
    //We sent something to Sag that it didn't expect.
    error_log('Programmer error: '.$e->getMessage());
    
}

?>
<h1>NoSQL E-Commerce</h1>

<h2>Ordine <?php echo $id ?></h2>

<p>Cliente: <?php echo $ordine->cliente->nome . ' ' . $ordine->cliente->cognome; ?></p>
<p>Totale: <?php echo $ordine->totale; ?></p>

<h3>Lista prodotti</h3>
<table>
    <thead>
        <td>Nome</td>
        <td>Prezzo</td>
    </thead>
<?php
foreach($ordine->prodotti as $row) {
      ?>
    <tr>
        <td><?php echo $row->nome; ?></td>
        <td><?php echo $row->prezzo; ?></td>
    </tr>
      <?php  } ?>
</table>

<a href="/ordini.php">Back</a>