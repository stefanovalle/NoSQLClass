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
  
}
  catch (PDOException $e) {
    print $e->getMessage();
}


/*
 *  inserimento dati su couch
 */

// configurazione libreria Sag
require_once('./sag/src/Sag.php');
$sag = new Sag('127.0.0.1', '5984');
 
// selezione database
$sag->setDatabase('ordini');
 
try {
    
    // creazione array con dati ordine
    $dati = array('_id' => 'ordine_' . time(),
                  'cliente' => array('nome' => 'Stefano',
                                     'cognome' => 'Valle'),
                  'prodotti' => array($item['id'] => array('nome'     => $item['nome'],
                                                           'quantita' => 1)
                  ));
    
    // inserimento ordine
    $risultato = $sag->post($dati);
    
    if ($risultato->body->ok) {
        echo 'Ordine inserito con successo!';
    } else {
        echo "Problemi nell'inserimento dell'ordine";
        print_r($risultato->body);
    }
    
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
