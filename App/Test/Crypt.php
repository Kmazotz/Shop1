<?php 
    
    checkValue( $_POST['imgName'] );

    function checkValue( $val ){

        if( strlen( $val ) > 0 ){

            ImgEncrypt( $val );

            return true;
        }

        return false;

    }

    function ImgEncrypt( $val ){
        
        $str = md5( $val );

        return json_encode(array('response' => $str ));
    }    

?>