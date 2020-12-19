<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<style>
    .tbl_head01 thead th {
        background: #dae1e4;
        text-align: center;
        padding: 5px 0;
    }

    .tbl_head01 td {
        text-align: center;
    }

    #add_form {
        border: 2px solid grey;
        padding: 5px;
        margin: 10px;
        text-align: center;
        padding-bottom: 30px;
    }

    .add_form_input {
        padding: 10px;
        margin: 10px;
    }
</style>


<script>
    function hideElementById(id) {
        document.getElementById(id).style.display = "none"
    }

    function displayElementById(id) {
        document.getElementById(id).style.display = "block"
    }
</script>


<?php
//DB connection
$user = 'db_master';
$password = 'qwertyuiop';
$dbName = 'bizcos';
$host = 'bizcos.czjzq6s5u780.ap-northeast-2.rds.amazonaws.com';
$con = mysqli_connect($host, $user, $password, $dbName);
mysqli_query($con, "set session character_set_connection=utf8;");
mysqli_query($con, "set session character_set_results=utf8;");
mysqli_query($con, "set session character_set_client=utf8;");
mysqli_select_db($con, $dbName);
?>


<?php
$product_code = $_GET['product_code'];
console_log("product code : $product_code");

console_log($_COOKIE["need_clear"]);
if ($_COOKIE["need_clear"]) {
    clear_form_finish();
    if (count($_POST) > 0) {
        console_log("clear post data");
        header("Location://sfac1234.dothome.co.kr/skin/board/skin_recipe_mng/popup.skin.php?product_code=$product_code");
        exit;
    }
}
?>

<div class="tbl_head01 tbl_wrap">
    <?php
    echo "<h1>상품코드 : $product_code</h1>";
    ?>

    <table>
        <caption><?php echo $board['bo_subject'] ?>레시피 수정</caption>
        <p>값 입력 후 Enter키 누르면 입력됩니다. </br>
            단위 소요량 또는 비율 없을시 0을 입력해주세요.<p>
                <thead>
                    <tr>
                        <th scope="col" style="width:200px">NO.</th>
                        <th scope="col" style="width:200px">CODE</th>
                        <th scope="col" style="width:200px;">성분명</th>
                        <th scope="col" style="width:200px">성분유형</th>
                        <th scope="col" style="width:200px">단위</th>
                        <th scope="col" style="width:200px">단위 소요량</th>
                        <th scope="col" style="width:200px">비율(%)</th>
                        <th scope="col" style="width:200px">비고</th>
                        <?php
                        $sql = "SELECT recipe_id,r.material_code,m.material_sub_ko, m.material_type, m.material_unit, r.material_qty , r.material_ratio FROM recipe_mng AS r JOIN material AS m WHERE r.product_code='{$product_code}' and m.material_code=r.material_code";
                        $result = mysqli_query($con, $sql);
                        $i = 1;
                        while ($row = mysqli_fetch_array($result)) {
                            $filtered = array(
                                'material_code' => htmlspecialchars($row['material_code']),
                                'material_sub_ko' => htmlspecialchars($row['material_sub_ko']),
                                'material_type' => htmlspecialchars($row['material_type']),
                                'material_unit' => htmlspecialchars($row['material_unit']),
                                'material_qty' => htmlspecialchars($row['material_qty']),
                                'material_ratio' => htmlspecialchars($row['material_ratio'])
                            );
                        ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><input value="<?= $filtered['material_code'] ?>" style="width:50px;" id="code-input-<?= $i ?>"></input></td>
                        <td><?= $filtered['material_sub_ko'] ?></td>
                        <td><?= $filtered['material_type'] ?></td>
                        <td><?= $filtered['material_unit'] ?></td>
                        <td><input value="<?= $filtered['material_qty'] ?>" style="width:50px;" id="qty-input-<?= $i ?>"></input></td>
                        <td><input value="<?= $filtered['material_ratio'] ?>" style="width:50px;" id="ratio-input-<?= $i ?>"></input> % </td>
                        <td>-</td>
                    </tr>
                    <script>
                        document
                            .getElementById("code-input-<?= $i ?>")
                            .addEventListener("keyup", (ev) => {
                                const target = document.getElementById("code-input-<?= $i ?>")
                                console.log(`<?= $i ?>'s value changed ${target.value}`)
                                if (ev.key === "Enter") {
                                    console.log(`recipe will be updated with code ${target.value}`)
                                    document.cookie = `new_metarial_target=<?= $i ?>`
                                    document.cookie = `new_material_code=${target.value}`;
                                    location.reload()
                                }
                            })

                        document
                            .getElementById("qty-input-<?= $i ?>")
                            .addEventListener("keyup", (ev) => {
                                const target = document.getElementById("qty-input-<?= $i ?>")
                                console.log(`<?= $i ?>'s value changed ${target.value}`)
                                if (ev.key === "Enter") {
                                    console.log(`recipe will be updated with qty ${target.value}`)
                                    document.cookie = `new_metarial_target=<?= $i ?>`
                                    if (target.value == 0) {
                                        document.cookie = `new_material_qty=-1`;
                                    } else {
                                        document.cookie = `new_material_qty=${target.value}`;
                                    }
                                    location.reload()
                                }
                            })

                        document
                            .getElementById("ratio-input-<?= $i ?>")
                            .addEventListener("keyup", (ev) => {
                                const target = document.getElementById("ratio-input-<?= $i ?>")
                                console.log(`<?= $i ?>'s value changed ${target.value}`)
                                if (ev.key === "Enter") {
                                    console.log(`recipe will be updated with ratio ${target.value}`)
                                    document.cookie = `new_metarial_target=<?= $i ?>`
                                    if (target.value == 0) {
                                        document.cookie = `new_material_ratio=-1`;
                                    } else {
                                        document.cookie = `new_material_ratio=${target.value}`;
                                    }
                                    location.reload()
                                }
                            })
                    </script>
                <?php
                            $target = $_COOKIE["new_metarial_target"];
                            $new_material_code = $_COOKIE["new_material_code"];
                            $new_material_qty = $_COOKIE["new_material_qty"];
                            $new_material_ratio = $_COOKIE["new_material_ratio"];
                            console_log("target : $target, new_material_code : $new_material_code");
                            if ($new_material_code && $target == $i) {
                                // clear cookie
                                echo "<script>";
                                echo "document.cookie=`new_material_code=`;\n";
                                echo "document.cookie=`new_material_target=`;\n";
                                $recipe_id = $row['recipe_id'];
                                update_recipe($con, $recipe_id, "material_code", $new_material_code);
                                echo "console.log(\"update it $new_material_code\");\n";
                                echo "</script>\n";
                                // echo "location.reload();\n";
                            }
                            if ($new_material_qty && $target == $i) {
                                echo "<script>";
                                echo "document.cookie=`new_material_qty=`;\n";
                                echo "document.cookie=`new_material_target=`;\n";
                                $recipe_id = $row['recipe_id'];
                                if ($new_material_qty <= 0) {
                                    update_recipe($con, $recipe_id, "material_qty", "NULL");
                                } else {
                                    update_recipe($con, $recipe_id, "material_qty", $new_material_qty);
                                }
                                echo "console.log(\"update it $new_material_qty\");\n";
                                echo "</script>\n";
                            }
                            if ($new_material_ratio && $target == $i) {
                                echo "<script>";
                                echo "document.cookie=`new_material_ratio=`;\n";
                                echo "document.cookie=`new_material_target=`;\n";
                                $recipe_id = $row['recipe_id'];
                                if ($new_material_ratio <= 0) {
                                    update_recipe($con, $recipe_id, "material_ratio", "NULL");
                                } else {
                                    update_recipe($con, $recipe_id, "material_ratio", $new_material_ratio);
                                }
                                echo "console.log(\"update it $new_material_ratio\");\n";
                                echo "</script>\n";
                            }


                            $i++;
                        }
                ?>
                </tr>
                </thead>
    </table>

    <button class="btn_b03" onclick="displayElementById('add_form')">추가</button>
    <form id="add_form" method="post" action="">
        <h3>원재료 추가</h3>
        <p>[단위 또는 비율 중 적어도 1개는 입력해주세요.]</p>
        <span class="add_form_input">
            <label for="material_code">원재료 코드</label>
            <input id="material_code" type="text" name="material_code" />

            <label for="material_qty">단위</label>
            <input id="material_qty" type="text" name="material_qty" />

            <label for="material_ratio">비율</label>
            <input id="material_ratio" type="text" name="material_ratio" />

            <input type="submit" name="submit" class="btn_submit" value="완료">
        </span>
    </form>
    <?php
    hide_element_by_id("add_form");

    $material_code = $_POST["material_code"];
    $material_qty = $_POST["material_qty"];
    $material_ratio = $_POST["material_ratio"];


    console_log("product code : $product_code");
    console_log("material code : $material_code");
    console_log("material qt : $material_qty");
    console_log("material ratio : $material_ratio");

    function is_adding_material($product_code, $material_code, $material_qty, $material_ratio)
    {
        return $product_code && $material_code && ($material_qty || $material_ratio);
    }

    if (is_adding_material($product_code, $material_code, $material_qty, $material_ratio)) {
        add_new_material($con, $product_code, $material_code, $material_qty, $material_ratio);
        unset($_POST);
        console_log("post array length is");
        console_log(count($_POST));
        clear_form();
        reload_page();
    } else {
        console_log("can not add material to recipe.");
    }
    ?>
    <button class="btn_b03" onclick="self.close()">등록</button>
</div>

<!-- functions -->
<?php
function console_log($message)
{
    echo "<script>console.log(`$message`);\n</script>";
}

function console_err($err)
{
    echo "<script>console.log(`error : $err`);\n</script>";
}

function reload_page()
{
    echo "<script>location.reload();</script>\n";
}

function clear_form()
{
    echo "<script>document.cookie= \"need_clear=true\";\n</script>";
}

function clear_form_finish()
{
    echo "<script>document.cookie= \"need_clear=\";\n</script>";
}

function hide_element_by_id($id)
{
    echo "<script>hideElementById('$id');\n</script>";
}


// calling inside script tag
function update_recipe($con, $recipe_id, $material_column, $material_value)
{
    $sql_query = "
        UPDATE bizcos.recipe_mng
        SET $material_column= $material_value
        WHERE recipe_id = $recipe_id
    ";

    $result_insert = mysqli_query($con, $sql_query);
    if ($result_insert == false) {
        // echo "레시피 변경 실패. 관리자에게 문의하세요.";
        $err = mysqli_error($con);
        echo "console.log(`$err`);\n";
    } else {
        echo "location.reload();\n";
        // echo "레시피 변경 완료.";
    }

    // header("Location: //sfac1234.dothome.co.kr/skin/board/skin_recipe_mng/popup.skin.php?product_code=$product_code");
    // exit;
}


function add_new_material($con, $product_code, $material_code, $material_qty, $material_ratio)
{
    if (!$material_qty) {
        $material_qty = "NULL";
    }
    if (!$material_ratio) {
        $material_ratio = "NULL";
    }

    $sql_query_add_material = "INSERT INTO recipe_mng(product_code,material_code, material_qty,material_ratio)  
					VALUES (\"$product_code\", $material_code, $material_qty, $material_ratio)";
    console_log($sql_query_add_material);
    $insert_material_result = mysqli_query($con, $sql_query_add_material);
    if ($insert_material_result == false) {
        echo "레시피 저장 실패. 관리자에게 문의하세요.";
        error_log(mysqli_error($con));
    } else {
        echo "레시피 저장 완료.";
        // reload_page();
    }
}
?>