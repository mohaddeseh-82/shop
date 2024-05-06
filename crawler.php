<?php
include( 'includes/init.php' );

$category   = 'notebook-netbook-ultrabook';
$band       = 'acer';
$page       = 1;

function crawl( $category, $page = 1 ){

    $url = sprintf(
        'https://api.digikala.com/v1/categories/%s/search/?page=%d'
        , $category
        , $page
    );

    $data           = file_get_contents( $url );
    $data           = json_decode( $data );
    $total_page     = $data->data->pager->total_pages;
    $total_items    = $data->data->pager->total_items;

    foreach( $data->data->products as $product ){
        $product_id        = $product->id;
        //$product_id        = 14017405;
        
        $product_api_data   = file_get_contents( 'https://api.digikala.com/v2/product/' . $product_id . '/' );
        $product_api_data   = json_decode( $product_api_data );

        $product            = $product_api_data->data->product;

        if( $product->status != 'marketable' || ! isset( $product->default_variant ) ){
            continue;
        }

        $thumbnail_path     = explode( '?', $product->images->main->url[0] );
        $thumbnail_url      = $thumbnail_path[0];
        $thumbnail_path     = pathinfo( $thumbnail_url );
        file_put_contents( 'uploads/products/' . $thumbnail_path['basename'], file_get_contents( $thumbnail_url ) );
        $thumbnail_path     = 'uploads/products/' . $thumbnail_path['basename'];

        $content            = isset( $product->review->description ) ? $product->review->description : '';

        $price              = $product->default_variant->price->rrp_price;
        $sale_price         = $product->default_variant->price->selling_price;
    
        $discount_date      = isset( $product->default_variant->price->timer ) ? date( 'Y-m-d H:i:s', time() + $product->defaule_variant->price->timer ) : NULL;

        $discount_percent   = 0;
        if( $price > $sale_price ){
            $discount_percent = round( ( $price - $sale_price ) / $price * 100 );
        }

        $product_data       = [
            'title'             => $product->title_fa,
            'content'           => $content,
            'thumbnail'             => $thumbnail_path,
            'price'             => $price,
            'discount_percent'  => $discount_percent,
            'discount_date'     => $discount_date,
            'total_sale'        => 0,
            'sale_count'        => 0,
            'stock'             => rand( 0, 15 ),
            'created_at'        => date( 'Y-m-d H:i:s' )
        ];

        extract( $product_data );

        $discount_date = $discount_date ? "'$discount_date'" : 'NULL';
        
        /**
         * If not exists
         */
        $sql_stmt  = db()->query("SELECT * FROM products WHERE title = '$title'");
        $exists     = $sql_stmt->fetch();
        if( $exists ){
            continue;
        }

        db()->exec(
            "
            INSERT INTO products
                (title, content, thumbnail, price, discount_percent, discount_date, stock, created_at)
                VALUES
                ('$title', '$content', '$thumbnail', $price, $discount_percent, $discount_date, $stock, '$created_at')
            "
        );

    }

    if( $page < 6 ){
        echo $category . ' => page ' . $page . ' Finished' . PHP_EOL;
        $page++;
        crawl( $category, $page );
    }

}

//crawl('notebook-netbook-ultrabook');
//crawl('mobile-phone');
//crawl('game-console');
//crawl('headphone');
//crawl('wearable-gadget');
//crawl('digital-camera');
//crawl('video-audio-entertainment');
//crawl('washing-machines');
//crawl('airtreatment');

//$url = 'https://api.digikala.com/v1/categories/notebook-netbook-ultrabook/brands/acer/search/?page=1';

