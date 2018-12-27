<?php

    namespace Support\Collections;

    /**
    * ArrayCollection short summary.
    *
    * ArrayCollection description.
    *
    * @version 0.1
    * @author Brayan Alexis Angulo R
    */

    use Support\Objects\ObjectContainer;

    class ObjectCollection extends ItemCollection
    {

        /**
         * Summary of $objectCollection
         * @var ObjectContainer[]
         */

        private $objectCollection;

        public function __construct( ObjectContainer ...$items )
        {

            $this->objectCollection = $items;

        }

        public function ToArray()
        {

            $collection = [];

            foreach($this->objectCollection as $object)
            {

                $collection = $object->ToArray();

            }

            return $collection;

        }

    }    

?>