<?php

if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
// 선택옵션으로 인해 셀합치기가 가변적으로 변함
$colspan = 7;

if ($is_checkbox) $colspan++;
if ($is_good) $colspan++;
if ($is_nogood) $colspan++;

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . $board_skin_url . '/style.css">', 0);


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

//paging
if (isset($_GET["page"]))
	$page = $_GET["page"];
else
	$page = 1;

$url = '//sfac1234.dothome.co.kr/bbs/board.php?bo_table=recipe_mng';

$search = $_GET['search'];
?>

<style>
	
	.tbl_head01 thead th {
		background: #dae1e4;
		text-align: center;
		padding: 5px 0;
	}

	.tbl_head01 td {
		text-align: center;
	}

	.search_form{
		display : inline-block;
	}
	
	/* 2020.11.13 해선 */
	#product_name {
		font-size: 15px;
		margin-right: 5px;
		font-weight: bold;
	}

	/* input[type=text]{
		width : 200px;
		padding : 3px 3px;
		margin : 8px 8px;
		box-sizing : border-box;

	} */

	#add_form {
		border: 2px solid grey;
		padding : 5px;
		margin : 10px;
		text-align : center;
		padding-bottom : 30px;
	}

	.add_form_input{
		padding : 10px;
		margin : 10px;
	}

	.btn_add{
		
	}

	h1{
		margin: 10px;
	}

</style>

<script>
	function open_popup(productCode) {
		console.log(productCode)
		const searcUrl = new URLSearchParams({
			product_code: productCode
		})
		console.log(searcUrl.toString())
		openWin = window.open(`//sfac1234.dothome.co.kr/skin/board/skin_recipe_mng/popup.skin.php?${searcUrl.toString()}`, 'popup', 'width=800,height=500,scrollbars=no');
		openWin;
	}

	function deleteProduct(id){
		window.location.search = window.location.search + `&delete=${id}`
	}

	function hideElementById(id){
		document.getElementById(id).style.display = "none"
	}

	function displayElementById(id){
		console.log(id)
		document.getElementById(id).style.display = "block"
	}
</script>

<!-- read init Table -->
<?php
$sql_search = "SELECT product_name_ko,product_type,product_unit,create_time FROM vr_product WHERE product_name_ko LIKE '%{$search}%' ORDER BY create_time DESC ";
$result_page = mysqli_query($con, $sql_search);
$total_count = mysqli_num_rows($result_page);
?>


<!-- 게시판 목록 시작 { -->
<div id="bo_list" style="width:<?php echo $width; ?>">
	<?php if ($is_category) { ?>
		<nav id="bo_cate" style="display:none;">
			<h2><?php echo $board['bo_subject'] ?> 카테고리</h2>
			<ul id="bo_cate_ul">
				<?php echo $category_option ?>
			</ul>
		</nav>
	<?php } ?>
	<!-- } 게시판 카테고리 끝 -->


	<!-- 제품명 조회 및 신규등록 -->
	<form class="search_form" name="wsearch" method="get">
		<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
		<span class="tbl_search01 tbl_wrap">
			<label for="input_product_name" id="product_name">제품명</label>
			<input id="input_product_name" type="text" name="search" required />
			<button class="btn_b03">조회</button>
		</span>
	</form>
	<button id="btn-create-product" class="btn_b03" onclick="displayElementById('add_form')">신규등록</button>

	<?php
	$search = $_GET['search'];
	Console_log($search)
	?>

	<form id="add_form" method="post">
		<h1>제품 신규 등록</h1>
		<span class ="add_form_input"> 
			<label for="product_code" >제품코드</label>
			<input id="product_code" type="text" name="product_code"/>
			<label for="product_name" >제품명</label>
			<input id="product_name" type="text" name="product_name"/>
			<label for="product_type" >제품유형</label>
			<input id="product_type" type="text" name="product_type"/>
			<label for="product_unit" >단위</label>
			<input id="product_unit" type="text" name="product_unit"/>
		</span>
		<input type="submit" class="btn_b03" value="등록"> 
	</form>
	<?php
	$product_code = $_POST["product_code"];
	$product_name = $_POST["product_name"];
	$product_type = $_POST["product_type"];
	$product_unit = $_POST["product_unit"];

	if($product_code && $product_name && $product_type && $product_unit){
		create_new_product($con,$product_code,$product_name, $product_type, $product_unit);
		$product_code=null;
	}
	
	hide_element_by_id("add_form");
	?>



	<form name="fboardlist" id="fboardlist" action="./board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
		<input type="hidden" name="wr_invoice_no" id="wr_invoice_no" value="">
		<!-- paging -->
		<div class="bo_fx">
			<div id="bo_list_total">
				<span>Total <strong><?php echo number_format($total_count) ?></strong>건</span>
				<strong><?php echo $page ?></strong> Page
			</div>
		</div>

		<div class="tbl_head01 tbl_wrap">
			<?php

			if ($search && $total_count > 0) {
				echo "<p><span>'<b>$search</b>'의 검색결과는 다음과 같습니다.</span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<a href='$url'>▷전체 제품 보기</a></p>";
			} else if ($search && $total_count == 0) {
				echo "<div><span>'<b>$search</b>'의 검색결과가 없습니다. 제품을 신규등록 해주세요.</span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<a href='$url'>▷전체 제품 보기</a></div>";
			}
			?>
			<table>
				<caption><?php echo $board['bo_subject'] ?>목록</caption>
				<thead>
					<tr>
						<th scope="col" style="width:200px">제품명</th>
						<th scope="col" style="width:200px">제품유형</th>
						<th scope="col" style="width:00px;">단위</th>
						<th scope="col" style="width:200px">등록일</th>
						<th scope="col" style="width:200px"></th>

						<?php


						$list_count = 10;
						$page_count = 10;
						$page_num = ceil($page / $page_count);
						$page_start = (($page_num - 1) * $page_count) + 1;
						$page_end = $page_start + $page_count - 1;
						$total_page = ceil($total_count / $list_count);
						if ($page_end > $total_page) {
							$page_end = $total_page;
						}
						$total_pages = ceil($total_pages / $page_count);
						$pages_start = ($page - 1) * $list_count;

						$sql_search = "SELECT product_code,product_name_ko,product_type,product_unit,create_time FROM vr_product WHERE product_name_ko LIKE '%{$search}%' ORDER BY create_time DESC LIMIT $pages_start, $list_count";

						// $sql_total="SELECT wr_subject, wr_type, wr_unit, wr_datetime FROM g5_write_product ORDER BY wr_datetime DESC LIMIT $pages_start, $list_count";
						$result_page = mysqli_query($con, $sql_search);

						while ($row = mysqli_fetch_array($result_page)) {
							$filtered = array(
								'product_code' => htmlspecialchars($row['product_code']),
								'product_name_ko' => htmlspecialchars($row['product_name_ko']),
								'product_type' => htmlspecialchars($row['product_type']),
								'product_unit' => htmlspecialchars($row['product_unit']),
								'create_time' => htmlspecialchars($row['create_time']),
							);
							
						?>
					<tr>
						<td><?= $filtered['product_name_ko'] ?></td>
						<td><?= $filtered['product_type'] ?></td>
						<td><?= $filtered['product_unit'] ?></td>
						<td><?= $filtered['create_time'] ?></td>
						<td><input type="button" class="btn_b03" onclick="deleteProduct(`<?= $row['product_code'] ?>`)" value="삭제"> 
							<input type="button" class="btn_b03" onclick="open_popup(`<?= $row['product_code'] ?>`)" value="수정">
						</td>
					</tr>
				<?php
				$delete_id = $_GET['delete'];
				if($delete_id){
					delete_product($con, $delete_id);
				}
						}
				?>

				</tr>
				</thead>
			</table>

			<div id="page_num" style="text-align:center;">
				<?php
				if ($page <= 1) {
				} else {
					echo "<a href='$url&search=$search&page=1'>처음 </a>";
				}
				if ($page <= 1) {
				} else {
					$pre = $page - 1;
					echo "<a href='$url&search=$search&page=$pre'>◀ </a>";
				}
				for ($i = $page_start; $i <= $page_end; $i++) {
					if ($page == $i) {
						echo "<b>$i</b>";
					} else {
						echo "<a href='$url&search=$search&page=$i'> $i </a>";
					}
				}
				if ($page >= $total_page) {
				} else {
					$next = $page + 1;
					echo "<a href='$url&search=$search&page=$next'>▶ </a>";
				}

				if ($page >= $total_page) {
				} else {
					echo "<a href='$url&search=$search&page=$total_page'>마지막 </a>";
				}

				?>
			</div>
		</div>

</div>


<?php

function hide_element_by_id($id){
	echo "<script>hideElementById('$id')</script>";
}

function display_element_by_id($id){
	echo "<script>displayElementById('$id')</script>";
}

function alertMessage($message)
{
	echo "<script>alert('{$message}')</script>";
}

function Console_log($data)
{
	echo "<script>console.log(`$data`);</script>";
}

function delete_product($con, $code){
	$query = "DELETE from vr_product where product_code = '$code'";
	Console_log("query : $query");

	$result_delete = mysqli_query($con, $query);
	if($result_insert==false){
		echo "상품 삭제 실패. 관리자에게 문의하세요.";
		error_log(mysqli_error($con));
	}else{
		echo "상품 삭제 완료.";
	}
	header("Location: //sfac1234.dothome.co.kr/bbs/board.php?bo_table=recipe_mng");
	exit;
}

function create_new_product($con,$code,$name, $type, $unit){
	Console_log($code);
	$filtered=array(
	'product_code'=>mysqli_real_escape_string($con,$code),
	'product_name_ko'=>mysqli_real_escape_string($con,$name),
	'product_type'=>mysqli_real_escape_string($con,$type),
	'product_unit'=>mysqli_real_escape_string($con,$unit)
	);
	$tmp = mysqli_real_escape_string($con, $code);
	$sql_insert = "INSERT INTO vr_product(product_code,product_name_ko, product_type,product_unit,create_time)  
					SELECT * FROM (SELECT '{$filtered['product_code']}','{$filtered['product_name_ko']}','{$filtered['product_type']}','{$filtered['product_unit']}',NOW()) as t
	 					WHERE NOT EXISTS (
		 					SELECT product_code FROM vr_product WHERE product_code = '{$filtered['product_code']}'
							)";

							//상품코드 겹칠 때 에러메세지

	Console_log($sql_insert);
	$result_insert = mysqli_query($con, $sql_insert);
	if($result_insert==false){
		echo "상품 저장 실패. 관리자에게 문의하세요.";
		error_log(mysqli_error($con));
	}else{
		echo "상품 저장 완료.";
	}
	header("Location: //sfac1234.dothome.co.kr/bbs/board.php?bo_table=recipe_mng");
	exit;
}
?>
