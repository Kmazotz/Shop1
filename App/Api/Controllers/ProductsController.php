<?php

    namespace App\Api\Controllers;

    use App\Core\Request;
    use App\Core\Controller;
    use Database\Connection;
    use App\Core\EntityModel;
    use Support\Objects\ObjectContainer;
    use Support\Collections\ObjectCollection;
    use Symfony\Component\Validator\Validation;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\Validator\Constraints\Email;
    use Symfony\Component\Validator\Constraints\Regex;
    use Symfony\Component\Validator\Constraints\Length;
    use Symfony\Component\Validator\Constraints\EqualTo;
    use Symfony\Component\Validator\Constraints\NotNull;
    use Symfony\Component\Validator\Constraints\NotBlank;
    use Support\Collections\ArrayCollection;
    use Support\Objects\Property;

    /**
     * Products short summary.
     *
     * Products description.
     *
     * @version 0.1
     * @author Brayan Alexis Angulo R.
     */

    class ProductsControllers extends Controller
    {
        /**
         * Summary of $products
         * @var Stock
         */
        private $products;

        /**
         * @param Request $request
         * @return JsonResponse
         */

        public function Delete(): JsonResponse
        {

            //Code...

        }

        /**
         * Delete
         *
         * @param Request $request
         *
         * @return mixed
         */

        public function Filter(): JsonResponse
        {

            //Code...

        }

        /**
         * InitializeComponents
         * @param Request $request
         * @return void
         */

        public function InitializeComponents(): void
        {

            $this->products = Products::Create(Connection::GetConnection(),

                'ID',
                'productID',
                'productName',
                'unitPrice',
                'Stock',
                'Status',
                'cantSell'

            );

            $this->products->AddConstraint( 'ID',

                new NotBlank([ 'message' => 'El campo ID no debe de estar vacio.' ]),
                new NotNull([ 'message' => 'El campo ID no puede ser nulo' ])

            );

            $this->product->AddConstraint( 'productID',

                new NotBlank([ 'message' => 'El campo productID no debe de estar vacio.' ]),
                new NotNull([ 'message' => 'El campo productID no debe ser nulo.' ]),
                new Lenght([ 'min' => 10 , 'max' => 20, 'minMessage' => 'El campo productID debe dener una longitud minima de [10] caracteres.',
                            'maxMessage' => 'El campo productID debe tener una longitud maxima de [20] caracteres.']),
                new Regex(['pattern' => '/^[A-Za-zñÑ]+(\s[A-Za-zñÑ]+|[A-Za-zñÑ]+)+$/', 'message' => 'El campo productID debe conterner numeros y letras, y no puede contener espacios al inicio y al final..'])

            );

            $this->product->AddConstraint( 'productName',

                new NotBlank([ 'message' => 'El campo productName no debe de estar vacio.' ]),
                new NotNull([ 'message' => 'El campo productName no debe ser nulo.' ]),
                new Lenght([ 'min' => 3 , 'max' => 45, 'minMessage' => 'El campo productName debe dener una longitud minima de [3] caracteres.',
                            'maxMessage' => 'El campo productName debe tener una longitud maxima de [45] caracteres.']),
                new Regex(['pattern' => '/^[A-Za-zñÑ]+(\s[A-Za-zñÑ]+|[A-Za-zñÑ]+)+$/', 'message' => 'El campo productName no debe contener numeros y letras, y no puede contener espacios al inicio y al final..'])

            );

            $this->product->AddConstraint( 'productName',

                new NotBlank([ 'message' => 'El campo productName no debe de estar vacio.' ]),
                new NotNull([ 'message' => 'El campo productName no debe ser nulo.' ]),
                new Lenght([ 'min' => 4 , 'max' => 9, 'minMessage' => 'El campo productName debe dener una longitud minima de [4] caracteres.',
                            'maxMessage' => 'El campo productName debe tener una longitud maxima de [9] caracteres.'])
            );

        }

        /**
         * Store
         *
         * @param Request $request
         *
         * @return Response
         */

        public function Store(): JsonResponse
        {

            //Code...

        }

        /**
         * Update
         * 
         * @param Request $request
         * 
         * @return Response
         */

        public function Update(): JsonResponse
        {

            //Code...

        }

    }

?>