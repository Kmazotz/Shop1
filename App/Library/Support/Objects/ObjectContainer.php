<?php
    namespace Support\Objects;

    /**
    * ArrayCollection short summary.
    *
    * ArrayCollection description.
    *
    * @version 0.1
    * @author Brayan Alexis Angulo R
    */

    use Traversable;
    use ArrayIterator;
    use JsonSerializable;
    use IteratorAggregate;

    class ObjectContainer implements JsonSerializable, IteratorAggregate
    {

        /**
         * Summary of $properties
         * @var Property[]
         */

        private $properties;

        /**
         * Summary of AddProperties
         * @param array $properties
         */

        public function AddProperties(Property...$properties): void
        {

            $this->properties = array_merge( $this->properties, $properties );

        }

        /**
         * Summary of BuilObject
         * @param string[] $properties
         * @param array $values
         * @return ObjectContainer
         */

        public static function BuildObjects($properties, ...$values): ObjectContainer
        {
            $keys = array_values( $properties );
            $vals = array_values( $values );

            $properties = [];

            for ($i=0; $i < count($keys); $i++) { 
                
                $properties[] = new Property( $keys[i], array_key_exists( $i, $vals ) ? $vals[$i] : null );

            }

            return new static( ...$properties );
        }

        public static function EmptyObject()
        {
            return new static();
        }

        public function IsEmpty()
        {
            return count( $this->properties )>0?false:true;
        }

        /**
         * Summary of GetProperty
         * @param string $name
         * @return Property
         */

        public function GetProperty( string $name )
        {
            if ( $this->propertyExists( $name ) ) {
                
                return $this->properties[ $this->GetKeyIndex( $name ) ];

            }

            return null;
        }

        /**
         * Summary of PropertyArray
         * @return Property[]
         */

        public function PropertyArray()
        {
        
            return array_values( $this->properties );

        }

    }

    

?>