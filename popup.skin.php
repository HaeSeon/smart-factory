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

	
</style>
<?php
//DB connection
$user = 'db_master';
$password = 'qwertyuiop';
$dbName = 'virtual_db_haeseon_20201120';
$host = 'bizcos.czjzq6s5u780.ap-northeast-2.rds.amazonaws.com';
$con = mysqli_connect($host, $user, $password, $dbName);
mysqli_query($con, "set session character_set_connection=utf8;");
mysqli_query($con, "set session character_set_results=utf8;");
mysqli_query($con, "set session character_set_client=utf8;");
mysqli_select_db($con, $dbName);

?>

<div class="tbl_head01 tbl_wrap">
    <?php
    $product_code=$_GET['product_code'];
    echo "<h1>상품코드 : $product_code</h1>";
    ?>
    
    <table>
        <caption><?php echo $board['bo_subject'] ?>레시피 수정</caption>
        <thead>
            <tr>
                <th scope="col" style="width:200px">NO.</th>
                <th scope="col" style="width:200px">CODE</th>
                <th scope="col" style="width:200px;">성분명</th>
                <th scope="col" style="width:200px">성분유형</th>
                <th scope="col" style="width:200px">단위</th>
                <th scope="col" style="width:200px">단위 소요량</th>
                <th scope="col" style="width:200px">비고</th>

                <?php
                $sql= "SELECT r.material_code,m.material_name_ko, m.material_type, m.material_unit, r.qt FROM vr_recipe AS r JOIN vr_material AS m WHERE r.product_code='{$product_code}' and m.material_code=r.material_code";
                $result=mysqli_query($con,$sql);
                $i=1;
                $sql_material="SELECT material_code FROM vr_material";
                $result_material=mysqli_query($con,$sql_material);
                // Console_log("hi");
                while ($row = mysqli_fetch_array($result)) {
                    $filtered = array(
                        'material_code' => htmlspecialchars($row['material_code']),
                        'material_name_ko' => htmlspecialchars($row['material_name_ko']),
                        'material_type' => htmlspecialchars($row['material_type']),
                        'material_unit' => htmlspecialchars($row['material_unit']),
                        'qt' => htmlspecialchars($row['qt'])
                    );
                    ?>
                    <tr>
                        <td><?=$i?></td>
                        <td>
                            <select name="material_option">
                                <option value="1">CO01</option>
                                <option value="2">CO02</option>
                            
                            </select>
                        </td>
                        <td><?= $filtered['material_name_ko'] ?></td>
                        <td><?= $filtered['material_type'] ?></td>
                        <td><?= $filtered['material_unit'] ?></td>
                        <td><?=$filtered['qt']?></td>
                        <td>-</td>
                    </tr>
                <?php
                    $i++;
                    }
                ?>    
            </tr>
        </thead>
    </table>
    <button class="btn_b03">추가</button>
    <button class="btn_b03">등록</button>
</div>