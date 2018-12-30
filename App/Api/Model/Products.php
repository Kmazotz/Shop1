<?php

    namespace App\Api\Models;

    /**
     * Products short summary.
     *
     * Products description.
     *
     * @version 0.1
     * @author Brayan Alexis angulo R.
     */

    use App\Core\Model;

    class Products extends Model
    {
        
        /**
     * @var array
     */
    protected $fillable = ['ID', 'productID', 'productName', 'unitPrice', 'Stock', 'Status', 'cantSell'];

    /**
     * @var mixed
     */
    protected $incrementing = false;

    /**
     * @var string
     */
    protected $keyType = 'varchar';

    /**
     * @var string
     */
    protected $primaryKey = 'productID';

    /**
     * @var string
     */
    protected $table = 'products';

    }

?>