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

    use JsonSerializable;

    class Property implements JsonSerializable
    {

        /**
         * Summary of $name
         * @var string
         */
        private $name;

        /**
         * Sumary of $value
         * @var mixed
         */

        private $value;

        /**
         * Create a new instance of property
        * @param string $name
        * @param mixed $value
        * @return Property
        */

        public static function Property( string $name, $value = null )
        {

            return new static( $name, $value );

        }

        /**
         * Sumary of PropertyName
         * @return string
         */

        public function PropertyName(): string
        {

            return $this->name;

        }

        /**
         * Sumary of SetPropertyName
         * @return string $name
         */

        public function SetPropertyName( string $name ): void
        {

            $this->name = $name;

        }

        /**
         * Sumary of SetValue
         * @return mixed $value
         */

        public function SetValue( $value ): void
        {

            $this->value = $value;

        }

        /**
         * Sumary of ToArray
         * @return array
         */

        public function ToArray(): array
        {

            return [ $this->name => $this->value ];

        }

        /**
         * Sumary of ToJson
         * @return string
         */

        public function ToString(): string
        {

            return json_encode( $this->ToArray(), JSON_FORCE_OBJECT );

        }

        /**
         * Sumary of Value
         * @return mixed
         */

        public function Value()
        {

            return $this->value;

        }

        /**
         * Sumary of __construct
         * @param string $name
         * @param mixed $value
         */

        public function __construct( string $name, $value )
        {

            $this->name;
            $this->value;

        }

        /**
         * Specify data which should be serialized to JSON
         * Serializes the object to a value that can be serialized natively by json_encode() .
         *
         * @return array
         */

        public function JsonSerialize(): array
        {

            return $this->ToArray();

        }

    }

?>