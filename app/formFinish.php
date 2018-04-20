<?php
    require_once 'Tools.php';
    $time = time();

    $databaseTools = new databaseTools();
    $database = $databaseTools->databaseInit();
    
    $forms = $database->select('waiting_finish','form_id',array(
        'expected_settlement_time[<=]'=>$time
    ));

    $result = $database->select('form_basic',array('formID','masterUID','serverUID','distance','flag'),array(
        'AND'=>array(
            'formID'=>$forms,
            'flag'=>2
        )
    ));

    // $masterArray = array(array(),array());
    // $serverArray = array(array(),array());
    // $forms = array();

    // foreach($result as $data){
    //     array_push($masterArray[0],$data['masterUID']);


    //     array_push($serverArray,array($data['serverUID'],$data['distance']));
    //     array_push($forms,$data['formID']);
    // }

    
    for($i = 0;$i < sizeof($forms);$i++){
        $database->update('user_basic',array(
            'contribution[-]'=>$result[$i]['distance'],
            'abs_contribution[+]'=>$result[$i]['distance']
        ),array('UID'=>$result[$i]['masterUID']));
        
        $database->update('user_basic',array(
            'contribution[+]'=>$result[$i]['distance'],
            'abs_contribution[+]'=>$result[$i]['distance']
        ),array('UID'=>$result[$i]['serverUID']));
    }
    
    $database->update('form_basic',array('flag'=>3),array('formID'=>$forms));
    $database->delete('waiting_finish',array('form_id'=>$forms));

    

?>