<?php
    namespace Support\Collections;

    /**
    * ArrayCollection short summary.
    *
    * ArrayCollection description.
    *
    * @version 0.01
    * @author Brayan Alexis Angulo R
    */

    class ArrayCollection{

        public static function Except( array $collection, string ...$keys ){

            $keyCollection = array_map( function( $val )
            {
                return strtolower( $val );

            }, array_keys( $collection ));

            $valuesCollection = array_values( $collection );

            $newCollection = array_combine( $keyCollection, $valuesCollection );

            foreach( $keys as $key )
            {
                unset( $newCollection[ strtolower( $key ) ] );
            }

            return $newCollection;
        }

        /**
         * Sumary of ContainKeys
         * @param int[]|string[] $keys
         */

         public static function ContainKeys( array $collection,...$keys ):array
         {
            $notFound = [];

            $keyCollection = array_map( function ( $val )
            {
                return strtolower( $val );

            }, array_keys( $collection ));

            foreach ( $keys as $key )
            {
                if( !in_array( strtolower( $key ), $keyCollection ) )
                {

                    $notFound[] = $keys;

                }
            }

            return $notFound;

         }

    }
?>