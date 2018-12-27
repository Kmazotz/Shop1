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

        /**
         * Summary of PropertyExists
         * @param string $name
         * @return boolean
         */

        public function PropertyExists( string $name ): bool
        {
            $lowerKeys = array_map( function( $val )
            {
            
            return strtolower( $val->PropertyName() );

            }, $this->properties);

            return in_array( strtolower( $name ), $lowerKeys );

        }

        /**
         * Summary of SetProperty
         * @param string $name
         * @param mixed|Property $value
         * @return boolean
         */

        public function SetProperty( string $name, $val ): bool
        {

            if( $this->PropertyExists($name) )
            {

                unset( $this->properties[$name] );

                $this->properties[ $value->PropertyName() ] = $value;

                $this->properties[ $this->GetKeyIndex( $name ) ]->setValue( $value );

                return true;

            }

            return false;

        }

        /**
         * Sumary of ToArray
         * @return array
         */
        public function ToArray()
        {

            return array_map(

                function (Property $property)
                {

                    return $property->Value();

                }, $this->properties

            );

        }

        /**
         * Sumary of ToJson
         * @return string
         */

        public function ToJson()
        {

            return json_encode( $this->ToArray(), JSON_FORCE_OBJECT );

        }

        /**
         * Sumary of __construct
         * @param array $properties
         */

        public function __construct( Property ...$properties )
        {

            foreach( $properties as $property )
            {

                $this->properties[ $property->PropertyName() ] = $property;

            }

            if ( empty( $properties ) ) {
                
                $this->properties = [];

            }

        }

        /**
         * Sumary of __get
         * @param string $name
         * @return Property
         */

        public function _get($name)
        {

            return $this->GetProperty( $name );

        }

        /**
         * Summary of __set
         * @param string $name
         * @param mixed|Property $value
         * @return boolean
         */

        public function __set( $name, $value ): bool
        {

            return $this->SetProperty($name, $value);

        }

        /**
         * Retrieve an external iterator
         * Returns an external iterator.
         *
         * @return Traversable
         */

        public function GetIterator(): Traversable
        {

            return new ArrayIterator( $this->properties );

        }

        /**
         * Specify data which should be serialized to JSON
         * Serializes the object to a value that can be serialized natively by json_encode() .
         *
         * @return array
         */

        public function JsonSerialize()
        {

            return $this->ToArray();

        }

        /**
         * Summary of GetKeyIndex
         * @param mixed $value
         * @return string|null
         */
        
        public function GetKeyIndex( $value )
        {

            $lowerKeys = array_map(

                function ( $val )
                {

                    return strtolower( $val->PropertyName() );

                }, $this->properties

            );

            return array_search( strtolower( $value ), $lowerKeys );

        }

    }

    

?>