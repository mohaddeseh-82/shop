<div class="col-3">
    <div class="card mb-2" style="">
        <?php
        if( $product['discount_percent'] ){
            echo '<span class="discount-badge">' . $product['discount_percent'] . '%</span>';
        }
        ?>
        <img width="260" height="260" loading="lazy" src="<?php echo image_url( $product['thumbnail'] );?>" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">
                <?php echo $product['title'];?>
            </h5>
            <p class="card-text">
                <?php echo mb_substr( $product['content'], 0, 60 );?>
            </p>
            <?php
            if( $product['stock'] < 4 )
            {
                echo $product['stock'] . ' محصول باقی مانده در انبار';
            }
            ?>
            <a href="<?php echo url( 'product.php?id=' . $product['ID'] );?>" class="btn btn-primary">Go somewhere</a>
        </div>
    </div>
</div>