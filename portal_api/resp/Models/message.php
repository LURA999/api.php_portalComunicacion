<?php
    class message
    {
        function select($obj, $name){
            if(count($obj) == 0){
                echo json_encode( array(
                'status' => "404",
                'info' => "not there ".$name,
                'container' => []) 
                );
            }else{
                echo json_encode( array(
                'status' => "200",
                'info' => "yes there ".$name,
                'container' => $obj ) 
                );  
            }
        }

        function exitoso($name){
            echo json_encode( array(
                'status' => "200",
                'info' => "successfully ".$name
                )
            );
        }

        function noExitoso($name, $e){
            echo json_encode(
                array('status' => "404",
                    'info' => "don't found ".$name,
                    'container' => $e)
            );
        }
    }
    