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

    use App\Core\Model;

    class ModelCollection extends ItemCollection
    {
        /**
         * Summary of $modelCollection
         * @var Model[]
         */
        private $modelCollection;

        public function ToArray()
        {
            $array = [];

            foreach($this->modelCollection as $model)
            {
                $array[] = $model->AsArray();
            }

            return $array;
        }
    }

?>