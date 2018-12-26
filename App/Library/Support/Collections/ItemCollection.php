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

    use JsonSerializable;

    class ItemCollection implements JsonSerializable
    {
        public function __construct( ...$items )
        {
            $this->items = $items;
        }

        public static function From( $collection ):ItemCollection
        {
            return new ItemCollection();
        }

        public function ToArray(){

            return [];

        }
        #region JsonSerializable Members

        /**
         * Specify data which should be serialized to JSON
         * Serializes the object to a value that can be serialized natively by json_encode() .
         *
         * @return mixed
         */

         function jsonSerialize()
         {
            return $this->ToArray();
         }

         #endregion

    }

?>