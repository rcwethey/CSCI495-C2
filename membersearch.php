<?php
/**
 * Created by PhpStorm.
 * User: rcwethey
 * Date: 9/21/2019
 * Time: 5:39 PM
 */


$pagename = "Search Members";
require_once "header.inc.php";
?>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript">

    $(document).ready(function () {
        $('search-box input[type="text"]').on("keyup input", function() {
            /* Get input value on change */
            var inputVal = $(this).val();
            var resultDropdown = $(this).siblings(".result");
            if(inputVal.length){
                $.get("membersearchcode.inc.php", {term:inputVal}).done(function(data)){
                    resultDropdown.html(data);
                });
            }
            else{
                resultDropdown.empty();
            }
        });

        $(document).on("click", ".result p", function(){
            $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
            $(this).parent(".result").empty();
        })
    });
</script>
<div class="search-box">
    <input type="text" autocomplete="off" placeholder="Search username . . ." />
    <div class="result"></div>
</div>

<?php
include_once "footer.inc.php";
?>




