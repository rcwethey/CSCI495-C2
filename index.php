<?php
/**
 * Created by PhpStorm.
 * User: rcwethey
 * Date: 9/18/2019
 * Time: 9:26 PM
 */

$pagename = "Home";
require_once "header.inc.php";
?>
<div id="sidebar1">
    <h3>What We do</h3>
        <p>
        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dolor eos atque odio explicabo esse molestiae molestias iusto id in velit, laboriosam odit nam neque nostrum aliquid aut quasi corrupti tempora.
        </p>
    <h3>
        Why We Do It
    </h3>
        <p>
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorum dolores, placeat excepturi magnam obcaecati hic reprehenderit quasi amet voluptatum consequatur assumenda sed repellendus facilis minus dolorem facere deserunt quidem officia!
        </p>
    <ul class="linkedList">
        <li class="first">
            <a href="#">Our Comany</a>
        </li>
        <li>
            <a href="#">Our Vision</a>
        </li>
        <li class="last">
            <a href="#">Sign Up</a>
        </li>
    </ul>
</div>
<div id="sidebar2">
    <h3>Company Sponsors</h3>
    <br>
    <ul class="linkedList">
        <li class="first">
            <a href="#">1-880-CONTACTS</a>
        </li>
        <li>
            <a href="#">Ray Band</a>
        </li>
        <li class="last">
            <a href="#">OASIS</a>
        </li>
    </ul>
</div>
<div id="content">
    <div id="box1">
        <h2>TO SEE IS TO BREATHE</h2>
        <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Ea magnam porro quae modi maxime, accusantium libero animi architecto sit minus distinctio, 
            possimus consequuntur. Labore ratione facere eos omnis dolore sed. Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid error architecto 
            dolorum nam similique aut quod officia dignissimos, iure quaerat tenetur! Ea ullam suscipit voluptatem, quia iste ex cupiditate repellat.
        </p>
        <br><br><br><br><br>
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d13263.113104575224!2d-79.02427825!3d33.79222345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sus!4v1555727283912!5m2!1sen!2sus" width="400" height="300"  frameborder="0" style="border:0;transform: translate(60px, -80px);" allowfullscreen></iframe><hr>
    </div>
    <div id="box2">
        <h2>Quotes</h2>
        <!--<p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Fugit debitis totam perspiciatis accusamus porro consequuntur, autem, expedita recusandae perferendis commodi eveniet soluta!</p>-->
        <?php
        include_once "contentlist.php";
        ?>
    </div>
    <div id="box3">
        <h2>Glasses Brands</h2>
        <br>
        <!--<p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Fugit debitis totam perspiciatis accusamus porro consequuntur, autem, expedita recusandae perferendis commodi eveniet soluta!</p>-->
    </div>
</div>

<?php
require_once "footer.inc.php";
?>
<div id="copyright">
    &copy; D2R.com | Design by Ryan C. Wethey
</div>

